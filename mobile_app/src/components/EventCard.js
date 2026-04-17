import React from 'react';
import { StyleSheet, Text, View, TouchableOpacity, Image } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';

const EventCard = ({ event, onPress }) => {
  // Determine status badge color and label
  const getStatusConfig = (status) => {
    const config = {
      sap_to_chuc: { label: 'Sắp tổ chức', color: '#007bff' },
      dang_dien_ra: { label: 'Đang diễn ra', color: '#28a745' },
      da_ket_thuc: { label: 'Đã kết thúc', color: '#6c757d' },
    };
    return config[status] || { label: 'Không xác định', color: '#6c757d' };
  };

  const statusConfig = getStatusConfig(event.trang_thai_thuc_te);
  const imageUrl = event.anh_su_kien ? `http://192.168.1.211:8000/storage/${event.anh_su_kien}` : null;

  const formatDateTime = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
      hour: '2-digit',
      minute: '2-digit',
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
    });
  };

  const truncateText = (text, maxLength = 50) => {
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
  };

  return (
    <TouchableOpacity style={styles.card} onPress={onPress} activeOpacity={0.7}>
      {/* Image Container */}
      <View style={styles.imageContainer}>
        {imageUrl ? (
          <Image
            source={{ uri: imageUrl }}
            style={styles.image}
            resizeMode="cover"
          />
        ) : (
          <View style={[styles.image, styles.placeholderImage]}>
            <MaterialIcons name="calendar-today" size={48} color="#ccc" />
          </View>
        )}

        {/* Status Badge */}
        <View
          style={[
            styles.statusBadge,
            { backgroundColor: statusConfig.color },
          ]}
        >
          <Text style={styles.statusText}>{statusConfig.label}</Text>
        </View>
      </View>

      {/* Card Body */}
      <View style={styles.cardBody}>
        <Text style={styles.cardTitle} numberOfLines={2}>
          {truncateText(event.ten_su_kien, 55)}
        </Text>

        {/* Meta Information */}
        <View style={styles.cardMeta}>
          {event.thoi_gian_bat_dau && (
            <View style={styles.metaItem}>
              <MaterialIcons name="access-time" size={12} color="#666" />
              <Text style={styles.metaText} numberOfLines={1}>
                {formatDateTime(event.thoi_gian_bat_dau)}
              </Text>
            </View>
          )}

          {event.dia_diem && (
            <View style={styles.metaItem}>
              <MaterialIcons name="location-on" size={12} color="#666" />
              <Text style={styles.metaText} numberOfLines={1}>
                {event.dia_diem.length > 35 ? event.dia_diem.substring(0, 35) + '...' : event.dia_diem}
              </Text>
            </View>
          )}

          {event.diem_cong > 0 && (
            <View style={styles.metaItem}>
              <MaterialIcons name="star" size={12} color="#ffc107" />
              <Text style={styles.metaText}>+{event.diem_cong} điểm</Text>
            </View>
          )}
        </View>
      </View>

      {/* Card Footer */}
      <View style={styles.cardFooter}>
        <View style={styles.participantInfo}>
          <MaterialIcons name="people" size={14} color="#666" />
          <Text style={styles.participantText}>
            {event.so_luong_hien_tai}/{event.so_luong_toi_da || '∞'}
          </Text>
        </View>
        <MaterialIcons name="arrow-forward" size={16} color="#007bff" />
      </View>
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    overflow: 'hidden',
    marginBottom: 16,
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  imageContainer: {
    position: 'relative',
    width: '100%',
    height: 180,
    backgroundColor: '#f0f0f0',
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
    top: 10,
    right: 10,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 6,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.15,
    shadowRadius: 3,
  },
  statusText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: '600',
  },
  cardBody: {
    padding: 12,
  },
  cardTitle: {
    fontSize: 15,
    fontWeight: '700',
    color: '#212529',
    marginBottom: 10,
    lineHeight: 20,
  },
  cardMeta: {
    gap: 8,
  },
  metaItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
  },
  metaText: {
    fontSize: 12,
    color: '#666',
    flex: 1,
  },
  cardFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderTopWidth: 1,
    borderTopColor: '#e9ecef',
  },
  participantInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
  },
  participantText: {
    fontSize: 12,
    color: '#666',
    fontWeight: '500',
  },
});

export default EventCard;
