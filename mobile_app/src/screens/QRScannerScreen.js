import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, Dimensions, SafeAreaView } from 'react-native';
import { CameraView, useCameraPermissions } from 'expo-camera';
import { MaterialIcons } from '@expo/vector-icons';
import useQueueStore from '../store/queueStore';
import Colors from '../constants/Colors';

export default function QRScannerScreen({ navigation }) {
  const [permission, requestPermission] = useCameraPermissions();
  const [scanned, setScanned] = useState(false);
  const [statusMessage, setStatusMessage] = useState('Hướng camera vào mã QR sự kiện để điểm danh');
  const [isSuccess, setIsSuccess] = useState(false);

  if (!permission) {
    return <View style={styles.container} />;
  }

  if (!permission.granted) {
    return (
      <SafeAreaView style={styles.permissionContainer}>
        <View style={styles.permissionContent}>
          <MaterialIcons name="camera-alt" size={80} color={Colors.primary} />
          <Text style={styles.permissionTitle}>Quyền truy cập Camera</Text>
          <Text style={styles.permissionText}>
            Ứng dụng cần quyền truy cập camera để quét mã QR điểm danh tham gia sự kiện.
          </Text>
          <TouchableOpacity style={styles.permissionBtn} onPress={requestPermission}>
            <Text style={styles.permissionBtnText}>Cấp quyền ngay</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    );
  }

  const handleBarCodeScanned = async ({ type, data }) => {
    setScanned(true);
    try {
      const payload = JSON.parse(data);

      if (!['diem_danh', 'student_checkin'].includes(payload.action) || !payload.ma_su_kien || !payload.t) {
        throw new Error('Mã QR không hợp lệ, hoặc không đúng định dạng điểm danh.');
      }

      const diff = Math.abs(Date.now() - payload.t);
      console.log('Time diff (ms):', diff);

      if (diff > 20000) {
        throw new Error('Mã QR đã hết hạn, vui lòng quét mã mới nhất.');
      }

      // Lưu vào hàng chờ
      useQueueStore.getState().enqueue({
        ma_su_kien: payload.ma_su_kien,
        ma_sinh_vien: payload.ma_sinh_vien || null,
        action: payload.action,
        scanned_at: Date.now()
      });

      setStatusMessage('Điểm danh thành công! Thông tin sẽ được gửi về máy chủ khi có kết nối.');
      setIsSuccess(true);
    } catch (error) {
      setStatusMessage('Lỗi quét mã: ' + error.message);
      setIsSuccess(false);
    }
  };

  return (
    <View style={styles.container}>
      <CameraView
        style={styles.camera}
        facing="back"
        onBarcodeScanned={scanned ? undefined : handleBarCodeScanned}
        barcodeScannerSettings={{
          barcodeTypes: ["qr"],
        }}
      >
        <View style={styles.overlay}>
          <TouchableOpacity style={styles.closeBtn} onPress={() => navigation.goBack()}>
            <MaterialIcons name="close" size={28} color={Colors.white} />
          </TouchableOpacity>
          
          <View style={styles.scanFrameContainer}>
            <View style={styles.scanFrame}>
              <View style={[styles.corner, styles.topLeft]} />
              <View style={[styles.corner, styles.topRight]} />
              <View style={[styles.corner, styles.bottomLeft]} />
              <View style={[styles.corner, styles.bottomRight]} />
            </View>
            <Text style={styles.hintText}>Đưa mã QR vào giữa khung hình</Text>
          </View>
        </View>
      </CameraView>

      <View style={[styles.bottomCard, isSuccess ? styles.cardSuccess : (scanned ? styles.cardError : styles.cardNormal)]}>
        <View style={styles.statusHeader}>
          <MaterialIcons 
            name={isSuccess ? "check-circle" : (scanned ? "error" : "qr-code-scanner")} 
            size={32} 
            color={isSuccess ? Colors.success : (scanned ? Colors.danger : Colors.primary)} 
          />
          <Text style={[styles.statusTitle, isSuccess ? styles.textSuccess : (scanned ? styles.textError : styles.textNormal)]}>
            {isSuccess ? 'Thành công' : (scanned ? 'Thất bại' : 'Đang chờ quét...')}
          </Text>
        </View>
        
        <Text style={styles.statusText}>{statusMessage}</Text>

        {scanned && (
          <TouchableOpacity 
            style={[styles.actionBtn, isSuccess ? styles.btnSuccess : styles.btnError]} 
            onPress={() => isSuccess ? navigation.goBack() : setScanned(false)}
          >
            <Text style={styles.actionBtnText}>{isSuccess ? "Hoàn tất" : "Thử lại"}</Text>
          </TouchableOpacity>
        )}
      </View>
    </View>
  );
}

