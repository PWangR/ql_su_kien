import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, Button, Dimensions } from 'react-native';
import { CameraView, useCameraPermissions } from 'expo-camera';
import useQueueStore from '../store/queueStore';

export default function QRScannerScreen({ navigation }) {
  const [permission, requestPermission] = useCameraPermissions();
  const [scanned, setScanned] = useState(false);
  const [statusMessage, setStatusMessage] = useState('Hướng camera vào mã QR sự kiện để điểm danh');
  const [isSuccess, setIsSuccess] = useState(false);

  if (!permission) {
    return <View />;
  }

  if (!permission.granted) {
    return (
      <View style={styles.container}>
        <Text style={{ textAlign: 'center', marginBottom: 20 }}>
          Ứng dụng cần quyền truy cập camera để quét mã QR điểm danh.
        </Text>
        <Button onPress={requestPermission} title="Cấp quyền Camera" />
      </View>
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
          <View style={styles.scanFrame} />
        </View>
      </CameraView>

      <View style={[styles.bottomCard, isSuccess ? styles.cardSuccess : styles.cardError]}>
        <Text style={[styles.statusText, isSuccess ? styles.textSuccess : styles.textError]}>
          {statusMessage}
        </Text>

        {scanned && (
          <Button
            title={isSuccess ? "Hoàn tất" : "Chạm để quét lại"}
            onPress={() => isSuccess ? navigation.goBack() : setScanned(false)}
            color={isSuccess ? "#007bff" : "#dc3545"}
          />
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
    backgroundColor: '#000',
    justifyContent: 'center',
  },
  camera: {
    flex: 1,
  },
  overlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  scanFrame: {
    width: frameSize,
    height: frameSize,
    borderWidth: 2,
    borderColor: '#4ade80',
    backgroundColor: 'transparent',
    boxShadow: '0 0 0 4000px rgba(0, 0, 0, 0.5)', // fake overlay hole
  },
  bottomCard: {
    padding: 20,
    backgroundColor: '#fff',
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
    minHeight: 150,
  },
  cardSuccess: {
    backgroundColor: '#ecfdf5',
  },
  cardError: {
    backgroundColor: '#fef2f2',
  },
  statusText: {
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 20,
    fontWeight: 'bold',
  },
  textSuccess: {
    color: '#065f46',
  },
  textError: {
    color: '#991b1b',
  }
});
