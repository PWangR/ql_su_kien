import React from 'react';
import { StyleSheet, View, ScrollView, TouchableOpacity, Text } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import Colors from '../constants/Colors';

const EventFilters = ({ 
  statuses = [], 
  categories = [], 
  selectedStatus, 
  selectedCategory, 
  onStatusChange, 
  onCategoryChange,
  onClearFilters 
}) => {
  const statusOptions = [
    { value: '', label: 'Tất cả' },
    { value: 'sap_to_chuc', label: 'Sắp tổ chức' },
    { value: 'dang_dien_ra', label: 'Đang diễn ra' },
    { value: 'da_ket_thuc', label: 'Đã kết thúc' },
  ];

  const hasActiveFilters = (selectedStatus && selectedStatus !== '') || (selectedCategory && selectedCategory !== '');

  return (
    <View style={styles.container}>
      {/* Status Filter */}
      <View style={styles.filterSection}>
        <View style={styles.filterHeader}>
          <Text style={styles.filterLabel}>Trạng thái</Text>
          {hasActiveFilters && (
            <TouchableOpacity onPress={onClearFilters} style={styles.clearBtn}>
              <MaterialIcons name="refresh" size={14} color={Colors.primary} />
              <Text style={styles.clearText}>Đặt lại</Text>
            </TouchableOpacity>
          )}
        </View>
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.filterScroll}
        >
          {statusOptions.map((status) => (
            <TouchableOpacity
              key={status.value}
              style={[
                styles.filterChip,
                selectedStatus === status.value && styles.filterChipActive,
              ]}
              onPress={() => onStatusChange(status.value)}
              activeOpacity={0.7}
            >
              <Text
                style={[
                  styles.filterChipText,
                  selectedStatus === status.value && styles.filterChipTextActive,
                ]}
              >
                {status.label}
              </Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </View>

      {/* Category Filter */}
      {categories && categories.length > 0 && (
        <View style={styles.filterSection}>
          <Text style={styles.filterLabel}>Loại sự kiện</Text>
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.filterScroll}
          >
            <TouchableOpacity
              style={[
                styles.filterChip,
                selectedCategory === '' && styles.filterChipActive,
              ]}
              onPress={() => onCategoryChange('')}
              activeOpacity={0.7}
            >
              <Text
                style={[
                  styles.filterChipText,
                  selectedCategory === '' && styles.filterChipTextActive,
                ]}
              >
                Tất cả
              </Text>
            </TouchableOpacity>
            {categories.map((category) => (
              <TouchableOpacity
                key={category.ma_loai_su_kien}
                style={[
                  styles.filterChip,
                  selectedCategory === category.ma_loai_su_kien && styles.filterChipActive,
                ]}
                onPress={() => onCategoryChange(category.ma_loai_su_kien)}
                activeOpacity={0.7}
              >
                <Text
                  style={[
                    styles.filterChipText,
                    selectedCategory === category.ma_loai_su_kien && styles.filterChipTextActive,
                  ]}
                >
                  {category.ten_loai}
                </Text>
              </TouchableOpacity>
            ))}
          </ScrollView>
        </View>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    backgroundColor: Colors.white,
    paddingVertical: 14,
    borderBottomWidth: 1,
    borderBottomColor: Colors.borderLight,
  },
  filterSection: {
    marginBottom: 16,
    paddingHorizontal: 16,
  },
  filterHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  filterLabel: {
    fontSize: 13,
    fontWeight: '700',
    color: Colors.textMuted,
    textTransform: 'uppercase',
    letterSpacing: 0.5,
  },
  clearBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  clearText: {
    fontSize: 12,
    color: Colors.primary,
    fontWeight: '600',
  },
  filterScroll: {
    paddingBottom: 4,
    gap: 8,
  },
  filterChip: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: Colors.border,
    backgroundColor: Colors.white,
    marginRight: 8,
  },
  filterChipActive: {
    backgroundColor: Colors.primary,
    borderColor: Colors.primary,
  },
  filterChipText: {
    fontSize: 13,
    fontWeight: '600',
    color: Colors.textMuted,
  },
  filterChipTextActive: {
    color: Colors.white,
  },
});

export default EventFilters;
