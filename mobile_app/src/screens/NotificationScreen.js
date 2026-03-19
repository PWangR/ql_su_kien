import React, { useEffect, useState } from 'react';
import { StyleSheet, Text, View, FlatList, ActivityIndicator, TouchableOpacity } from 'react-native';
import api from '../services/api';
import { MaterialIcons } from '@expo/vector-icons';

const NotificationScreen = () => {
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchNotifications();
  }, []);

  const fetchNotifications = async () => {
    try {
      const response = await api.get('/notifications');
      setNotifications(response.data.data);
    } catch (error) {
      console.error('Lỗi lấy thông báo:', error);
    } finally {
      setLoading(false);
    }
  };

  const renderItem = ({ item }) => (
    <View style={[styles.card, !item.read_at && styles.unreadCard]}>
      <View style={styles.iconContainer}>
        <MaterialIcons 
          name={item.read_at ? "notifications-none" : "notifications-active"} 
          size={24} 
          color={item.read_at ? "#999" : "#007bff"} 
        />
      </View>
      <View style={styles.content}>
        <Text style={[styles.title, !item.read_at && styles.unreadTitle]}>{item.data.title || 'Thông báo mới'}</Text>
        <Text style={styles.body}>{item.data.message || item.data.content}</Text>
        <Text style={styles.time}>{new Date(item.created_at).toLocaleString()}</Text>
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      {loading ? (
        <ActivityIndicator size="large" color="#007bff" style={{ marginTop: 50 }} />
      ) : (
        <FlatList
          data={notifications}
          renderItem={renderItem}
          keyExtractor={(item) => item.id}
          contentContainerStyle={styles.list}
          ListEmptyComponent={
            <View style={styles.emptyContainer}>
              <MaterialIcons name="notifications-off" size={60} color="#ccc" />
              <Text style={styles.emptyText}>Bạn không có thông báo nào.</Text>
            </View>
          }
        />
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8f9fa',
  },
  list: {
    padding: 10,
  },
  card: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    elevation: 1,
  },
  unreadCard: {
    borderLeftWidth: 4,
    borderLeftColor: '#007bff',
  },
  iconContainer: {
    marginRight: 15,
    justifyContent: 'center',
  },
  content: {
    flex: 1,
  },
  title: {
    fontSize: 16,
    color: '#666',
    marginBottom: 5,
  },
  unreadTitle: {
    fontWeight: 'bold',
    color: '#333',
  },
  body: {
    fontSize: 14,
    color: '#444',
    lineHeight: 20,
  },
  time: {
    fontSize: 12,
    color: '#999',
    marginTop: 8,
  },
  emptyContainer: {
    alignItems: 'center',
    marginTop: 100,
  },
  emptyText: {
    marginTop: 10,
    color: '#999',
    fontSize: 16,
  },
});

export default NotificationScreen;
