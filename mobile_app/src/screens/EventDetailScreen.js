import React, { useCallback, useEffect, useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  Dimensions,
  Image,
  Linking,
  ScrollView,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialIcons } from '@expo/vector-icons';
import api, { BASE_URL } from '../services/api';
import useAuthStore from '../store/authStore';
import Colors from '../constants/Colors';
import RemoteImage, { storageUrl } from '../components/RemoteImage';

const { width } = Dimensions.get('window');

const encodeBase64 = (value) => {
  if (typeof btoa === 'function') {
    return btoa(value);
  }

  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  let output = '';
  let index = 0;

  while (index < value.length) {
    const chr1 = value.charCodeAt(index++);
    const chr2 = value.charCodeAt(index++);
    const chr3 = value.charCodeAt(index++);
    const enc1 = chr1 >> 2;
    const enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
    let enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
    let enc4 = chr3 & 63;

    if (Number.isNaN(chr2)) {
      enc3 = enc4 = 64;
    } else if (Number.isNaN(chr3)) {
      enc4 = 64;
    }

    output += chars.charAt(enc1) + chars.charAt(enc2) + chars.charAt(enc3) + chars.charAt(enc4);
  }

  return output;
};

const stripHtml = (value) => {
  if (!value) return '';
  return String(value)
    .replace(/<br\s*\/?>/gi, '\n')
    .replace(/<\/p>/gi, '\n')
    .replace(/<[^>]+>/g, '')
    .replace(/&nbsp;/g, ' ')
    .replace(/&amp;/g, '&')
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .trim();
};

