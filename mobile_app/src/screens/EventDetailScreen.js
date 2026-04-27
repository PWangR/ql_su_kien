import React, { useState, useEffect, useCallback } from 'react';
import {
  StyleSheet,
  Text,
  View,
  ScrollView,
  TouchableOpacity,
  Alert,
  ActivityIndicator,
  Image,
  Linking,
  SafeAreaView,
  Dimensions,
  StatusBar
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api, { BASE_URL } from '../services/api';
import useAuthStore from '../store/authStore';
import Colors from '../constants/Colors';
import Typography from '../constants/Typography';

const { width } = Dimensions.get('window');

const EventDetailScreen = ({ route, navigation }) => {
  const { eventId, event: initialEvent } = route.params;
  const [event, setEvent] = useState(initialEvent);
  const [loading, setLoading] = useState(!initialEvent);
  const [registering, setRegistering] = useState(false);
  const [isRegistered, setIsRegistered] = useState(false);
  const [timeLeft, setTimeLeft] = useState('');

  useEffect(() => {
    fetchEventDetails();
    checkRegistrationStatus();
  }, [eventId]);

  useEffect(() => {
    if (event?.thoi_gian_bat_dau) {
      const timer = setInterval(() => {
        calculateTimeLeft();
      }, 1000);
      return () => clearInterval(timer);
    }
  }, [event]);

  const calculateTimeLeft = () => {
    const now = new Date().getTime();
    const start = new Date(event.thoi_gian_bat_dau).getTime();
    const difference = start - now;

    if (difference > 0) {
      const days = Math.floor(difference / (1000 * 60 * 60 * 24));
      const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((difference % (1000 * 60)) / 1000);
      setTimeLeft(`${days > 0 ? days + 'n ' : ''}${hours}h ${minutes}m ${seconds}s`);
    } else {
      setTimeLeft(null);
    }
  };

  const fetchEventDetails = async () => {
    try {
      setLoading(true);
      const response = await api.get(`/events/${eventId}`);
      if (response.data.success) {
        setEvent(response.data.data);
      }
    } catch (error) {
      console.error('Lỗi khi tải chi tiết sự kiện:', error);
    } finally {
      setLoading(false);
    }
  };

  const checkRegistrationStatus = async () => {
    try {
      const response = await api.get(`/registrations/check/${eventId}`);
      if (response.data.success) {
        setIsRegistered(response.data.data.is_registered);
      }
    } catch (error) {
      console.error('Lỗi khi kiểm tra trạng thái đăng ký:', error);
    }
  };

  const handleRegister = async () => {
    setRegistering(true);
    try {
      const response = await api.post(`/registrations/${event.ma_su_kien}`);
      if (response.data.success) {
        Alert.alert('Thành công', 'Bạn đã đăng ký tham gia sự kiện thành công!');
        setIsRegistered(true);
      }
    } catch (error) {
      Alert.alert('Lỗi', error.response?.data?.message || 'Có lỗi xảy ra');
    } finally {
      setRegistering(false);
    }
  };

  const handleUnregister = async () => {
    Alert.alert('Hủy đăng ký', 'Bạn có chắc chắn muốn hủy?', [
      { text: 'Quay lại', style: 'cancel' },
      { text: 'Xác nhận', onPress: async () => {
        setRegistering(true);
        try {
          const response = await api.delete(`/registrations/${event.ma_su_kien}`);
          if (response.data.success) {
            setIsRegistered(false);
          }
        } catch (error) {
          Alert.alert('Lỗi', 'Không thể hủy đăng ký');
        } finally {
          setRegistering(false);
        }
      }}
    ]);
  };

  // Modular Rendering Functions
  const renderBanner = (content) => {
    const bannerPath = content.image_path || event.anh_su_kien;
    return (
      <View style={styles.moduleBox}>
        {bannerPath && (
          <Image 
            source={{ uri: `${BASE_URL}/storage/${bannerPath}` }} 
            style={styles.moduleBanner} 
          />
        )}
        {content.caption && <Text style={styles.moduleNote}>{content.caption}</Text>}
      </View>
    );
  };

  const renderHeader = (content) => {
    const statusMap = {
      sap_to_chuc: { label: 'Sắp tổ chức', color: Colors.primary },
      dang_dien_ra: { label: 'Đang diễn ra', color: Colors.success },
      da_ket_thuc: { label: 'Đã kết thúc', color: Colors.textMuted },
    };
    const status = event.trang_thai_thuc_te;
    const statusConfig = statusMap[status] || statusMap.da_ket_thuc;

    return (
      <View style={styles.moduleBox}>
        <View style={styles.headerBadges}>
          <View style={[styles.badge, { backgroundColor: statusConfig.color }]}>
            <Text style={styles.badgeText}>{statusConfig.label}</Text>
          </View>
          {event.loai_su_kien && (
            <View style={[styles.badge, { backgroundColor: Colors.primaryBg, borderColor: Colors.primary, borderWidth: 1 }]}>
              <Text style={[styles.badgeText, { color: Colors.primary }]}>{event.loai_su_kien.ten_loai}</Text>
            </View>
          )}
        </View>
        <Text style={[Typography.h1, { marginBottom: 12 }]}>{content.title || event.ten_su_kien}</Text>
        {content.subtitle && <Text style={styles.headerSubtitle}>{content.subtitle}</Text>}
        {status === 'sap_to_chuc' && timeLeft && (
          <View style={styles.countdownContainer}>
            <MaterialIcons name="hourglass-empty" size={16} color={Colors.textMuted} />
            <Text style={styles.countdownText}>Bắt đầu sau: {timeLeft}</Text>
          </View>
        )}
      </View>
    );
  };

  const renderInfo = (content) => (
    <View style={styles.moduleBox}>
      <View style={styles.moduleTitle}>
        <MaterialIcons name="info-outline" size={18} color={Colors.primary} />
        <Text style={styles.moduleTitleText}>{content.title || 'Thông tin sự kiện'}</Text>
      </View>
      <View style={styles.infoList}>
        <View style={styles.infoRow}>
          <Text style={styles.infoLabel}>Thời gian</Text>
          <Text style={styles.infoValue}>
            {new Date(event.thoi_gian_bat_dau).toLocaleString('vi-VN')}
          </Text>
        </View>
        <View style={styles.infoRow}>
          <Text style={styles.infoLabel}>Địa điểm</Text>
          <Text style={styles.infoValue}>{event.dia_diem || 'Chưa cập nhật'}</Text>
        </View>
        <View style={styles.infoRow}>
          <Text style={styles.infoLabel}>Điểm cộng</Text>
          <Text style={[styles.infoValue, { color: Colors.warning }]}>+{event.diem_cong} điểm rèn luyện</Text>
        </View>
      </View>
    </View>
  );

  const renderDescription = (content) => (
    <View style={styles.moduleBox}>
      <View style={styles.moduleTitle}>
        <MaterialIcons name="description" size={18} color={Colors.primary} />
        <Text style={styles.moduleTitleText}>{content.heading || 'Nội dung chi tiết'}</Text>
      </View>
      <Text style={styles.descriptionText}>
        {content.body || event.mo_ta_chi_tiet || event.mo_ta}
      </Text>
    </View>
  );

  const renderDocuments = (content) => (
    <View style={styles.moduleBox}>
      <View style={styles.moduleTitle}>
        <MaterialIcons name="attachment" size={18} color={Colors.primary} />
        <Text style={styles.moduleTitleText}>{content.title || 'Tài liệu đính kèm'}</Text>
      </View>
      {content.items?.map((doc, idx) => (
        <TouchableOpacity 
          key={idx} 
          style={styles.docLink}
          onPress={() => Linking.openURL(`${BASE_URL}/storage/${doc.duong_dan_tep}`)}
        >
          <MaterialIcons name="insert-drive-file" size={24} color={Colors.textMuted} />
          <View style={{ flex: 1 }}>
            <Text style={styles.docName}>{doc.ten_tep}</Text>
            <Text style={styles.docMeta}>{Math.round(doc.kich_thuoc / 1024)} KB • {doc.loai_tep?.toUpperCase()}</Text>
          </View>
          <MaterialIcons name="file-download" size={20} color={Colors.primary} />
        </TouchableOpacity>
      ))}
    </View>
  );

  const renderModules = () => {
    const modules = typeof event.bo_cuc === 'string' ? JSON.parse(event.bo_cuc) : (event.bo_cuc || []);
    if (modules.length === 0) {
        // Fallback layout
        return (
            <>
                {renderBanner({})}
                {renderHeader({})}
                {renderInfo({})}
                {renderDescription({})}
            </>
        );
    }
    return modules.map((m, i) => {
      switch(m.type) {
        case 'banner': return <View key={i}>{renderBanner(m.content)}</View>;
        case 'header': return <View key={i}>{renderHeader(m.content)}</View>;
        case 'info': return <View key={i}>{renderInfo(m.content)}</View>;
        case 'description': return <View key={i}>{renderDescription(m.content)}</View>;
        case 'documents': return <View key={i}>{renderDocuments(m.content)}</View>;
        default: return null;
      }
    });
  };

  if (loading) return <View style={styles.loading}><ActivityIndicator size="large" color={Colors.primary} /></View>;

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <ScrollView contentContainerStyle={styles.scrollContent}>
        {renderModules()}
      </ScrollView>

      {/* Register Button */}
      <View style={styles.bottomBar}>
        {!isRegistered ? (
          <TouchableOpacity 
            style={[styles.btnRegister, registering && styles.btnDisabled]} 
            onPress={handleRegister}
            disabled={registering}
          >
            {registering ? <ActivityIndicator color="#fff" /> : <Text style={styles.btnText}>Đăng ký tham gia ngay</Text>}
          </TouchableOpacity>
        ) : (
          <TouchableOpacity 
            style={[styles.btnUnregister, registering && styles.btnDisabled]} 
            onPress={handleUnregister}
            disabled={registering}
          >
            {registering ? <ActivityIndicator color="#fff" /> : <Text style={styles.btnText}>Hủy đăng ký</Text>}
          </TouchableOpacity>
        )}
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.background },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  scrollContent: { padding: 16, paddingBottom: 100 },
  moduleBox: {
    backgroundColor: Colors.white,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: Colors.border,
    padding: 20,
    marginBottom: 16,
    elevation: 1,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
  },
  moduleBanner: { width: '100%', height: 200, borderRadius: 6, marginBottom: 10 },
  moduleNote: { color: Colors.textMuted, fontSize: 13, textAlign: 'center', fontStyle: 'italic' },
  moduleTitle: { flexDirection: 'row', alignItems: 'center', marginBottom: 16, gap: 8 },
  moduleTitleText: { fontSize: 16, fontWeight: '700', color: Colors.text },
  headerBadges: { flexDirection: 'row', gap: 8, marginBottom: 12 },
  badge: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 4 },
  badgeText: { color: '#fff', fontSize: 11, fontWeight: '800', textTransform: 'uppercase' },
  headerSubtitle: { color: Colors.textMuted, lineHeight: 22, fontSize: 14 },
  countdownContainer: { flexDirection: 'row', alignItems: 'center', marginTop: 12, gap: 6 },
  countdownText: { color: Colors.textMuted, fontWeight: '700', fontSize: 13 },
  infoList: { gap: 12 },
  infoRow: { borderBottomWidth: 1, borderBottomColor: Colors.borderLight, paddingBottom: 12 },
  infoLabel: { fontSize: 11, color: Colors.textMuted, textTransform: 'uppercase', letterSpacing: 1, marginBottom: 4 },
  infoValue: { fontSize: 15, fontWeight: '700', color: Colors.text },
  descriptionText: { fontSize: 15, lineHeight: 26, color: Colors.textLight },
  docLink: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 12,
    borderWidth: 1,
    borderColor: Colors.border,
    borderRadius: 8,
    marginBottom: 10,
    gap: 12,
  },
  docName: { fontSize: 14, fontWeight: '600', color: Colors.text },
  docMeta: { fontSize: 11, color: Colors.textMuted },
  bottomBar: {
    position: 'absolute',
    bottom: 0, left: 0, right: 0,
    padding: 16,
    backgroundColor: Colors.white,
    borderTopWidth: 1,
    borderTopColor: Colors.border,
  },
  btnRegister: {
    backgroundColor: Colors.primary,
    paddingVertical: 16,
    borderRadius: 8,
    alignItems: 'center',
  },
  btnUnregister: {
    backgroundColor: Colors.danger,
    paddingVertical: 16,
    borderRadius: 8,
    alignItems: 'center',
  },
  btnDisabled: { opacity: 0.6 },
  btnText: { color: '#fff', fontWeight: '800', fontSize: 16 },
});

export default EventDetailScreen;
