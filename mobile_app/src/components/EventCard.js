import React, { memo } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, Image } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import Colors from '../constants/Colors';
import Typography from '../constants/Typography';
import { BASE_URL } from '../services/api';

const EventCard = memo(({ event, onPress }) => {
  const getStatusConfig = (status) => {
    const config = {
      sap_to_chuc: { label: 'Sắp tổ chức', color: Colors.primary, bgColor: Colors.primaryBg },
      dang_dien_ra: { label: 'Đang diễn ra', color: Colors.success, bgColor: Colors.successBg },
      da_ket_thuc: { label: 'Đã kết thúc', color: Colors.danger, bgColor: Colors.dangerBg },
    };
    return config[status] || { label: 'Không xác định', color: Colors.textMuted, bgColor: Colors.background };
  };

  const statusConfig = getStatusConfig(event.trang_thai_thuc_te);
  const imageUrl = event.anh_su_kien ? `${BASE_URL}/storage/${event.anh_su_kien}` : null;

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

  return (
    <TouchableOpacity style={styles.card} onPress={onPress} activeOpacity={0.8}>
      <View style={styles.imageContainer}>
        {imageUrl ? (
          <Image source={{ uri: imageUrl }} style={styles.image} resizeMode="cover" />
        ) : (
          <View style={[styles.image, styles.placeholderImage]}>
            <MaterialIcons name="calendar-today" size={40} color={Colors.border} />
          </View>
        )}
        <View style={[styles.statusBadge, { backgroundColor: statusConfig.bgColor, borderColor: statusConfig.color }]}>
          <Text style={[styles.statusText, { color: statusConfig.color }]}>{statusConfig.label}</Text>
        </View>
      </View>

      <View style={styles.cardBody}>
        {event.loai_su_kien && (
          <Text style={[Typography.label, { color: Colors.primary, marginBottom: 4 }]}>
            {event.loai_su_kien.ten_loai}
          </Text>
        )}
        <Text style={[Typography.h3, styles.cardTitle]} numberOfLines={2}>
          {event.ten_su_kien}
        </Text>

        <View style={styles.cardMeta}>
          {event.thoi_gian_bat_dau && (
            <View style={styles.metaItem}>
              <MaterialIcons name="access-time" size={14} color={Colors.textMuted} />
              <Text style={[Typography.caption, styles.metaText]} numberOfLines={1}>
                {formatDateTime(event.thoi_gian_bat_dau)}
              </Text>
            </View>
          )}
          {event.dia_diem && (
            <View style={styles.metaItem}>
              <MaterialIcons name="location-on" size={14} color={Colors.textMuted} />
              <Text style={[Typography.caption, styles.metaText]} numberOfLines={1}>
                {event.dia_diem}
              </Text>
            </View>
          )}
        </View>
      </View>

      <View style={styles.cardFooter}>
        <View style={styles.participantCount}>
          <MaterialIcons name="group" size={14} color={Colors.textMuted} />
          <Text style={[Typography.caption, { marginLeft: 4 }]}>
            {event.so_luong_hien_tai}/{event.so_luong_toi_da || '∞'}
          </Text>
        </View>
        <View style={styles.actionLink}>
          <Text style={[Typography.label, { color: Colors.primary, textTransform: 'none' }]}>Xem chi tiết</Text>
          <MaterialIcons name="arrow-forward" size={14} color={Colors.primary} />
        </View>
      </View>
    </TouchableOpacity>
  );
});

const styles = StyleSheet.create({
  card: {
    backgroundColor: Colors.white,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: Colors.border,
    overflow: 'hidden',
    elevation: 2,
    shadowColor: '#0F172A',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.05,
    shadowRadius: 10,
  },
  imageContainer: {
    height: 160,
    width: '100%',
    backgroundColor: '#F8FAFC',
  },
  image: {
    width: '100%',
    height: '100%',
  },
  placeholderImage: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  statusBadge: {
    position: 'absolute',
    top: 12,
    right: 12,
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 4,
    borderWidth: 1,
  },
  statusText: {
    fontSize: 10,
    fontWeight: '800',
    textTransform: 'uppercase',
  },
  cardBody: {
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: Colors.borderLight,
  },
  cardTitle: {
    color: Colors.text,
    marginBottom: 12,
  },
  cardMeta: {
    gap: 6,
  },
  metaItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  metaText: {
    color: Colors.textMuted,
    flex: 1,
  },
  cardFooter: {
    paddingHorizontal: 16,
    paddingVertical: 12,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#FCFDFF',
  },
  participantCount: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  actionLink: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
});

export default EventCard;
