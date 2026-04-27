import React, { useEffect, useState } from 'react';
import { 
  StyleSheet, 
  Text, 
  View, 
  FlatList, 
  ActivityIndicator, 
  TouchableOpacity, 
  SafeAreaView,
  StatusBar 
} from 'react-native';
import api from '../services/api';
import { MaterialIcons } from '@expo/vector-icons';
import Colors from '../constants/Colors';
import Typography from '../constants/Typography';

const NotificationScreen = ({ navigation }) => {
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
    <TouchableOpacity 
      style={[styles.card, !item.read_at && styles.unreadCard]}
      activeOpacity={0.7}
    >
      <View style={[styles.iconContainer, !item.read_at && styles.unreadIconContainer]}>
        <MaterialIcons 
          name={item.read_at ? "notifications-none" : "notifications-active"} 
          size={22} 
          color={item.read_at ? Colors.textMuted : Colors.primary} 
        />
      </View>
      <View style={styles.content}>
        <View style={styles.contentHeader}>
          <Text style={[Typography.bodyBold, !item.read_at && { color: Colors.primary }]}>
            {item.data.title || 'Thông báo mới'}
          </Text>
          {!item.read_at && <View style={styles.unreadDot} />}
        </View>
        <Text style={[Typography.body, { color: Colors.textLight }]} numberOfLines={3}>
          {item.data.message || item.data.content}
        </Text>
        <Text style={[Typography.caption, { color: Colors.textMuted, marginTop: 8 }]}>
          {new Date(item.created_at).toLocaleString('vi-VN')}
        </Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <View style={styles.header}>
        <TouchableOpacity onPress={() => navigation.goBack()}>
          <MaterialIcons name="arrow-back" size={24} color={Colors.text} />
        </TouchableOpacity>
        <Text style={Typography.h3}>Thông báo</Text>
        <MaterialIcons name="done-all" size={24} color={Colors.primary} />
      </View>

      {loading ? (
        <View style={styles.loading}>
          <ActivityIndicator size="large" color={Colors.primary} />
        </View>
      ) : (
        <FlatList
          data={notifications}
          renderItem={renderItem}
          keyExtractor={(item) => item.id}
          contentContainerStyle={styles.list}
          ListEmptyComponent={
            <View style={styles.emptyContainer}>
              <MaterialIcons name="notifications-off" size={64} color={Colors.border} />
              <Text style={[Typography.bodySemiBold, { color: Colors.textMuted, marginTop: 16 }]}>
                Bạn không có thông báo nào.
              </Text>
            </View>
          }
        />
      )}
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.background },
  header: { 
    flexDirection: 'row', 
    alignItems: 'center', 
    justifyContent: 'space-between',
    padding: 16, 
    backgroundColor: Colors.white, 
    borderBottomWidth: 1, 
    borderBottomColor: Colors.border 
  },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  list: { padding: 16 },
  card: {
    flexDirection: 'row',
    backgroundColor: Colors.white,
    padding: 16,
    borderRadius: 12,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: Colors.border,
  },
  unreadCard: { backgroundColor: Colors.primaryBg, borderColor: Colors.primary },
  iconContainer: {
    width: 40, height: 40, borderRadius: 20,
    backgroundColor: Colors.background,
    justifyContent: 'center', alignItems: 'center',
    marginRight: 16
  },
  unreadIconContainer: { backgroundColor: Colors.white },
  content: { flex: 1 },
  contentHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 4 },
  unreadDot: { width: 8, height: 8, borderRadius: 4, backgroundColor: Colors.primary },
  emptyContainer: { alignItems: 'center', justifyContent: 'center', marginTop: 100 },
});

export default NotificationScreen;
