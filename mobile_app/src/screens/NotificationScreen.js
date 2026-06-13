import React, { useCallback, useEffect, useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  FlatList,
  RefreshControl,
  SafeAreaView,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
  Platform,
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import Colors from '../constants/Colors';
import Typography from '../constants/Typography';

const NotificationScreen = ({ navigation }) => {
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const fetchNotifications = useCallback(async () => {
    try {
      const response = await api.get('/notifications');
      if (response.data.success) {
        setNotifications(response.data.data || []);
      }
    } catch (error) {
      console.error('Notification error:', error.response?.data || error.message);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  useEffect(() => {
    fetchNotifications();
  }, [fetchNotifications]);

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    fetchNotifications();
  }, [fetchNotifications]);

  const markAsRead = useCallback(async (item) => {
    if (item.da_doc) return;
    try {
      await api.post(`/notifications/${item.ma_thong_bao}/read`);
      setNotifications((current) =>
        current.map((noti) =>
          noti.ma_thong_bao === item.ma_thong_bao ? { ...noti, da_doc: true } : noti
        )
      );
    } catch (error) {
      Alert.alert('Không thành công', 'Không thể đánh dấu thông báo.');
    }
  }, []);

  const markAllAsRead = useCallback(async () => {
    try {
      await api.post('/notifications/read-all');
      setNotifications((current) => current.map((item) => ({ ...item, da_doc: true })));
    } catch (error) {
      Alert.alert('Không thành công', 'Không thể đánh dấu tất cả thông báo.');
    }
  }, []);

  const deleteNotification = useCallback(async (item) => {
    try {
      await api.delete(`/notifications/${item.ma_thong_bao}`);
      setNotifications((current) => current.filter((noti) => noti.ma_thong_bao !== item.ma_thong_bao));
    } catch (error) {
      Alert.alert('Không thành công', 'Không thể xóa thông báo.');
    }
  }, []);

  const renderItem = useCallback(({ item }) => (
    <TouchableOpacity
      style={[styles.card, !item.da_doc && styles.unreadCard]}
      activeOpacity={0.8}
      onPress={() => markAsRead(item)}
    >
      <View style={[styles.iconContainer, !item.da_doc && styles.unreadIconContainer]}>
        <MaterialIcons
          name={item.da_doc ? 'notifications-none' : 'notifications-active'}
          size={22}
          color={item.da_doc ? Colors.textMuted : Colors.primary}
        />
      </View>
      <View style={styles.content}>
        <View style={styles.contentHeader}>
          <Text style={[Typography.bodyBold, !item.da_doc && { color: Colors.primary }]} numberOfLines={2}>
            {item.tieu_de || 'Thông báo'}
          </Text>
          {!item.da_doc && <View style={styles.unreadDot} />}
        </View>
        <Text style={[Typography.body, { color: Colors.textLight }]} numberOfLines={3}>
          {item.noi_dung}
        </Text>
        <View style={styles.itemFooter}>
          <Text style={[Typography.caption, { color: Colors.textMuted }]}>
            {new Date(item.created_at).toLocaleString('vi-VN')}
          </Text>
          <TouchableOpacity onPress={() => deleteNotification(item)} hitSlop={10}>
            <MaterialIcons name="delete-outline" size={20} color={Colors.danger} />
          </TouchableOpacity>
        </View>
      </View>
    </TouchableOpacity>
  ), [deleteNotification, markAsRead]);

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <View style={styles.header}>
        <TouchableOpacity onPress={() => navigation.goBack()}>
          <MaterialIcons name="arrow-back" size={24} color={Colors.text} />
        </TouchableOpacity>
        <Text style={Typography.h3}>Thông báo</Text>
        <TouchableOpacity onPress={markAllAsRead}>
          <MaterialIcons name="done-all" size={24} color={Colors.primary} />
        </TouchableOpacity>
      </View>

      {loading ? (
        <View style={styles.loading}>
          <ActivityIndicator size="large" color={Colors.primary} />
        </View>
      ) : (
        <FlatList
          data={notifications}
          renderItem={renderItem}
          keyExtractor={(item) => item.ma_thong_bao.toString()}
          contentContainerStyle={styles.list}
          refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={[Colors.primary]} />}
          removeClippedSubviews={Platform.OS === 'android'}
          initialNumToRender={8}
          maxToRenderPerBatch={8}
          windowSize={7}
          updateCellsBatchingPeriod={60}
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
    borderBottomColor: Colors.border,
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
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: Colors.background,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 16,
  },
  unreadIconContainer: { backgroundColor: Colors.white },
  content: { flex: 1 },
  contentHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 4, gap: 8 },
  unreadDot: { width: 8, height: 8, borderRadius: 4, backgroundColor: Colors.primary },
  itemFooter: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: 10 },
  emptyContainer: { alignItems: 'center', justifyContent: 'center', marginTop: 100 },
});

export default NotificationScreen;
