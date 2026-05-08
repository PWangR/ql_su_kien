import React, { useEffect, useState } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, Dimensions, SafeAreaView } from 'react-native';
import { CameraView, useCameraPermissions } from 'expo-camera';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import useAuthStore from '../store/authStore';
import useQueueStore from '../store/queueStore';
import Colors from '../constants/Colors';

export default function QRScannerScreen({ navigation }) {
  const [permission, requestPermission] = useCameraPermissions();
  const { user } = useAuthStore();
  const isAdmin = ['admin', 'super_admin'].includes(user?.vai_tro);
  const [scanned, setScanned] = useState(false);
  const [statusMessage, setStatusMessage] = useState('Huong camera vao ma QR su kien de diem danh');
  const [isSuccess, setIsSuccess] = useState(false);

  useEffect(() => {
    setStatusMessage(
      isAdmin
        ? 'Huong camera vao ma QR ca nhan cua nguoi dung de diem danh'
        : 'Huong camera vao ma QR su kien de diem danh'
    );
  }, [isAdmin]);

  if (!permission) {
    return <View style={styles.container} />;
  }

  if (!permission.granted) {
    return (
      <SafeAreaView style={styles.permissionContainer}>
        <View style={styles.permissionContent}>
          <MaterialIcons name="camera-alt" size={80} color={Colors.primary} />
          <Text style={styles.permissionTitle}>Quyen truy cap Camera</Text>
          <Text style={styles.permissionText}>
            Ung dung can quyen truy cap camera de quet ma QR diem danh tham gia su kien.
          </Text>
          <TouchableOpacity style={styles.permissionBtn} onPress={requestPermission}>
            <Text style={styles.permissionBtnText}>Cap quyen ngay</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    );
  }

  const handleBarCodeScanned = async ({ data }) => {
    setScanned(true);
    try {
      const payload = JSON.parse(data);

      if (isAdmin) {
        if (payload.action !== 'student_checkin' || !payload.ma_su_kien || !payload.ma_sinh_vien) {
          throw new Error('Ma QR khong dung dinh dang diem danh nguoi dung.');
        }

        const response = await api.post('/admin/registrations/scan-student', {
          ma_su_kien: payload.ma_su_kien,
          ma_sinh_vien: payload.ma_sinh_vien,
        });

        setStatusMessage(response.data.message || 'Diem danh nguoi dung thanh cong.');
        setIsSuccess(true);
        return;
      }

      if (payload.action !== 'diem_danh' || !payload.ma_su_kien || !payload.t) {
        throw new Error('Ma QR khong hop le, hoac khong dung dinh dang diem danh su kien.');
      }

      const diff = Math.abs(Date.now() - payload.t);

      if (diff > 20000) {
        throw new Error('Ma QR da het han, vui long quet ma moi nhat.');
      }

      useQueueStore.getState().enqueue({
        ma_su_kien: payload.ma_su_kien,
        ma_sinh_vien: null,
        action: payload.action,
        loai_diem_danh: payload.loai_diem_danh || 'dau_buoi',
        scanned_at: Date.now()
      });

      setStatusMessage('Diem danh thanh cong! Thong tin se duoc gui ve may chu khi co ket noi.');
      setIsSuccess(true);
    } catch (error) {
      const message = error.response?.data?.message || error.message;
      setStatusMessage('Loi quet ma: ' + message);
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
          barcodeTypes: ['qr'],
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
            <Text style={styles.hintText}>Dua ma QR vao giua khung hinh</Text>
          </View>
        </View>
      </CameraView>

      <View style={[styles.bottomCard, isSuccess ? styles.cardSuccess : (scanned ? styles.cardError : styles.cardNormal)]}>
        <View style={styles.statusHeader}>
          <MaterialIcons
            name={isSuccess ? 'check-circle' : (scanned ? 'error' : 'qr-code-scanner')}
            size={32}
            color={isSuccess ? Colors.success : (scanned ? Colors.danger : Colors.primary)}
          />
          <Text style={[styles.statusTitle, isSuccess ? styles.textSuccess : (scanned ? styles.textError : styles.textNormal)]}>
            {isSuccess ? 'Thanh cong' : (scanned ? 'That bai' : 'Dang cho quet...')}
          </Text>
        </View>

        <Text style={styles.statusText}>{statusMessage}</Text>

        {scanned && (
          <TouchableOpacity
            style={[styles.actionBtn, isSuccess ? styles.btnSuccess : styles.btnError]}
            onPress={() => isSuccess ? navigation.goBack() : setScanned(false)}
          >
            <Text style={styles.actionBtnText}>{isSuccess ? 'Hoan tat' : 'Thu lai'}</Text>
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
