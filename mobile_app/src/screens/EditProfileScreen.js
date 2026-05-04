import React, { useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  Image,
  SafeAreaView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import api from '../services/api';
import useAuthStore from '../store/authStore';
import Colors from '../constants/Colors';

const EditProfileScreen = ({ navigation }) => {
  const { user, setUser } = useAuthStore();
  const [loading, setLoading] = useState(false);
  const [form, setForm] = useState({
    ho_ten: user?.ho_ten || user?.name || '',
    email: user?.email || '',
    lop: user?.lop || '',
    so_dien_thoai: user?.so_dien_thoai || '',
  });
  const [avatar, setAvatar] = useState(null);

  const updateField = (key, value) => setForm((current) => ({ ...current, [key]: value }));

  const pickAvatar = async () => {
    const permission = await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (!permission.granted) {
      Alert.alert('Không thành công', 'Ứng dụng cần quyền truy cập thư viện ảnh.');
      return;
    }

    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      aspect: [1, 1],
      quality: 0.85,
    });

    if (!result.canceled && result.assets?.[0]) {
      setAvatar(result.assets[0]);
    }
  };

  const save = async () => {
    setLoading(true);
    try {
      const payload = new FormData();
      Object.entries(form).forEach(([key, value]) => payload.append(key, value || ''));
      if (avatar) {
        payload.append('avatar', {
          uri: avatar.uri,
          name: avatar.fileName || 'avatar.jpg',
          type: avatar.mimeType || 'image/jpeg',
        });
      }

      const response = await api.post('/user/profile/update', payload, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });
      if (response.data.success) {
        const nextUser = response.data.data;
        await setUser({
          ...user,
          ...nextUser,
          name: nextUser.ho_ten || nextUser.name || user?.name,
        });
        Alert.alert('Thành công', 'Đã cập nhật hồ sơ.');
        navigation.goBack();
      }
    } catch (error) {
      const firstError = error.response?.data?.errors ? Object.values(error.response.data.errors)?.[0]?.[0] : null;
      Alert.alert('Không thành công', firstError || error.response?.data?.message || 'Không thể cập nhật hồ sơ.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.content}>
        <Text style={styles.title}>Chỉnh sửa hồ sơ</Text>
        <TouchableOpacity style={styles.avatarPicker} onPress={pickAvatar}>
          {avatar?.uri ? (
            <Image source={{ uri: avatar.uri }} style={styles.avatarPreview} />
          ) : (
            <Text style={styles.avatarPickerText}>Chọn ảnh đại diện</Text>
          )}
        </TouchableOpacity>
        <TextInput style={styles.input} placeholder="Họ tên" value={form.ho_ten} onChangeText={(v) => updateField('ho_ten', v)} />
        <TextInput style={styles.input} placeholder="Email" keyboardType="email-address" autoCapitalize="none" value={form.email} onChangeText={(v) => updateField('email', v)} />
        <TextInput style={styles.input} placeholder="Lớp" value={form.lop} onChangeText={(v) => updateField('lop', v)} />
        <TextInput style={styles.input} placeholder="Số điện thoại" keyboardType="phone-pad" value={form.so_dien_thoai} onChangeText={(v) => updateField('so_dien_thoai', v)} />

        <TouchableOpacity style={styles.button} onPress={save} disabled={loading}>
          {loading ? <ActivityIndicator color={Colors.white} /> : <Text style={styles.buttonText}>Lưu thay đổi</Text>}
        </TouchableOpacity>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.background },
  content: { padding: 20, gap: 14 },
  title: { fontSize: 24, fontWeight: '900', color: Colors.text, marginBottom: 10 },
  avatarPicker: {
    alignSelf: 'center',
    width: 128,
    height: 128,
    borderRadius: 64,
    borderWidth: 1,
    borderColor: Colors.border,
    backgroundColor: Colors.white,
    alignItems: 'center',
    justifyContent: 'center',
    overflow: 'hidden',
    marginBottom: 8,
  },
  avatarPreview: {
    width: '100%',
    height: '100%',
  },
  avatarPickerText: {
    color: Colors.primary,
    fontWeight: '800',
    textAlign: 'center',
    paddingHorizontal: 12,
  },
  input: {
    backgroundColor: Colors.white,
    borderColor: Colors.border,
    borderRadius: 12,
    borderWidth: 1,
    color: Colors.text,
    fontSize: 15,
    paddingHorizontal: 16,
    paddingVertical: 14,
  },
  button: { marginTop: 8, backgroundColor: Colors.primary, borderRadius: 12, alignItems: 'center', padding: 16 },
  buttonText: { color: Colors.white, fontSize: 15, fontWeight: '800' },
});

export default EditProfileScreen;
