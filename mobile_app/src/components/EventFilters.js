import React from 'react';
import { StyleSheet, View, ScrollView, TouchableOpacity, Text } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';

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

  const hasActiveFilters = selectedStatus !== '' || selectedCategory !== '';

  return (
    <View style={styles.container}>
      {/* Status Filter */}
      <View style={styles.filterSection}>
        <View style={styles.filterHeader}>
          <Text style={styles.filterLabel}>Trạng thái</Text>
          {hasActiveFilters && (
            <TouchableOpacity onPress={onClearFilters} style={styles.clearBtn}>
              <MaterialIcons name="close" size={14} color="#007bff" />
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
    backgroundColor: '#fff',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#e9ecef',
  },
  filterSection: {
    marginBottom: 12,
    paddingHorizontal: 16,
  },
  filterHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  filterLabel: {
    fontSize: 14,
    fontWeight: '600',
    color: '#212529',
  },
  clearBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    padding: 6,
  },
  clearText: {
    fontSize: 12,
    color: '#007bff',
    fontWeight: '500',
  },
  filterScroll: {
    paddingBottom: 4,
    gap: 8,
  },
  filterChip: {
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#dee2e6',
    backgroundColor: '#fff',
    marginRight: 8,
  },
  filterChipActive: {
    backgroundColor: '#007bff',
    borderColor: '#007bff',
  },
  filterChipText: {
    fontSize: 12,
    fontWeight: '500',
    color: '#6c757d',
  },
  filterChipTextActive: {
    color: '#fff',
  },
});

export default EventFilters;