const { width } = Dimensions.get('window');
const frameSize = width * 0.7;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.black,
  },
  permissionContainer: {
    flex: 1,
    backgroundColor: Colors.white,
    justifyContent: 'center',
    padding: 32,
  },
  permissionContent: {
    alignItems: 'center',
  },
  permissionTitle: {
    fontSize: 22,
    fontWeight: '800',
    color: Colors.text,
    marginTop: 24,
    marginBottom: 12,
  },
  permissionText: {
    fontSize: 15,
    color: Colors.textMuted,
    textAlign: 'center',
    lineHeight: 22,
    marginBottom: 32,
  },
  permissionBtn: {
    backgroundColor: Colors.primary,
    paddingHorizontal: 32,
    paddingVertical: 16,
    borderRadius: 16,
    width: '100%',
    alignItems: 'center',
  },
  permissionBtnText: {
    color: Colors.white,
    fontSize: 16,
    fontWeight: '700',
  },
  camera: {
    flex: 1,
  },
  overlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.6)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  closeBtn: {
    position: 'absolute',
    top: 50,
    left: 20,
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  scanFrameContainer: {
    alignItems: 'center',
  },
  scanFrame: {
    width: frameSize,
    height: frameSize,
    position: 'relative',
  },
  corner: {
    position: 'absolute',
    width: 30,
    height: 30,
    borderColor: Colors.primary,
    borderWidth: 4,
  },
  topLeft: {
    top: 0,
    left: 0,
    borderRightWidth: 0,
    borderBottomWidth: 0,
  },
  topRight: {
    top: 0,
    right: 0,
    borderLeftWidth: 0,
    borderBottomWidth: 0,
  },
  bottomLeft: {
    bottom: 0,
    left: 0,
    borderRightWidth: 0,
    borderTopWidth: 0,
  },
  bottomRight: {
    bottom: 0,
    right: 0,
    borderLeftWidth: 0,
    borderTopWidth: 0,
  },
  hintText: {
    color: Colors.white,
    fontSize: 14,
    marginTop: 24,
    fontWeight: '600',
    backgroundColor: 'rgba(0,0,0,0.4)',
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
  },
  bottomCard: {
    padding: 24,
    paddingBottom: 40,
    backgroundColor: Colors.white,
    borderTopLeftRadius: 30,
    borderTopRightRadius: 30,
    minHeight: 200,
  },
  statusHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
    gap: 12,
  },
  statusTitle: {
    fontSize: 20,
    fontWeight: '800',
  },
  statusText: {
    fontSize: 15,
    color: Colors.textLight,
    lineHeight: 22,
    marginBottom: 24,
  },
  cardSuccess: {
    borderTopWidth: 4,
    borderTopColor: Colors.success,
  },
  cardError: {
    borderTopWidth: 4,
    borderTopColor: Colors.danger,
  },
  cardNormal: {
    borderTopWidth: 4,
    borderTopColor: Colors.primary,
  },
  textSuccess: {
    color: Colors.success,
  },
  textError: {
    color: Colors.danger,
  },
  textNormal: {
    color: Colors.primary,
  },
  actionBtn: {
    paddingVertical: 16,
    borderRadius: 14,
    alignItems: 'center',
  },
  btnSuccess: {
    backgroundColor: Colors.success,
  },
  btnError: {
    backgroundColor: Colors.danger,
  },
  actionBtnText: {
    color: Colors.white,
    fontSize: 16,
    fontWeight: '700',
  },
});

