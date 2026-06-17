import React, { useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  SafeAreaView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import api from '../services/api';
import Colors from '../constants/Colors';

const ChangePasswordScreen = ({ navigation }) => {
  const [loading, setLoading] = useState(false);
  const [form, setForm] = useState({
    current_password: '',
    password: '',
    password_confirmation: '',
  });

  const updateField = (key, value) => setForm((current) => ({ ...current, [key]: value }));

  const save = async () => {
    if (!form.current_password || !form.password || !form.password_confirmation) {
      Alert.alert('Thông báo', 'Vui lòng nhập đầy đủ thông tin.');
      return;
    }

    setLoading(true);
    try {
      const response = await api.post('/user/change-password', form);
      if (response.data.success) {
        Alert.alert('Thành công', 'Đã đổi mật khẩu.', [
          { text: 'OK', onPress: () => navigation.goBack() },
        ]);
      }
    } catch (error) {
      const firstError = error.response?.data?.errors ? Object.values(error.response.data.errors)?.[0]?.[0] : null;
      Alert.alert('Không thành công', firstError || error.response?.data?.message || 'Không thể đổi mật khẩu.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.content}>
        <Text style={styles.title}>Đổi mật khẩu</Text>
        <TextInput style={styles.input} placeholder="Mật khẩu hiện tại" secureTextEntry value={form.current_password} onChangeText={(v) => updateField('current_password', v)} />
        <TextInput style={styles.input} placeholder="Mật khẩu mới" secureTextEntry value={form.password} onChangeText={(v) => updateField('password', v)} />
        <TextInput style={styles.input} placeholder="Nhập lại mật khẩu mới" secureTextEntry value={form.password_confirmation} onChangeText={(v) => updateField('password_confirmation', v)} />

        <TouchableOpacity style={styles.button} onPress={save} disabled={loading}>
          {loading ? <ActivityIndicator color={Colors.white} /> : <Text style={styles.buttonText}>Cập nhật mật khẩu</Text>}
        </TouchableOpacity>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.background },
  content: { padding: 20, gap: 14 },
  title: { fontSize: 24, fontWeight: '900', color: Colors.text, marginBottom: 10 },
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

export default ChangePasswordScreen;
