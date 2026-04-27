import React, { useEffect, useState, useCallback } from 'react';
import {
  StyleSheet,
  View,
  ScrollView,
  ActivityIndicator,
  Text,
  TouchableOpacity,
  Alert,
  SafeAreaView,
  Dimensions,
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import Colors from '../constants/Colors';

const BauCuDetailScreen = ({ route, navigation }) => {
  const { votingId } = route.params;
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedIds, setSelectedIds] = useState([]);
  const [submitting, setSubmitting] = useState(false);

  const fetchDetail = useCallback(async () => {
    try {
      setLoading(true);
      const response = await api.get(`/voting/${votingId}`);
      if (response.data.success) {
        setData(response.data.data);
      }
    } catch (error) {
      console.error('Lỗi khi tải chi tiết bầu cử:', error);
      Alert.alert('Lỗi', 'Không thể tải thông tin cuộc bầu cử');
    } finally {
      setLoading(false);
    }
  }, [votingId]);

  useEffect(() => {
    fetchDetail();
  }, [fetchDetail]);

  const toggleSelection = (id) => {
    if (data.da_bo_phieu || data.bau_cu.trang_thai_thuc_te !== 'dang_dien_ra') return;

    if (selectedIds.includes(id)) {
      setSelectedIds(selectedIds.filter((item) => item !== id));
    } else {
      if (selectedIds.length >= data.bau_cu.so_chon_toi_da) {
        Alert.alert('Thông báo', `Bạn chỉ được chọn tối đa ${data.bau_cu.so_chon_toi_da} ứng cử viên.`);
        return;
      }
      setSelectedIds([...selectedIds, id]);
    }
  };

  const handleSubmit = async () => {
    if (selectedIds.length < data.bau_cu.so_chon_toi_thieu) {
      Alert.alert('Thông báo', `Vui lòng chọn tối thiểu ${data.bau_cu.so_chon_toi_thieu} ứng cử viên.`);
      return;
    }

    Alert.alert(
      'Xác nhận bỏ phiếu',
      `Bạn có chắc chắn muốn bỏ phiếu cho ${selectedIds.length} ứng cử viên đã chọn? Hành động này không thể hoàn tác.`,
      [
        { text: 'Hủy', style: 'cancel' },
        {
          text: 'Đồng ý',
          onPress: async () => {
            try {
              setSubmitting(true);
              const response = await api.post(`/voting/${votingId}/vote`, {
                ung_cu_vien: selectedIds,
              });
              if (response.data.success) {
                Alert.alert('Thành công', 'Bạn đã bỏ phiếu thành công!');
                fetchDetail();
              }
            } catch (error) {
              const msg = error.response?.data?.message || 'Có lỗi xảy ra khi gửi phiếu bầu.';
              Alert.alert('Lỗi', msg);
            } finally {
              setSubmitting(false);
            }
          },
        },
      ]
    );
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color={Colors.primary} />
      </View>
    );
  }

  if (!data) return null;

  const { bau_cu, ung_cu_viens, da_bo_phieu, la_cu_tri, ket_qua } = data;
  const isVotingOpen = bau_cu.trang_thai_thuc_te === 'dang_dien_ra';

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContent}>
        <View style={styles.header}>
          <Text style={styles.title}>{bau_cu.ten_cuoc_bau_cu}</Text>
          <View style={styles.statusRow}>
            <View style={[styles.statusBadge, { backgroundColor: isVotingOpen ? Colors.successBg : Colors.background }]}>
              <Text style={[styles.statusText, { color: isVotingOpen ? Colors.success : Colors.textMuted }]}>
                {isVotingOpen ? 'Đang diễn ra' : 'Đã kết thúc/Chưa bắt đầu'}
              </Text>
            </View>
            <Text style={styles.dateRange}>
              {new Date(bau_cu.thoi_gian_bat_dau).toLocaleDateString('vi-VN')} - {new Date(bau_cu.thoi_gian_ket_thuc).toLocaleDateString('vi-VN')}
            </Text>
          </View>
          <Text style={styles.description}>{bau_cu.mo_ta}</Text>
        </View>

        {!la_cu_tri && (
          <View style={styles.alertBox}>
            <MaterialIcons name="info-outline" size={20} color={Colors.danger} />
            <Text style={styles.alertText}>Bạn không nằm trong danh sách cử tri của cuộc bầu cử này.</Text>
          </View>
        )}

        {da_bo_phieu && (
          <View style={[styles.alertBox, { backgroundColor: Colors.successBg }]}>
            <MaterialIcons name="check-circle" size={20} color={Colors.success} />
            <Text style={[styles.alertText, { color: Colors.success }]}>Bạn đã hoàn thành bỏ phiếu cho cuộc bầu cử này.</Text>
          </View>
        )}

        <View style={styles.section}>
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Danh sách ứng cử viên</Text>
            {!da_bo_phieu && isVotingOpen && (
              <Text style={styles.selectionCount}>
                Đã chọn: {selectedIds.length}/{bau_cu.so_chon_toi_da}
              </Text>
            )}
          </View>

          {ung_cu_viens.map((ucv) => {
            const isSelected = selectedIds.includes(ucv.ma_ung_cu_vien);
            const result = ket_qua?.find(r => r.ma_ung_cu_vien === ucv.ma_ung_cu_vien);
            
            return (
              <TouchableOpacity
                key={ucv.ma_ung_cu_vien}
                style={[
                  styles.candidateCard,
                  isSelected && styles.candidateSelected,
                  (da_bo_phieu || !isVotingOpen) && styles.candidateDisabled
                ]}
                onPress={() => toggleSelection(ucv.ma_ung_cu_vien)}
                disabled={da_bo_phieu || !isVotingOpen}
                activeOpacity={0.8}
              >
                <View style={styles.candidateInfo}>
                  <View style={styles.avatarContainer}>
                    <Text style={styles.avatarText}>{ucv.ho_ten.charAt(0)}</Text>
                  </View>
                  <View style={styles.candidateDetails}>
                    <Text style={styles.candidateName}>{ucv.ho_ten}</Text>
                    <Text style={styles.candidateSub}>{ucv.lop} • MSSV: {ucv.ma_sinh_vien}</Text>
                  </View>
                  {!da_bo_phieu && isVotingOpen && (
                    <View style={[styles.checkbox, isSelected && styles.checkboxActive]}>
                      {isSelected && <MaterialIcons name="check" size={16} color={Colors.white} />}
                    </View>
                  )}
                </View>
                
                {bau_cu.hien_thi_ket_qua && result && (
                  <View style={styles.resultContainer}>
                    <View style={styles.progressBarBg}>
                      <View 
                        style={[
                          styles.progressBarFill, 
                          { width: `${result.phan_tram}%`, backgroundColor: isSelected ? Colors.primary : Colors.border }
                        ]} 
                      />
                    </View>
                    <View style={styles.resultStats}>
                      <Text style={styles.voteCount}>{result.so_phieu} phiếu</Text>
                      <Text style={styles.votePercent}>{result.phan_tram}%</Text>
                    </View>
                  </View>
                )}
              </TouchableOpacity>
            );
          })}
        </View>

        {bau_cu.hien_thi_ket_qua && data.tong_so_phieu !== undefined && (
          <View style={styles.summaryBox}>
            <Text style={styles.summaryTitle}>Tổng hợp bầu cử</Text>
            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabel}>Tổng số cử tri:</Text>
              <Text style={styles.summaryValue}>{data.tong_so_cu_tri}</Text>
            </View>
            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabel}>Đã bỏ phiếu:</Text>
              <Text style={styles.summaryValue}>{data.tong_so_phieu}</Text>
            </View>
            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabel}>Tỷ lệ tham gia:</Text>
              <Text style={styles.summaryValue}>
                {data.tong_so_cu_tri > 0 ? ((data.tong_so_phieu / data.tong_so_cu_tri) * 100).toFixed(1) : 0}%
              </Text>
            </View>
          </View>
        )}
      </ScrollView>

      {!da_bo_phieu && isVotingOpen && la_cu_tri && (
        <View style={styles.footer}>
          <TouchableOpacity
            style={[styles.submitBtn, selectedIds.length < bau_cu.so_chon_toi_thieu && styles.submitBtnDisabled]}
            onPress={handleSubmit}
            disabled={submitting || selectedIds.length < bau_cu.so_chon_toi_thieu}
          >
            {submitting ? (
              <ActivityIndicator color={Colors.white} />
            ) : (
              <>
                <MaterialIcons name="send" size={20} color={Colors.white} />
                <Text style={styles.submitBtnText}>Gửi phiếu bầu</Text>
              </>
            )}
          </TouchableOpacity>
        </View>
      )}
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.white,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  scrollContent: {
    paddingBottom: 120,
  },
  header: {
    padding: 24,
    backgroundColor: Colors.white,
    borderBottomWidth: 1,
    borderBottomColor: Colors.borderLight,
  },
  title: {
    fontSize: 22,
    fontWeight: '800',
    color: Colors.text,
    lineHeight: 30,
    marginBottom: 12,
  },
  statusRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
    marginBottom: 16,
  },
  statusBadge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 6,
  },
  statusText: {
    fontSize: 12,
    fontWeight: '800',
    textTransform: 'uppercase',
  },
  dateRange: {
    fontSize: 13,
    color: Colors.textMuted,
    fontWeight: '600',
  },
  description: {
    fontSize: 15,
    color: Colors.textLight,
    lineHeight: 22,
  },
  alertBox: {
    margin: 16,
    padding: 16,
    backgroundColor: Colors.dangerBg,
    borderRadius: 12,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  alertText: {
    flex: 1,
    fontSize: 14,
    color: Colors.danger,
    fontWeight: '600',
  },
  section: {
    padding: 24,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '800',
    color: Colors.text,
  },
  selectionCount: {
    fontSize: 14,
    fontWeight: '700',
    color: Colors.primary,
  },
  candidateCard: {
    backgroundColor: Colors.white,
    borderRadius: 16,
    padding: 16,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: Colors.borderLight,
    elevation: 2,
    shadowColor: Colors.black,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
  },
  candidateSelected: {
    borderColor: Colors.primary,
    backgroundColor: Colors.primaryBg,
  },
  candidateDisabled: {
    opacity: 0.9,
  },
  candidateInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 16,
  },
  avatarContainer: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: Colors.borderLight,
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarText: {
    fontSize: 18,
    fontWeight: '800',
    color: Colors.textMuted,
  },
  candidateDetails: {
    flex: 1,
  },
  candidateName: {
    fontSize: 16,
    fontWeight: '700',
    color: Colors.text,
  },
  candidateSub: {
    fontSize: 13,
    color: Colors.textMuted,
    marginTop: 2,
  },
  checkbox: {
    width: 24,
    height: 24,
    borderRadius: 12,
    borderWidth: 2,
    borderColor: Colors.border,
    justifyContent: 'center',
    alignItems: 'center',
  },
  checkboxActive: {
    backgroundColor: Colors.primary,
    borderColor: Colors.primary,
  },
  resultContainer: {
    marginTop: 16,
    paddingTop: 16,
    borderTopWidth: 1,
    borderTopColor: Colors.borderLight,
  },
  progressBarBg: {
    height: 6,
    backgroundColor: Colors.background,
    borderRadius: 3,
    overflow: 'hidden',
    marginBottom: 8,
  },
  progressBarFill: {
    height: '100%',
    borderRadius: 3,
  },
  resultStats: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  voteCount: {
    fontSize: 12,
    color: Colors.textMuted,
    fontWeight: '600',
  },
  votePercent: {
    fontSize: 14,
    color: Colors.text,
    fontWeight: '800',
  },
  summaryBox: {
    margin: 24,
    padding: 20,
    backgroundColor: Colors.background,
    borderRadius: 20,
  },
  summaryTitle: {
    fontSize: 16,
    fontWeight: '800',
    color: Colors.text,
    marginBottom: 16,
    textAlign: 'center',
  },
  summaryRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 8,
  },
  summaryLabel: {
    fontSize: 14,
    color: Colors.textMuted,
    fontWeight: '600',
  },
  summaryValue: {
    fontSize: 14,
    color: Colors.text,
    fontWeight: '700',
  },
  footer: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    padding: 24,
    paddingBottom: 40,
    backgroundColor: Colors.white,
    borderTopWidth: 1,
    borderTopColor: Colors.borderLight,
  },
  submitBtn: {
    backgroundColor: Colors.primary,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 16,
    borderRadius: 16,
    gap: 12,
    elevation: 4,
    shadowColor: Colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 10,
  },
  submitBtnDisabled: {
    backgroundColor: Colors.border,
    elevation: 0,
    shadowOpacity: 0,
  },
  submitBtnText: {
    color: Colors.white,
    fontSize: 16,
    fontWeight: '700',
  },
});

export default BauCuDetailScreen;
