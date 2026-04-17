import React, { useState, useEffect } from 'react';
import {
  StyleSheet,
  Text,
  View,
  ScrollView,
  TouchableOpacity,
  Alert,
  ActivityIndicator,
  Image,
  FlatList,
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import useAuthStore from '../store/authStore';

const EventDetailScreen = ({ route, navigation }) => {
  const { eventId, event: initialEvent } = route.params;
  const [event, setEvent] = useState(initialEvent);
  const [relatedEvents, setRelatedEvents] = useState([]);
  const [registering, setRegistering] = useState(false);
  const [loading, setLoading] = useState(!initialEvent);
  const [isRegistered, setIsRegistered] = useState(false);

  useEffect(() => {
    if (!initialEvent) {
      fetchEventDetails();
    } else {
      checkRegistrationStatus();
    }
  }, [eventId]);

  const fetchEventDetails = async () => {
    try {
      setLoading(true);
      const response = await api.get(`/events/${eventId}`);
      if (response.data.success) {
        setEvent(response.data.data);
        checkRegistrationStatus();
      }
    } catch (error) {
      console.error('Lỗi khi tải chi tiết sự kiện:', error);
      Alert.alert('Lỗi', 'Không thể tải thông tin sự kiện');
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
      // Optional: handle error silently or show message
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
      } else {
        Alert.alert('Thông báo', response.data.message || 'Có lỗi xảy ra');
      }
    } catch (error) {
      const msg = error.response?.data?.message || 'Có lỗi xảy ra khi đăng ký';
      Alert.alert('Lỗi', msg);
    } finally {
      setRegistering(false);
    }
  };

  const handleUnregister = async () => {
    Alert.alert(
      'Hủy đăng ký',
      'Bạn có chắc chắn muốn hủy đăng ký sự kiện này?',
      [
        { text: 'Hủy', onPress: () => {} },
        {
          text: 'Xác nhận',
          onPress: async () => {
            try {
              setRegistering(true);
              const response = await api.delete(`/registrations/${event.ma_su_kien}`);
              if (response.data.success) {
                Alert.alert('Thành công', 'Bạn đã hủy đăng ký sự kiện');
                setIsRegistered(false);
              }
            } catch (error) {
              Alert.alert('Lỗi', 'Không thể hủy đăng ký');
            } finally {
              setRegistering(false);
            }
          },
        },
      ]
    );
  };

  const getStatusConfig = (status) => {
    const config = {
      sap_to_chuc: { label: 'Sắp tổ chức', color: '#007bff' },
      dang_dien_ra: { label: 'Đang diễn ra', color: '#28a745' },
      da_ket_thuc: { label: 'Đã kết thúc', color: '#6c757d' },
    };
    return config[status] || { label: 'Không xác định', color: '#6c757d' };
  };

  const formatDateTime = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
      weekday: 'long',
      hour: '2-digit',
      minute: '2-digit',
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
    });
  };

  const statusConfig = event ? getStatusConfig(event.trang_thai_thuc_te) : {};
  const imageUrl = event?.anh_su_kien ? `http://192.168.1.211:8000/storage/${event.anh_su_kien}` : null;

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#007bff" />
      </View>
    );
  }

  if (!event) {
    return (
      <View style={styles.errorContainer}>
        <MaterialIcons name="error-outline" size={48} color="#dc3545" />
        <Text style={styles.errorText}>Không thể tải sự kiện</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container} showsVerticalScrollIndicator={false}>
      {/* Event Image */}
      <View style={styles.imageContainer}>
        {imageUrl ? (
          <Image
            source={{ uri: imageUrl }}
            style={styles.image}
            resizeMode="cover"
          />
        ) : (
          <View style={[styles.image, styles.placeholderImage]}>
            <MaterialIcons name="calendar-today" size={64} color="#ccc" />
          </View>
        )}
        <View style={[styles.statusBadge, { backgroundColor: statusConfig.color }]}>
          <Text style={styles.statusText}>{statusConfig.label}</Text>
        </View>
      </View>

      {/* Event Content */}
      <View style={styles.content}>
        {/* Title */}
        <Text style={styles.title}>{event.ten_su_kien}</Text>

        {/* Meta Information Grid */}
        <View style={styles.metaGrid}>
          {event.thoi_gian_bat_dau && (
            <View style={styles.metaBox}>
              <MaterialIcons name="access-time" size={20} color="#007bff" />
              <Text style={styles.metaBoxLabel}>Thời gian</Text>
              <Text style={styles.metaBoxText} numberOfLines={2}>
                {formatDateTime(event.thoi_gian_bat_dau)}
              </Text>
            </View>
          )}

          {event.dia_diem && (
            <View style={styles.metaBox}>
              <MaterialIcons name="location-on" size={20} color="#007bff" />
              <Text style={styles.metaBoxLabel}>Địa điểm</Text>
              <Text style={styles.metaBoxText} numberOfLines={2}>
                {event.dia_diem}
              </Text>
            </View>
          )}

          {event.diem_cong > 0 && (
            <View style={styles.metaBox}>
              <MaterialIcons name="star" size={20} color="#ffc107" />
              <Text style={styles.metaBoxLabel}>Điểm thưởng</Text>
              <Text style={styles.metaBoxText}>+{event.diem_cong} điểm</Text>
            </View>
          )}

          <View style={styles.metaBox}>
            <MaterialIcons name="people" size={20} color="#28a745" />
            <Text style={styles.metaBoxLabel}>Người tham gia</Text>
            <Text style={styles.metaBoxText}>
              {event.so_luong_hien_tai}/{event.so_luong_toi_da || '∞'}
            </Text>
          </View>
        </View>

        {/* Info Sections */}
        {event.mo_ta || event.mo_ta_chi_tiet ? (
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Mô tả</Text>
            <Text style={styles.sectionText}>
              {event.mo_ta || event.mo_ta_chi_tiet}
            </Text>
          </View>
        ) : null}

        {event.loaiSuKien && (
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Loại sự kiện</Text>
            <Text style={styles.sectionText}>{event.loaiSuKien.ten_loai}</Text>
          </View>
        )}

        {event.nguoiTao && (
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Người tổ chức</Text>
            <View style={styles.organizerBox}>
              <MaterialIcons name="account-circle" size={32} color="#007bff" />
              <View style={styles.organizerInfo}>
                <Text style={styles.organizerName}>
                  {event.nguoiTao.ho_ten || 'Không rõ'}
                </Text>
                <Text style={styles.organizerEmail}>{event.nguoiTao.email}</Text>
              </View>
            </View>
          </View>
        )}

        {/* Action Buttons */}
        <View style={styles.actionButtons}>
          {!isRegistered ? (
            <TouchableOpacity
              style={[styles.button, styles.registerButton]}
              onPress={handleRegister}
              disabled={registering}
              activeOpacity={0.8}
            >
              {registering ? (
                <ActivityIndicator color="#fff" size="small" />
              ) : (
                <>
                  <MaterialIcons name="check-circle" size={18} color="#fff" />
                  <Text style={styles.buttonText}>Đăng ký tham gia</Text>
                </>
              )}
            </TouchableOpacity>
          ) : (
            <TouchableOpacity
              style={[styles.button, styles.cancelButton]}
              onPress={handleUnregister}
              disabled={registering}
              activeOpacity={0.8}
            >
              {registering ? (
                <ActivityIndicator color="#fff" size="small" />
              ) : (
                <>
                  <MaterialIcons name="close-circle" size={18} color="#fff" />
                  <Text style={styles.buttonText}>Hủy đăng ký</Text>
                </>
              )}
            </TouchableOpacity>
          )}

          <TouchableOpacity
            style={[styles.button, styles.shareButton]}
            activeOpacity={0.8}
          >
            <MaterialIcons name="share" size={18} color="#007bff" />
            <Text style={styles.shareButtonText}>Chia sẻ</Text>
          </TouchableOpacity>
        </View>
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  errorText: {
    fontSize: 16,
    color: '#dc3545',
    marginTop: 12,
  },
  imageContainer: {
    position: 'relative',
    width: '100%',
    height: 250,
    backgroundColor: '#e9ecef',
  },
  image: {
    width: '100%',
    height: '100%',
  },
  placeholderImage: {
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#e9ecef',
  },
  statusBadge: {
    position: 'absolute',
    top: 16,
    right: 16,
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 8,
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.15,
    shadowRadius: 3,
  },
  statusText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: '700',
  },
  content: {
    backgroundColor: '#fff',
    padding: 16,
    marginTop: 8,
    marginHorizontal: 8,
    borderRadius: 12,
    marginBottom: 20,
  },
  title: {
    fontSize: 22,
    fontWeight: '700',
    color: '#212529',
    marginBottom: 16,
    lineHeight: 30,
  },
  metaGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginBottom: 20,
    gap: 12,
  },
  metaBox: {
    flex: 1,
    minWidth: '45%',
    backgroundColor: '#f8f9fa',
    borderRadius: 10,
    padding: 12,
    alignItems: 'center',
  },
  metaBoxLabel: {
    fontSize: 12,
    color: '#6c757d',
    marginTop: 6,
    fontWeight: '500',
  },
  metaBoxText: {
    fontSize: 13,
    fontWeight: '600',
    color: '#212529',
    marginTop: 4,
    textAlign: 'center',
  },
  section: {
    marginBottom: 20,
    paddingBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#e9ecef',
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: '700',
    color: '#212529',
    marginBottom: 10,
  },
  sectionText: {
    fontSize: 14,
    color: '#495057',
    lineHeight: 22,
  },
  organizerBox: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
    padding: 12,
    borderRadius: 10,
    gap: 12,
  },
  organizerInfo: {
    flex: 1,
  },
  organizerName: {
    fontSize: 14,
    fontWeight: '600',
    color: '#212529',
  },
  organizerEmail: {
    fontSize: 12,
    color: '#6c757d',
    marginTop: 4,
  },
  actionButtons: {
    gap: 12,
    marginTop: 20,
  },
  button: {
    paddingVertical: 14,
    borderRadius: 10,
    justifyContent: 'center',
    alignItems: 'center',
    flexDirection: 'row',
    gap: 8,
  },
  registerButton: {
    backgroundColor: '#28a745',
  },
  cancelButton: {
    backgroundColor: '#dc3545',
  },
  shareButton: {
    backgroundColor: '#fff',
    borderWidth: 2,
    borderColor: '#007bff',
  },
  buttonText: {
    fontSize: 15,
    fontWeight: '700',
    color: '#fff',
  },
  shareButtonText: {
    fontSize: 15,
    fontWeight: '700',
    color: '#007bff',
  },
});

export default EventDetailScreen;