const formatDateTime = (value) => {
  if (!value) return 'Chưa cập nhật';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return 'Chưa cập nhật';
  return date.toLocaleString('vi-VN', {
    hour: '2-digit',
    minute: '2-digit',
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
};

const formatFileSize = (size = 0) => {
  if (size >= 1048576) return `${(size / 1048576).toFixed(1)} MB`;
  if (size >= 1024) return `${Math.round(size / 1024)} KB`;
  return `${size} B`;
};

const EventDetailScreen = ({ route, navigation }) => {
  const { eventId, event: initialEvent } = route.params || {};
  const resolvedEventId = eventId || initialEvent?.ma_su_kien;
  const { user } = useAuthStore();
  const [event, setEvent] = useState(initialEvent || null);
  const [loading, setLoading] = useState(!initialEvent);
  const [registering, setRegistering] = useState(false);
  const [isRegistered, setIsRegistered] = useState(false);
  const [registration, setRegistration] = useState(null);
  const [timeLeft, setTimeLeft] = useState('');
  const [qrTimestamp, setQrTimestamp] = useState(Date.now());

  const fetchEventDetails = useCallback(async () => {
    if (!resolvedEventId) return;

    try {
      setLoading(true);
      const response = await api.get(`/events/${resolvedEventId}`);
      if (response.data.success) {
        setEvent(response.data.data);
      }
    } catch (error) {
      console.error('Lỗi khi tải chi tiết sự kiện:', error.response?.data || error.message);
    } finally {
      setLoading(false);
    }
  }, [resolvedEventId]);

  const checkRegistrationStatus = useCallback(async () => {
    if (!resolvedEventId) return;

    try {
      const response = await api.get(`/registrations/check/${resolvedEventId}`);
      if (response.data.success) {
        setIsRegistered(Boolean(response.data.data.is_registered));
        setRegistration(response.data.data.registration || null);
      }
    } catch (error) {
      console.error('Lỗi khi kiểm tra trạng thái đăng ký:', error.response?.data || error.message);
    }
  }, [resolvedEventId]);

  const calculateTimeLeft = useCallback(() => {
    if (!event?.thoi_gian_bat_dau) return;

    const now = new Date().getTime();
    const start = new Date(event.thoi_gian_bat_dau).getTime();
    const difference = start - now;

    if (difference > 0) {
      const days = Math.floor(difference / (1000 * 60 * 60 * 24));
      const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((difference % (1000 * 60)) / 1000);
      setTimeLeft(`${days > 0 ? `${days} ngày ` : ''}${hours} giờ ${minutes} phút ${seconds} giây`);
    } else {
      setTimeLeft(null);
    }
  }, [event?.thoi_gian_bat_dau]);

  useEffect(() => {
    fetchEventDetails();
    checkRegistrationStatus();
  }, [fetchEventDetails, checkRegistrationStatus]);

  useEffect(() => {
    if (!event?.thoi_gian_bat_dau) return undefined;

    calculateTimeLeft();
    const timer = setInterval(calculateTimeLeft, 1000);
    return () => clearInterval(timer);
  }, [calculateTimeLeft, event?.thoi_gian_bat_dau]);

  useEffect(() => {
    if (!isRegistered) return undefined;

    const timer = setInterval(() => {
      setQrTimestamp(Date.now());
    }, 20000);

    return () => clearInterval(timer);
  }, [isRegistered]);

  const handleRegister = async () => {
    if (!event?.ma_su_kien) return;

    setRegistering(true);
    try {
      const response = await api.post(`/registrations/${event.ma_su_kien}`);
      if (response.data.success) {
        Alert.alert('Thành công', 'Bạn đã đăng ký tham gia sự kiện thành công.');
        setIsRegistered(true);
        setRegistration(response.data.data || null);
      }
    } catch (error) {
      Alert.alert('Không thành công', error.response?.data?.message || 'Có lỗi xảy ra khi đăng ký.');
    } finally {
      setRegistering(false);
    }
  };

  const handleUnregister = async () => {
    if (!event?.ma_su_kien) return;

    Alert.alert('Hủy đăng ký', 'Bạn có chắc chắn muốn hủy đăng ký sự kiện này?', [
      { text: 'Quay lại', style: 'cancel' },
      {
        text: 'Xác nhận',
        style: 'destructive',
        onPress: async () => {
          setRegistering(true);
          try {
            const response = await api.delete(`/registrations/${event.ma_su_kien}`);
            if (response.data.success) {
              Alert.alert('Thành công', 'Đã hủy đăng ký sự kiện.');
              setIsRegistered(false);
              setRegistration(null);
            }
          } catch (error) {
            Alert.alert('Không thành công', error.response?.data?.message || 'Không thể hủy đăng ký.');
          } finally {
            setRegistering(false);
          }
        },
      },
    ]);
  };

  const renderBanner = (content = {}) => {
    const bannerPath = content.image_path || event?.anh_su_kien;

    return (
      <View style={styles.moduleBox}>
        <RemoteImage
          path={bannerPath}
          style={styles.moduleBanner}
          fallbackIcon="image"
          fallbackText="Chưa cập nhật hình ảnh"
        />
        {content.caption ? <Text style={styles.moduleNote}>{content.caption}</Text> : null}
      </View>
    );
  };

  const renderHeader = (content = {}) => {
    const statusMap = {
      sap_to_chuc: { label: 'Sắp tổ chức', color: Colors.primary },
      dang_dien_ra: { label: 'Đang diễn ra', color: Colors.success },
      da_ket_thuc: { label: 'Đã kết thúc', color: Colors.textMuted },
      huy: { label: 'Đã hủy', color: Colors.danger },
    };
    const status = event?.trang_thai_thuc_te || event?.trang_thai || 'da_ket_thuc';
    const statusConfig = statusMap[status] || statusMap.da_ket_thuc;

    return (
      <View style={styles.moduleBox}>
        <View style={styles.headerBadges}>
          <View style={[styles.badge, { backgroundColor: statusConfig.color }]}>
            <Text style={styles.badgeText}>{statusConfig.label}</Text>
          </View>
          {event?.loai_su_kien && (
            <View style={[styles.badge, styles.categoryBadge]}>
              <Text style={[styles.badgeText, { color: Colors.primary }]}>{event.loai_su_kien.ten_loai}</Text>
            </View>
          )}
          {content.badge ? (
            <View style={[styles.badge, styles.neutralBadge]}>
              <Text style={[styles.badgeText, { color: Colors.textMuted }]}>{content.badge}</Text>
            </View>
          ) : null}
        </View>
        <Text style={styles.eventTitle}>{content.title || event?.ten_su_kien || 'Chi tiết sự kiện'}</Text>
        {content.subtitle ? <Text style={styles.headerSubtitle}>{content.subtitle}</Text> : null}
        {status === 'sap_to_chuc' && timeLeft ? (
          <View style={styles.countdownContainer}>
            <MaterialIcons name="hourglass-empty" size={16} color={Colors.textMuted} />
            <Text style={styles.countdownText}>Bắt đầu sau: {timeLeft}</Text>
          </View>
        ) : null}
      </View>
    );
  };

  const renderInfo = (content = {}) => {
    const items = content.items || ['time', 'location', 'capacity', 'points'];

    return (
      <View style={styles.moduleBox}>
        <View style={styles.moduleTitle}>
          <MaterialIcons name="info-outline" size={18} color={Colors.primary} />
          <Text style={styles.moduleTitleText}>{content.title || 'Thông tin sự kiện'}</Text>
        </View>
        <View style={styles.infoList}>
          {items.includes('time') && (
            <>
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Bắt đầu</Text>
                <Text style={styles.infoValue}>{formatDateTime(event?.thoi_gian_bat_dau)}</Text>
              </View>
              <View style={styles.infoRow}>
                <Text style={styles.infoLabel}>Kết thúc</Text>
                <Text style={styles.infoValue}>{formatDateTime(event?.thoi_gian_ket_thuc)}</Text>
              </View>
            </>
          )}
          {items.includes('location') && (
            <View style={styles.infoRow}>
              <Text style={styles.infoLabel}>Địa điểm</Text>
              <Text style={styles.infoValue}>{event?.dia_diem || 'Chưa cập nhật'}</Text>
            </View>
          )}
          {items.includes('capacity') && (
            <View style={styles.infoRow}>
              <Text style={styles.infoLabel}>Số lượng</Text>
              <Text style={styles.infoValue}>
                {event?.so_luong_hien_tai || 0}/{event?.so_luong_toi_da || 'Không giới hạn'}
              </Text>
            </View>
          )}
          {items.includes('points') && (
            <View style={styles.infoRow}>
              <Text style={styles.infoLabel}>Điểm cộng</Text>
              <Text style={[styles.infoValue, { color: Colors.warning }]}>
                +{event?.diem_cong || 0} điểm rèn luyện
              </Text>
            </View>
          )}
        </View>
        {content.custom_note ? <Text style={[styles.moduleNote, styles.infoNote]}>{content.custom_note}</Text> : null}
      </View>
    );
  };

  const renderDescription = (content = {}) => {
    const body = stripHtml(content.body || event?.mo_ta_chi_tiet || event?.mo_ta);

    return (
      <View style={styles.moduleBox}>
        <View style={styles.moduleTitle}>
          <MaterialIcons name="description" size={18} color={Colors.primary} />
          <Text style={styles.moduleTitleText}>{content.heading || content.title || 'Nội dung chi tiết'}</Text>
        </View>
        <Text style={styles.descriptionText}>
          {body || 'Chưa cập nhật nội dung chi tiết cho sự kiện này.'}
        </Text>
      </View>
    );
  };

  const renderDocuments = (content = {}) => {
    const documents = Array.isArray(content.items) ? content.items : [];
    if (!documents.length) return null;

    return (
      <View style={styles.moduleBox}>
        <View style={styles.moduleTitle}>
          <MaterialIcons name="attachment" size={18} color={Colors.primary} />
          <Text style={styles.moduleTitleText}>{content.title || 'Tài liệu đính kèm'}</Text>
        </View>
        {documents.map((doc, index) => (
          <TouchableOpacity
            key={`${doc.duong_dan_tep || doc.ten_tep}-${index}`}
            style={styles.docLink}
            onPress={() => {
              const url = storageUrl(doc.duong_dan_tep);
              if (url) Linking.openURL(url);
            }}
          >
            <MaterialIcons name="insert-drive-file" size={24} color={Colors.textMuted} />
            <View style={{ flex: 1 }}>
              <Text style={styles.docName}>{doc.ten_tep || 'Tài liệu'}</Text>
              <Text style={styles.docMeta}>{formatFileSize(doc.kich_thuoc)} • {(doc.loai_tep || 'file').toUpperCase()}</Text>
            </View>
            <MaterialIcons name="file-download" size={20} color={Colors.primary} />
          </TouchableOpacity>
        ))}
      </View>
    );
  };

  const renderGallery = (content = {}) => {
    const mediaItems = Array.isArray(event?.media) ? event.media : [];
    const contentImages = Array.isArray(content.images) ? content.images : [];
    const mediaImages = mediaItems
      ?.filter((item) => item.loai_tep === 'hinh_anh')
      ?.map((item) => item.duong_dan_tep) || [];
    const images = (contentImages.length ? contentImages : mediaImages).filter((image) => image && String(image).trim());
    if (!images.length) return null;

    return (
      <View style={styles.moduleBox}>
        <View style={styles.moduleTitle}>
          <MaterialIcons name="collections" size={18} color={Colors.primary} />
          <Text style={styles.moduleTitleText}>{content.title || 'Hình ảnh sự kiện'}</Text>
        </View>
        <View style={styles.galleryGrid}>
          {images.map((imagePath, index) => (
            <View key={`${imagePath}-${index}`} style={styles.galleryItem}>
              <RemoteImage path={imagePath} style={styles.galleryImage} fallbackIcon="image" />
            </View>
          ))}
        </View>
      </View>
    );
  };

  const renderPersonalCode = () => {
    if (!isRegistered || !user?.ma_sinh_vien || !event?.ma_su_kien) return null;

    const qrData = JSON.stringify({
      action: 'student_checkin',
      ma_su_kien: event.ma_su_kien,
      ma_sinh_vien: user.ma_sinh_vien,
      t: qrTimestamp,
    });
    const qrUrl = `${BASE_URL}/api/generate-qr?format=png&base64=1&data=${encodeURIComponent(encodeBase64(qrData))}`;

    return (
      <View style={styles.moduleBox}>
        <View style={styles.moduleTitle}>
          <MaterialIcons name="qr-code-2" size={18} color={Colors.primary} />
          <Text style={styles.moduleTitleText}>Mã điểm danh cá nhân</Text>
        </View>
        <View style={styles.personalQrBox}>
          <Image source={{ uri: qrUrl }} style={styles.personalQrImage} />
          <Text style={styles.personalCodeText}>
            Quản trị viên sẽ quét mã này từ web để điểm danh.
          </Text>
          {registration?.trang_thai_tham_gia ? (
            <Text style={styles.personalCodeText}>Trạng thái: {registration.trang_thai_tham_gia}</Text>
          ) : null}
        </View>
      </View>
    );
  };

  const renderModules = () => {
    let modules = [];

    try {
      modules = typeof event?.bo_cuc === 'string' ? JSON.parse(event.bo_cuc) : (event?.bo_cuc || []);
    } catch (error) {
      modules = [];
    }

    if (!Array.isArray(modules) || modules.length === 0) {
      return (
        <>
          {renderBanner()}
          {renderHeader()}
          {renderInfo()}
          {renderDescription()}
          {renderGallery()}
          {renderPersonalCode()}
        </>
      );
    }

    return (
      <>
        {modules.map((module, index) => {
          const content = module.content || {};
          const titleContent = module.title ? { title: module.title, ...content } : content;

          switch (module.type) {
            case 'banner':
              return <View key={`${module.type}-${index}`}>{renderBanner(content)}</View>;
            case 'header':
              return <View key={`${module.type}-${index}`}>{renderHeader(content)}</View>;
            case 'info':
              return <View key={`${module.type}-${index}`}>{renderInfo(titleContent)}</View>;
            case 'description':
              return <View key={`${module.type}-${index}`}>{renderDescription(titleContent)}</View>;
            case 'gallery':
              return <View key={`${module.type}-${index}`}>{renderGallery(titleContent)}</View>;
            case 'documents':
              return <View key={`${module.type}-${index}`}>{renderDocuments(titleContent)}</View>;
            default:
              return null;
          }
        })}
        {renderPersonalCode()}
      </>
    );
  };

  if (loading) {
    return (
      <SafeAreaView style={styles.loading} edges={['top', 'left', 'right']}>
        <ActivityIndicator size="large" color={Colors.primary} />
      </SafeAreaView>
    );
  }

  if (!event) {
    return (
      <SafeAreaView style={styles.loading} edges={['top', 'left', 'right']}>
        <MaterialIcons name="event-busy" size={56} color={Colors.textMuted} />
        <Text style={styles.emptyText}>Không tìm thấy thông tin sự kiện.</Text>
        <TouchableOpacity style={styles.backButton} onPress={() => navigation.goBack()}>
          <Text style={styles.backButtonText}>Quay lại</Text>
        </TouchableOpacity>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container} edges={['top', 'left', 'right']}>
      <StatusBar barStyle="dark-content" />
      <ScrollView contentContainerStyle={styles.scrollContent}>
        {renderModules()}
      </ScrollView>

      <View style={styles.bottomBar}>
        {!isRegistered ? (
          <TouchableOpacity
            style={[styles.btnRegister, registering && styles.btnDisabled]}
            onPress={handleRegister}
            disabled={registering}
          >
            {registering ? <ActivityIndicator color={Colors.white} /> : <Text style={styles.btnText}>Đăng ký tham gia ngay</Text>}
          </TouchableOpacity>
        ) : (
          <TouchableOpacity
            style={[styles.btnUnregister, registering && styles.btnDisabled]}
            onPress={handleUnregister}
            disabled={registering}
          >
            {registering ? <ActivityIndicator color={Colors.white} /> : <Text style={styles.btnText}>Hủy đăng ký</Text>}
          </TouchableOpacity>
        )}
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.background },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: Colors.background, padding: 24 },
  emptyText: { color: Colors.textMuted, fontSize: 15, fontWeight: '700', marginTop: 12, textAlign: 'center' },
  backButton: { marginTop: 16, backgroundColor: Colors.primary, borderRadius: 8, paddingHorizontal: 18, paddingVertical: 10 },
  backButtonText: { color: Colors.white, fontWeight: '800' },
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
  categoryBadge: { backgroundColor: Colors.primaryBg, borderColor: Colors.primary, borderWidth: 1 },
  neutralBadge: { backgroundColor: Colors.background, borderColor: Colors.border, borderWidth: 1 },
  badgeText: { color: Colors.white, fontSize: 11, fontWeight: '800', textTransform: 'uppercase' },
  headerSubtitle: { color: Colors.textMuted, lineHeight: 20, fontSize: 13 },
  countdownContainer: { flexDirection: 'row', alignItems: 'center', marginTop: 12, gap: 6 },
  countdownText: { color: Colors.textMuted, fontWeight: '700', fontSize: 13 },
  infoList: { gap: 8 },
  infoRow: { borderBottomWidth: 1, borderBottomColor: Colors.borderLight, paddingBottom: 8 },
  infoLabel: { fontSize: 11, color: Colors.textMuted, textTransform: 'uppercase', letterSpacing: 1, marginBottom: 4 },
  infoValue: { fontSize: 15, fontWeight: '700', color: Colors.text, flexShrink: 1 },
  infoNote: { marginTop: 12 },
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
  galleryItem: { width: (width - 64) / 2, aspectRatio: 1 },
  galleryImage: { width: '100%', height: '100%', borderRadius: 6, backgroundColor: Colors.borderLight },
  personalQrBox: { alignItems: 'center', gap: 10 },
  personalQrImage: { width: 148, height: 148, backgroundColor: Colors.white },
  personalCodeText: { color: Colors.textMuted, fontSize: 12, textAlign: 'center' },
  bottomBar: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
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
  btnText: { color: Colors.white, fontWeight: '800', fontSize: 16 },
});

export default EventDetailScreen;
