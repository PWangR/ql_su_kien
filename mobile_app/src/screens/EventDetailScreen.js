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
  StatusBar,
  Clipboard
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api, { BASE_URL } from '../services/api';
import useAuthStore from '../store/authStore';
import Colors from '../constants/Colors';

const { width } = Dimensions.get('window');

const EventDetailScreen = ({ route, navigation }) => {
  const { eventId, event: initialEvent } = route.params;
  const { user } = useAuthStore();
  const [event, setEvent] = useState(initialEvent);
  const [loading, setLoading] = useState(!initialEvent);
  const [registering, setRegistering] = useState(false);
  const [isRegistered, setIsRegistered] = useState(false);
  const [registration, setRegistration] = useState(null);
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
        setRegistration(response.data.data.registration || null);
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
        setRegistration(response.data.data || null);
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
            setRegistration(null);
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
        {bannerPath && bannerPath.trim() ? (
          <Image 
            source={{ uri: `${BASE_URL}/storage/${bannerPath}` }} 
            style={styles.moduleBanner}
            onError={(e) => console.error('Banner image load failed:', e)}
          />
        ) : (
          <View style={[styles.moduleBanner, { backgroundColor: Colors.borderLight, justifyContent: 'center', alignItems: 'center' }]}>
            <MaterialIcons name="image" size={40} color={Colors.textMuted} />
            <Text style={{ color: Colors.textMuted, marginTop: 8, fontSize: 13 }}>Chưa cập nhật hình ảnh</Text>
          </View>
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
    const status = event.trang_thai_thuc_te || event.trang_thai;
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
        <Text style={styles.eventTitle}>{content.title || event.ten_su_kien}</Text>
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

  const renderGallery = (content = {}) => {
    const images = (content.images || []).filter(img => img && img.trim());
    if (!images.length) return null;

    return (
      <View style={styles.moduleBox}>
        <View style={styles.moduleTitle}>
          <MaterialIcons name="collections" size={18} color={Colors.primary} />
          <Text style={styles.moduleTitleText}>{content.title || 'Hình ảnh sự kiện'}</Text>
        </View>
        <View style={styles.galleryGrid}>
          {images.map((imagePath, index) => (
            <View key={`${imagePath}-${index}`} style={{ width: (width - 64) / 2, aspectRatio: 1 }}>
              <Image
                source={{ uri: `${BASE_URL}/storage/${imagePath}` }}
                style={styles.galleryImage}
                onError={(e) => console.error(`Gallery image ${index} failed:`, e)}
              />
            </View>
          ))}
        </View>
      </View>
    );
  };

  const renderPersonalCode = () => {
    if (!isRegistered || !registration?.ma_dang_ky || !user?.ma_sinh_vien) return null;

    const code = JSON.stringify({
      action: 'student_checkin',
      ma_su_kien: event.ma_su_kien,
      ma_dang_ky: registration.ma_dang_ky,
      ma_sinh_vien: user.ma_sinh_vien,
      loai_diem_danh: registration?.da_diem_danh_dau_buoi ? 'cuoi_buoi' : 'dau_buoi',
    });

    const codeString = String(code);

    return (
      <View style={styles.moduleBox}>
        <View style={styles.moduleTitle}>
          <MaterialIcons name="qr-code-2" size={18} color={Colors.primary} />
          <Text style={styles.moduleTitleText}>Mã điểm danh cá nhân</Text>
        </View>
        <View style={styles.personalQrBox}>
          <View style={styles.qrCodeContainer}>
            <MaterialIcons name="qr-code-2" size={80} color={Colors.primary} style={{ opacity: 0.7 }} />
            <Text style={{ fontSize: 11, color: Colors.textMuted, marginTop: 8 }}>QR được tạo từ server</Text>
          </View>
          <TouchableOpacity 
            style={styles.copyButton}
            onPress={() => {
              Clipboard.setString(codeString);
              Alert.alert('Thành công', 'Đã sao chép mã vào clipboard');
            }}
          >
            <MaterialIcons name="content-copy" size={16} color={Colors.primary} />
            <Text style={styles.copyButtonText}>Sao chép mã</Text>
          </TouchableOpacity>
          <View style={styles.codeTextBox}>
            <Text style={styles.personalCodeText} numberOfLines={4} selectable>
              {codeString}
            </Text>
          </View>
          <Text style={{ fontSize: 11, color: Colors.textMuted, marginTop: 8, textAlign: 'center' }}>
            Quản trị viên sẽ quét mã này từ web để điểm danh
          </Text>
        </View>
      </View>
    );
  };

  const renderModules = () => {
    let modules = [];
    try {
      modules = typeof event.bo_cuc === 'string' ? JSON.parse(event.bo_cuc) : (event.bo_cuc || []);
    } catch (error) {
      modules = [];
    }

    if (modules.length === 0) {
        // Fallback layout
        return (
            <>
                {renderBanner({})}
                {renderHeader({})}
                {renderInfo({})}
                {renderDescription({})}
                {renderPersonalCode()}
            </>
        );
    }
    return (
      <>
        {modules.map((m, i) => {
          switch(m.type) {
            case 'banner': return <View key={i}>{renderBanner(m.content)}</View>;
            case 'header': return <View key={i}>{renderHeader(m.content)}</View>;
            case 'info': return <View key={i}>{renderInfo(m.content)}</View>;
            case 'description': return <View key={i}>{renderDescription(m.content)}</View>;
            case 'gallery': return <View key={i}>{renderGallery(m.content)}</View>;
            case 'documents': return <View key={i}>{renderDocuments(m.content)}</View>;
            default: return null;
          }
        })}
        {renderPersonalCode()}
      </>
    );
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
  scrollContent: { padding: 12, paddingBottom: 88 },
  moduleBox: {
    backgroundColor: Colors.white,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: Colors.border,
    padding: 14,
    marginBottom: 10,
    elevation: 1,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
  },
  moduleBanner: { width: '100%', aspectRatio: 16 / 8.5, borderRadius: 6, marginBottom: 8 },
  moduleNote: { color: Colors.textMuted, fontSize: 13, textAlign: 'center', fontStyle: 'italic' },
  moduleTitle: { flexDirection: 'row', alignItems: 'center', marginBottom: 10, gap: 8 },
  moduleTitleText: { fontSize: 16, fontWeight: '700', color: Colors.text },
  eventTitle: { fontSize: 22, lineHeight: 28, fontWeight: '800', color: Colors.text, marginBottom: 8 },
  headerBadges: { flexDirection: 'row', flexWrap: 'wrap', gap: 8, marginBottom: 12 },
  badge: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 4 },
  badgeText: { color: '#fff', fontSize: 11, fontWeight: '800', textTransform: 'uppercase' },
  headerSubtitle: { color: Colors.textMuted, lineHeight: 20, fontSize: 13 },
  countdownContainer: { flexDirection: 'row', alignItems: 'center', marginTop: 12, gap: 6 },
  countdownText: { color: Colors.textMuted, fontWeight: '700', fontSize: 13 },
  infoList: { gap: 8 },
  infoRow: { borderBottomWidth: 1, borderBottomColor: Colors.borderLight, paddingBottom: 8 },
  infoLabel: { fontSize: 11, color: Colors.textMuted, textTransform: 'uppercase', letterSpacing: 1, marginBottom: 4 },
  infoValue: { fontSize: 15, fontWeight: '700', color: Colors.text, flexShrink: 1 },
  descriptionText: { fontSize: 14, lineHeight: 22, color: Colors.textLight },
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
  galleryGrid: { flexDirection: 'row', flexWrap: 'wrap', gap: 8 },
  galleryImage: { width: (width - 64) / 2, aspectRatio: 1, borderRadius: 6, backgroundColor: Colors.borderLight },
  personalQrBox: { alignItems: 'center', gap: 10 },
  personalQrImage: { width: 148, height: 148, backgroundColor: Colors.white },
  personalCodeText: { color: Colors.textMuted, fontSize: 12, textAlign: 'center' },
  bottomBar: {
    position: 'absolute',
    bottom: 0, left: 0, right: 0,
    padding: 12,
    backgroundColor: Colors.white,
    borderTopWidth: 1,
    borderTopColor: Colors.border,
  },
  btnRegister: {
    backgroundColor: Colors.primary,
    paddingVertical: 12,
    borderRadius: 8,
    alignItems: 'center',
  },
  btnUnregister: {
    backgroundColor: Colors.danger,
    paddingVertical: 12,
    borderRadius: 8,
    alignItems: 'center',
  },
  btnDisabled: { opacity: 0.6 },
  btnText: { color: '#fff', fontWeight: '800', fontSize: 16 },
  qrCodeContainer: { alignItems: 'center', justifyContent: 'center', marginVertical: 16, padding: 12, backgroundColor: '#f5f5f5', borderRadius: 8 },
  copyButton: { flexDirection: 'row', alignItems: 'center', paddingHorizontal: 16, paddingVertical: 10, backgroundColor: Colors.primaryBg, borderRadius: 6, gap: 8, justifyContent: 'center' },
  copyButtonText: { color: Colors.primary, fontWeight: '700', fontSize: 13 },
  codeTextBox: { padding: 12, backgroundColor: Colors.borderLight, borderRadius: 6, marginTop: 10 },
});

export default EventDetailScreen;
