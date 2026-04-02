import React, { useEffect, useState } from 'react';
import { StyleSheet, Text, View, FlatList, TouchableOpacity, ActivityIndicator } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import useAuthStore from '../store/authStore';
import useQueueStore from '../store/queueStore';

const HomeScreen = ({ navigation }) => {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const logout = useAuthStore((state) => state.logout);
  const queue = useQueueStore((state) => state.queue);
  const syncQueue = useQueueStore((state) => state.syncQueue);

  useEffect(() => {
    fetchEvents();
    
    // Bắt đầu chu kỳ đồng bộ hàng chờ mỗi 15 giây
    const interval = setInterval(() => {
      syncQueue();
    }, 15000);

    return () => clearInterval(interval);
  }, []);

  const fetchEvents = async () => {
    try {
      const response = await api.get('/events');
      setEvents(response.data.data); // Laravel Resource thường bọc trong 'data'
    } catch (error) {
      console.error('Lỗi khi tải sự kiện:', error);
    } finally {
      setLoading(false);
    }
  };

  const renderItem = ({ item }) => (
    <TouchableOpacity 
      style={styles.card}
      onPress={() => navigation.navigate('EventDetail', { event: item })}
    >
      <Text style={styles.eventName}>{item.ten_su_kien}</Text>
      <Text style={styles.eventInfo}>📅 {new Date(item.thoi_gian_bat_dau).toLocaleString()}</Text>
      <Text style={styles.eventInfo}>📍 {item.dia_diem}</Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {loading ? (
        <ActivityIndicator size="large" color="#007bff" style={{ marginTop: 50 }} />
      ) : (
        <FlatList
          data={events}
          renderItem={renderItem}
          keyExtractor={(item) => item.ma_su_kien.toString()}
          contentContainerStyle={styles.list}
          ListEmptyComponent={<Text style={styles.empty}>Không có sự kiện nào.</Text>}
        />
      )}
      <TouchableOpacity style={styles.logoutBtn} onPress={logout}>
        <Text style={styles.logoutText}>Đăng xuất</Text>
      </TouchableOpacity>

      {queue.length > 0 && (
        <View style={styles.syncStatus}>
          <MaterialIcons name="sync" size={14} color="#007bff" />
          <Text style={styles.syncText}>Đang chờ đồng bộ: {queue.length}</Text>
        </View>
      )}

      <TouchableOpacity 
        style={styles.fab} 
        onPress={() => navigation.navigate('QRScanner')}
      >
        <MaterialIcons name="qr-code-scanner" size={28} color="#fff" />
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f4f4f4',
  },
  list: {
    padding: 15,
  },
  card: {
    backgroundColor: '#fff',
    padding: 15,
    borderRadius: 10,
    marginBottom: 15,
    boxShadow: '0px 2px 4px rgba(0,0,0,0.1)',
    elevation: 2,
  },
  eventName: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 5,
  },
  eventInfo: {
    fontSize: 14,
    color: '#666',
    marginTop: 3,
  },
  empty: {
    textAlign: 'center',
    marginTop: 50,
    color: '#999',
  },
  logoutBtn: {
    margin: 20,
    padding: 10,
    backgroundColor: '#dc3545',
    borderRadius: 5,
    alignItems: 'center',
  },
  logoutText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  fab: {
    position: 'absolute',
    bottom: 20,
    right: 20,
    backgroundColor: '#007bff',
    width: 60,
    height: 60,
    borderRadius: 30,
    justifyContent: 'center',
    alignItems: 'center',
    boxShadow: '0 4px 6px rgba(0,0,0,0.3)',
    elevation: 8,
  },
  syncStatus: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    padding: 10,
    backgroundColor: '#e7f3ff',
    borderRadius: 20,
    marginHorizontal: 40,
    marginBottom: 5,
  },
  syncText: {
    marginLeft: 5,
    fontSize: 12,
    color: '#007bff',
    fontWeight: '600',
  },
});

export default HomeScreen;
