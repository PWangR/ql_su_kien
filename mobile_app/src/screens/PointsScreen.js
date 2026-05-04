import React, { useCallback, useEffect, useState } from 'react';
import {
  ActivityIndicator,
  FlatList,
  RefreshControl,
  SafeAreaView,
  StyleSheet,
  Text,
  View,
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import Colors from '../constants/Colors';

const PointsScreen = () => {
  const [total, setTotal] = useState(0);
  const [history, setHistory] = useState([]);
  const [leaderboard, setLeaderboard] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const loadData = useCallback(async () => {
    try {
      const [totalRes, historyRes, leaderboardRes] = await Promise.all([
        api.get('/points/total'),
        api.get('/points/history'),
        api.get('/points/leaderboard?limit=10'),
      ]);
      setTotal(totalRes.data.data?.total_points || 0);
      setHistory(historyRes.data.data || []);
      setLeaderboard(leaderboardRes.data.data || []);
    } catch (error) {
      console.error('Point screen error:', error.response?.data || error.message);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  useEffect(() => {
    loadData();
  }, [loadData]);

  const onRefresh = () => {
    setRefreshing(true);
    loadData();
  };

  const renderHistory = ({ item }) => {
    const eventName = item.dang_ky?.su_kien?.ten_su_kien;
    return (
      <View style={styles.historyItem}>
        <View style={[styles.pointIcon, item.diem >= 0 ? styles.pointPlus : styles.pointMinus]}>
          <MaterialIcons name={item.diem >= 0 ? 'add' : 'remove'} size={18} color={Colors.white} />
        </View>
        <View style={{ flex: 1 }}>
          <Text style={styles.historyTitle}>{eventName || item.mo_ta || item.nguon}</Text>
          <Text style={styles.historyMeta}>{new Date(item.thoi_gian_ghi_nhan || item.created_at).toLocaleString('vi-VN')}</Text>
        </View>
        <Text style={[styles.historyPoints, item.diem >= 0 ? styles.plusText : styles.minusText]}>
          {item.diem >= 0 ? '+' : ''}{item.diem}
        </Text>
      </View>
    );
  };

  if (loading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color={Colors.primary} />
      </View>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <FlatList
        data={history}
        keyExtractor={(item) => item.ma_lich_su_diem?.toString() || item.id?.toString()}
        renderItem={renderHistory}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={[Colors.primary]} />}
        contentContainerStyle={styles.content}
        ListHeaderComponent={
          <>
            <View style={styles.totalCard}>
              <Text style={styles.totalLabel}>Tong diem tich luy</Text>
              <Text style={styles.totalValue}>{total}</Text>
            </View>

            <View style={styles.section}>
              <Text style={styles.sectionTitle}>Bang xep hang</Text>
              {leaderboard.map((student, index) => (
                <View key={student.ma_sinh_vien} style={styles.rankItem}>
                  <Text style={styles.rankNo}>#{index + 1}</Text>
                  <View style={{ flex: 1 }}>
                    <Text style={styles.rankName}>{student.ho_ten}</Text>
                    <Text style={styles.rankMeta}>{student.ma_sinh_vien}</Text>
                  </View>
                  <Text style={styles.rankPoints}>{student.total_points || 0}</Text>
                </View>
              ))}
            </View>

            <Text style={styles.sectionTitle}>Lich su diem</Text>
          </>
        }
        ListEmptyComponent={
          <View style={styles.empty}>
            <MaterialIcons name="stars" size={64} color={Colors.border} />
            <Text style={styles.emptyText}>Chua co lich su diem.</Text>
          </View>
        }
      />
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.background },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  content: { padding: 16, paddingBottom: 40 },
  totalCard: { backgroundColor: Colors.primary, borderRadius: 16, padding: 24, marginBottom: 20 },
  totalLabel: { color: 'rgba(255,255,255,0.8)', fontSize: 14, fontWeight: '700' },
  totalValue: { color: Colors.white, fontSize: 44, fontWeight: '900', marginTop: 8 },
  section: { backgroundColor: Colors.white, borderRadius: 12, borderColor: Colors.border, borderWidth: 1, padding: 12, marginBottom: 22 },
  sectionTitle: { fontSize: 16, fontWeight: '900', color: Colors.text, marginBottom: 12 },
  rankItem: { flexDirection: 'row', alignItems: 'center', gap: 12, paddingVertical: 10, borderBottomWidth: 1, borderBottomColor: Colors.borderLight },
  rankNo: { width: 34, color: Colors.primary, fontWeight: '900' },
  rankName: { color: Colors.text, fontWeight: '800' },
  rankMeta: { color: Colors.textMuted, fontSize: 12, marginTop: 2 },
  rankPoints: { color: Colors.warning, fontWeight: '900' },
  historyItem: { flexDirection: 'row', alignItems: 'center', gap: 12, backgroundColor: Colors.white, padding: 14, borderRadius: 12, borderColor: Colors.border, borderWidth: 1, marginBottom: 10 },
  pointIcon: { width: 34, height: 34, borderRadius: 17, alignItems: 'center', justifyContent: 'center' },
  pointPlus: { backgroundColor: Colors.success },
  pointMinus: { backgroundColor: Colors.danger },
  historyTitle: { color: Colors.text, fontWeight: '800' },
  historyMeta: { color: Colors.textMuted, fontSize: 12, marginTop: 4 },
  historyPoints: { fontSize: 16, fontWeight: '900' },
  plusText: { color: Colors.success },
  minusText: { color: Colors.danger },
  empty: { alignItems: 'center', padding: 40 },
  emptyText: { color: Colors.textMuted, marginTop: 10, fontWeight: '700' },
});

export default PointsScreen;
