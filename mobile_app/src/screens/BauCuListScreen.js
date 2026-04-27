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

const BauCuListScreen = ({ navigation }) => {
  const [votingSessions, setVotingSessions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const fetchVotingSessions = useCallback(async () => {
    try {
      setLoading(true);
      const response = await api.get('/voting');
      if (response.data.success) {
        setVotingSessions(response.data.data);
      }
    } catch (error) {
      console.error('Lỗi khi tải danh sách bầu cử:', error);
    } finally {
      setLoading(false);
    }
  }, []);

  const onRefresh = useCallback(async () => {
    setRefreshing(true);
    await fetchVotingSessions();
    setRefreshing(false);
  }, [fetchVotingSessions]);

  useEffect(() => {
    fetchVotingSessions();
  }, [fetchVotingSessions]);

  const getStatusConfig = (status) => {
    const config = {
      sap_to_chuc: { label: 'Sắp diễn ra', color: Colors.primary, bgColor: Colors.primaryBg },
      dang_dien_ra: { label: 'Đang diễn ra', color: Colors.success, bgColor: Colors.successBg },
      da_ket_thuc: { label: 'Đã kết thúc', color: Colors.textMuted, bgColor: Colors.background },
    };
    return config[status] || { label: 'Không xác định', color: Colors.textMuted, bgColor: Colors.background };
  };

  const renderItem = ({ item }) => {
    const status = getStatusConfig(item.trang_thai_thuc_te);
    
    return (
      <TouchableOpacity
        style={styles.card}
        onPress={() => navigation.navigate('BauCuDetail', { votingId: item.ma_bau_cu })}
        activeOpacity={0.7}
      >
        <View style={styles.cardHeader}>
          <Text style={styles.title} numberOfLines={2}>{item.ten_cuoc_bau_cu}</Text>
          <View style={[styles.badge, { backgroundColor: status.bgColor }]}>
            <Text style={[styles.badgeText, { color: status.color }]}>{status.label}</Text>
          </View>
        </View>
        
        <View style={styles.cardBody}>
          <View style={styles.infoRow}>
            <MaterialIcons name="event" size={16} color={Colors.textMuted} />
            <Text style={styles.infoText}>
              Bắt đầu: {new Date(item.thoi_gian_bat_dau).toLocaleString('vi-VN')}
            </Text>
          </View>
          <View style={styles.infoRow}>
            <MaterialIcons name="people-outline" size={16} color={Colors.textMuted} />
            <Text style={styles.infoText}>
              {item.so_ung_cu_vien} ứng cử viên • {item.so_da_bo_phieu}/{item.so_cu_tri} đã tham gia
            </Text>
          </View>
        </View>
        
        <View style={styles.cardFooter}>
          <Text style={styles.footerLink}>Chi tiết bầu cử</Text>
          <MaterialIcons name="arrow-forward" size={16} color={Colors.primary} />
        </View>
      </TouchableOpacity>
    );
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Bầu cử</Text>
        <Text style={styles.headerSubtitle}>Tham gia đóng góp ý kiến qua các cuộc bầu cử</Text>
      </View>

      {loading && !refreshing ? (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color={Colors.primary} />
        </View>
      ) : (
        <FlatList
          data={votingSessions}
          renderItem={renderItem}
          keyExtractor={(item) => item.ma_bau_cu.toString()}
          contentContainerStyle={styles.list}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={[Colors.primary]} />
          }
          ListEmptyComponent={
            <View style={styles.emptyContainer}>
              <MaterialIcons name="how-to-vote" size={80} color={Colors.border} />
              <Text style={styles.emptyText}>Hiện không có cuộc bầu cử nào</Text>
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
  header: {
    backgroundColor: Colors.white,
    paddingHorizontal: 20,
    paddingTop: 50,
    paddingBottom: 20,
    borderBottomWidth: 1,
    borderBottomColor: Colors.borderLight,
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: '800',
    color: Colors.text,
  },
  headerSubtitle: {
    fontSize: 13,
    color: Colors.textMuted,
    marginTop: 4,
  },
  list: {
    padding: 16,
    paddingBottom: 40,
  },
  card: {
    backgroundColor: Colors.white,
    borderRadius: 16,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: Colors.borderLight,
    elevation: 3,
    shadowColor: Colors.black,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 10,
    overflow: 'hidden',
  },
  cardHeader: {
    padding: 16,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    gap: 12,
  },
  title: {
    fontSize: 16,
    fontWeight: '700',
    color: Colors.text,
    flex: 1,
    lineHeight: 22,
  },
  badge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 6,
  },
  badgeText: {
    fontSize: 11,
    fontWeight: '800',
    textTransform: 'uppercase',
  },
  cardBody: {
    paddingHorizontal: 16,
    paddingBottom: 16,
    gap: 8,
  },
  infoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  infoText: {
    fontSize: 13,
    color: Colors.textMuted,
  },
  cardFooter: {
    padding: 16,
    backgroundColor: Colors.primaryBg,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 6,
  },
  footerLink: {
    fontSize: 13,
    fontWeight: '700',
    color: Colors.primary,
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
  },
});

export default BauCuListScreen;
