import React, { useEffect, useState } from 'react';
import { 
  StyleSheet, 
  Text, 
  View, 
  Image, 
  TouchableOpacity, 
  ScrollView, 
  ActivityIndicator, 
  SafeAreaView,
  StatusBar 
} from 'react-native';
import useAuthStore from '../store/authStore';
import api, { BASE_URL } from '../services/api';
import { MaterialIcons } from '@expo/vector-icons';
import Colors from '../constants/Colors';
import Typography from '../constants/Typography';

const ProfileScreen = ({ navigation }) => {
  const { user, logout } = useAuthStore();
  const [points, setPoints] = useState(0);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchPoints();
  }, []);

  const fetchPoints = async () => {
    try {
      const response = await api.get('/points/total');
      setPoints(response.data.data.total_points);
    } catch (error) {
      console.error('Lỗi lấy điểm:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContent}>
        
        {/* Profile Header */}
        <View style={styles.header}>
          <View style={styles.avatarContainer}>
            {user?.avatar ? (
              <Image source={{ uri: `${BASE_URL}/storage/${user.avatar}` }} style={styles.avatar} />
            ) : (
              <View style={styles.avatarPlaceholder}>
                <MaterialIcons name="person" size={60} color={Colors.primary} />
              </View>
            )}
            <TouchableOpacity style={styles.editAvatarBtn}>
              <MaterialIcons name="camera-alt" size={18} color={Colors.white} />
            </TouchableOpacity>
          </View>
          <Text style={[Typography.h2, styles.name]}>{user?.ho_ten || 'Người dùng'}</Text>
          <Text style={[Typography.body, styles.email]}>{user?.email}</Text>
        </View>

        {/* Stats Row */}
        <View style={styles.statsRow}>
          <View style={styles.statCard}>
            <Text style={[Typography.h1, { color: Colors.warning }]}>{loading ? '...' : points}</Text>
            <Text style={[Typography.label, { color: Colors.textMuted }]}>Điểm tích lũy</Text>
          </View>
          <View style={styles.statCard}>
            <Text style={[Typography.h1, { color: Colors.primary }]}>#12</Text>
            <Text style={[Typography.label, { color: Colors.textMuted }]}>Xếp hạng</Text>
          </View>
        </View>

        {/* Personal QR */}
        <View style={styles.section}>
          <Text style={[Typography.h3, styles.sectionTitle]}>Mã cá nhân của bạn</Text>
          <View style={styles.qrContainer}>
            <Image 
              source={{ uri: `${BASE_URL}/api/generate-qr?data=${user?.ma_sinh_vien}` }} 
              style={styles.qrImage}
            />
            <Text style={[Typography.caption, { color: Colors.textMuted, marginTop: 12 }]}>
              Dùng mã này để điểm danh nhanh tại các sự kiện
            </Text>
          </View>
        </View>

        {/* Info Grid */}
        <View style={styles.section}>
          <Text style={[Typography.h3, styles.sectionTitle]}>Thông tin tài khoản</Text>
          <View style={styles.infoCard}>
            <View style={styles.infoItem}>
              <MaterialIcons name="badge" size={20} color={Colors.textMuted} />
              <View style={{ flex: 1 }}>
                <Text style={Typography.label}>Mã sinh viên</Text>
                <Text style={Typography.bodyBold}>{user?.ma_sinh_vien || 'N/A'}</Text>
              </View>
            </View>
            <View style={styles.infoItem}>
              <MaterialIcons name="school" size={20} color={Colors.textMuted} />
              <View style={{ flex: 1 }}>
                <Text style={Typography.label}>Lớp / Khoa</Text>
                <Text style={Typography.bodyBold}>{user?.lop || 'Công nghệ Thông tin'}</Text>
              </View>
            </View>
            <View style={[styles.infoItem, { borderBottomWidth: 0 }]}>
              <MaterialIcons name="phone" size={20} color={Colors.textMuted} />
              <View style={{ flex: 1 }}>
                <Text style={Typography.label}>Số điện thoại</Text>
                <Text style={Typography.bodyBold}>{user?.so_dien_thoai || 'Chưa cập nhật'}</Text>
              </View>
            </View>
          </View>
        </View>

        {/* Actions */}
        <View style={styles.actions}>
          <TouchableOpacity style={styles.actionBtn} onPress={() => navigation.navigate('ParticipationHistory')}>
            <MaterialIcons name="history" size={22} color={Colors.text} />
            <Text style={[Typography.bodySemiBold, { flex: 1, marginLeft: 12 }]}>Lịch sử tham gia</Text>
            <MaterialIcons name="chevron-right" size={24} color={Colors.border} />
          </TouchableOpacity>
          
          <TouchableOpacity style={[styles.actionBtn, { marginTop: 12 }]} onPress={logout}>
            <MaterialIcons name="logout" size={22} color={Colors.danger} />
            <Text style={[Typography.bodySemiBold, { flex: 1, marginLeft: 12, color: Colors.danger }]}>Đăng xuất</Text>
          </TouchableOpacity>
        </View>

        <Text style={styles.versionText}>QL_SU_KIEN • v1.0.0 Premium</Text>
      </ScrollView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.background },
  scrollContent: { paddingBottom: 40 },
  header: { alignItems: 'center', paddingVertical: 40, backgroundColor: Colors.white },
  avatarContainer: { position: 'relative', marginBottom: 16 },
  avatar: { width: 120, height: 120, borderRadius: 60 },
  avatarPlaceholder: { width: 120, height: 120, borderRadius: 60, backgroundColor: Colors.primaryBg, justifyContent: 'center', alignItems: 'center' },
  editAvatarBtn: { position: 'absolute', bottom: 0, right: 4, backgroundColor: Colors.primary, width: 36, height: 36, borderRadius: 18, justifyContent: 'center', alignItems: 'center', borderWidth: 3, borderColor: Colors.white },
  name: { color: Colors.text, marginBottom: 4 },
  email: { color: Colors.textMuted },
  statsRow: { flexDirection: 'row', padding: 20, gap: 16 },
  statCard: { flex: 1, backgroundColor: Colors.white, padding: 20, borderRadius: 12, alignItems: 'center', borderWidth: 1, borderColor: Colors.border },
  section: { paddingHorizontal: 20, marginTop: 24 },
  sectionTitle: { marginBottom: 16 },
  qrContainer: { backgroundColor: Colors.white, padding: 24, borderRadius: 12, alignItems: 'center', borderWidth: 1, borderColor: Colors.border },
  qrImage: { width: 160, height: 160, backgroundColor: '#fff' },
  infoCard: { backgroundColor: Colors.white, borderRadius: 12, borderWidth: 1, borderColor: Colors.border, overflow: 'hidden' },
  infoItem: { flexDirection: 'row', alignItems: 'center', padding: 16, borderBottomWidth: 1, borderBottomColor: Colors.borderLight, gap: 16 },
  actions: { paddingHorizontal: 20, marginTop: 32 },
  actionBtn: { flexDirection: 'row', alignItems: 'center', backgroundColor: Colors.white, padding: 16, borderRadius: 12, borderWidth: 1, borderColor: Colors.border },
  versionText: { textAlign: 'center', marginTop: 40, color: Colors.textMuted, fontSize: 12, fontWeight: '600' },
});

export default ProfileScreen;
