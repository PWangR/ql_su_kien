import React, { useEffect, useState, useCallback } from 'react';
import {
  StyleSheet,
  View,
  FlatList,
  ActivityIndicator,
  Text,
  RefreshControl,
  TouchableOpacity,
  SafeAreaView,
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import Colors from '../constants/Colors';

const ParticipationHistoryScreen = ({ navigation }) => {
  const [history, setHistory] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [pagination, setPagination] = useState({
    current_page: 1,
    total: 0,
  });

  const fetchHistory = useCallback(async (page = 1) => {
    try {
      if (page === 1) setLoading(true);
      const response = await api.get(`/registrations/history?page=${page}`);
      if (response.data.success) {
        if (page === 1) {
          setHistory(response.data.data);
        } else {
          setHistory(prev => [...prev, ...response.data.data]);
        }
        setPagination(response.data.pagination);
      }
    } catch (error) {
      console.error('Lỗi khi tải lịch sử tham gia:', error);
    } finally {
      setLoading(false);
    }
  }, []);

  const onRefresh = useCallback(async () => {
    setRefreshing(true);
    await fetchHistory(1);
    setRefreshing(false);
  }, [fetchHistory]);

  const handleLoadMore = () => {
    if (pagination.current_page * 20 < pagination.total) {
      fetchHistory(pagination.current_page + 1);
    }
  };

  useEffect(() => {
    fetchHistory();
  }, [fetchHistory]);

  const getStatusConfig = (status) => {
    const config = {
      da_dang_ky: { label: 'Đã đăng ký', color: Colors.primary, bgColor: Colors.primaryBg },
      da_tham_gia: { label: 'Đã tham gia', color: Colors.success, bgColor: Colors.successBg },
      vang: { label: 'Vắng mặt', color: Colors.danger, bgColor: Colors.dangerBg },
    };
    return config[status] || { label: status, color: Colors.textMuted, bgColor: Colors.background };
  };

  const renderItem = ({ item }) => {
    const status = getStatusConfig(item.trang_thai_tham_gia);
    const event = item.su_kien || {};
    
    return (
      <TouchableOpacity
        style={styles.card}
        onPress={() => navigation.navigate('EventDetail', { eventId: event.ma_su_kien, event })}
        activeOpacity={0.7}
      >
        <View style={styles.cardContent}>
          <View style={styles.leftContent}>
            <Text style={styles.eventTitle} numberOfLines={2}>{event.ten_su_kien}</Text>
            <View style={styles.infoRow}>
              <MaterialIcons name="calendar-today" size={14} color={Colors.textMuted} />
              <Text style={styles.infoText}>{new Date(item.thoi_gian_dang_ky).toLocaleDateString('vi-VN')}</Text>
            </View>
            {item.trang_thai_tham_gia === 'da_tham_gia' && event.diem_cong > 0 && (
              <View style={styles.pointsBadge}>
                <MaterialIcons name="stars" size={14} color={Colors.warning} />
                <Text style={styles.pointsText}>+{event.diem_cong} điểm</Text>
              </View>
            )}
          </View>
          <View style={[styles.statusBadge, { backgroundColor: status.bgColor }]}>
            <Text style={[styles.statusText, { color: status.color }]}>{status.label}</Text>
          </View>
        </View>
      </TouchableOpacity>
    );
  };

  return (
    <SafeAreaView style={styles.container}>
      {loading && !refreshing ? (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color={Colors.primary} />
        </View>
      ) : (
        <FlatList
          data={history}
          renderItem={renderItem}
          keyExtractor={(item) => item.id.toString()}
          contentContainerStyle={styles.list}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={[Colors.primary]} />
          }
          onEndReached={handleLoadMore}
          onEndReachedThreshold={0.5}
          ListEmptyComponent={
            <View style={styles.emptyContainer}>
              <MaterialIcons name="history" size={80} color={Colors.border} />
              <Text style={styles.emptyText}>Bạn chưa tham gia sự kiện nào</Text>
              <TouchableOpacity 
                style={styles.exploreBtn}
                onPress={() => navigation.navigate('EventsList')}
              >
                <Text style={styles.exploreBtnText}>Khám phá ngay</Text>
              </TouchableOpacity>
            </View>
          }
        />
      )}
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.background,
  },
  list: {
    padding: 16,
    paddingBottom: 40,
  },
  card: {
    backgroundColor: Colors.white,
    borderRadius: 16,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: Colors.borderLight,
    elevation: 2,
    shadowColor: Colors.black,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
  },
  cardContent: {
    padding: 16,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
  },
  leftContent: {
    flex: 1,
    paddingRight: 12,
  },
  eventTitle: {
    fontSize: 15,
    fontWeight: '700',
    color: Colors.text,
    marginBottom: 8,
    lineHeight: 20,
  },
  infoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    marginBottom: 8,
  },
  infoText: {
    fontSize: 12,
    color: Colors.textMuted,
    fontWeight: '500',
  },
  pointsBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#FFF9E6',
    alignSelf: 'flex-start',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
    gap: 4,
  },
  pointsText: {
    fontSize: 11,
    fontWeight: '700',
    color: '#B28900',
  },
  statusBadge: {
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 8,
    minWidth: 90,
    alignItems: 'center',
  },
  statusText: {
    fontSize: 11,
    fontWeight: '800',
    textTransform: 'uppercase',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyContainer: {
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 120,
    paddingHorizontal: 40,
  },
  emptyText: {
    marginTop: 16,
    color: Colors.textMuted,
    fontSize: 16,
    fontWeight: '600',
    textAlign: 'center',
    marginBottom: 24,
  },
  exploreBtn: {
    backgroundColor: Colors.primary,
    paddingHorizontal: 24,
    paddingVertical: 12,
    borderRadius: 12,
  },
  exploreBtnText: {
    color: Colors.white,
    fontSize: 14,
    fontWeight: '700',
  },
});

export default ParticipationHistoryScreen;
