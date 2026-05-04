import React, { useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import useAuthStore from '../store/authStore';
import Colors from '../constants/Colors';

const RegisterScreen = ({ navigation }) => {
  const register = useAuthStore((state) => state.register);
  const [loading, setLoading] = useState(false);
  const [form, setForm] = useState({
    ho_ten: '',
    ma_sinh_vien: '',
    lop: '',
    email: '',
    so_dien_thoai: '',
    password: '',
    password_confirmation: '',
  });

  const updateField = (key, value) => setForm((current) => ({ ...current, [key]: value }));

  const handleSubmit = async () => {
    if (!form.ho_ten || !form.ma_sinh_vien || !form.lop || !form.email || !form.password) {
      Alert.alert('Thong bao', 'Vui long nhap day du thong tin bat buoc.');
      return;
    }

    setLoading(true);
    const result = await register(form);
    setLoading(false);

    if (result.success) {
      Alert.alert('Dang ky thanh cong', result.message, [
        { text: 'Dang nhap', onPress: () => navigation.goBack() },
      ]);
    } else {
      const firstError = result.errors ? Object.values(result.errors)?.[0]?.[0] : null;
      Alert.alert('Khong thanh cong', firstError || result.message);
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <ScrollView contentContainerStyle={styles.content} keyboardShouldPersistTaps="handled">
        <Text style={styles.title}>Tao tai khoan</Text>
        <Text style={styles.subtitle}>Dang ky tai khoan sinh vien de tham gia su kien.</Text>

        <View style={styles.form}>
          <TextInput style={styles.input} placeholder="Ho ten" value={form.ho_ten} onChangeText={(v) => updateField('ho_ten', v)} />
          <TextInput style={styles.input} placeholder="Ma sinh vien (8 so)" keyboardType="number-pad" maxLength={8} value={form.ma_sinh_vien} onChangeText={(v) => updateField('ma_sinh_vien', v)} />
          <TextInput style={styles.input} placeholder="Lop, vi du 64.CNTT-1" autoCapitalize="characters" value={form.lop} onChangeText={(v) => updateField('lop', v)} />
          <TextInput style={styles.input} placeholder="Email" keyboardType="email-address" autoCapitalize="none" value={form.email} onChangeText={(v) => updateField('email', v)} />
          <TextInput style={styles.input} placeholder="So dien thoai" keyboardType="phone-pad" value={form.so_dien_thoai} onChangeText={(v) => updateField('so_dien_thoai', v)} />
          <TextInput style={styles.input} placeholder="Mat khau" secureTextEntry value={form.password} onChangeText={(v) => updateField('password', v)} />
          <TextInput style={styles.input} placeholder="Nhap lai mat khau" secureTextEntry value={form.password_confirmation} onChangeText={(v) => updateField('password_confirmation', v)} />

          <TouchableOpacity style={[styles.button, loading && styles.disabled]} onPress={handleSubmit} disabled={loading}>
            {loading ? <ActivityIndicator color={Colors.white} /> : <Text style={styles.buttonText}>Dang ky</Text>}
          </TouchableOpacity>

          <TouchableOpacity onPress={() => navigation.goBack()} style={styles.secondaryButton}>
            <Text style={styles.secondaryText}>Da co tai khoan? Dang nhap</Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.white },
  content: { padding: 24, paddingTop: 64 },
  title: { fontSize: 30, fontWeight: '900', color: Colors.primary },
  subtitle: { marginTop: 8, marginBottom: 28, color: Colors.textMuted, lineHeight: 20 },
  form: { gap: 14 },
  input: {
    backgroundColor: Colors.background,
    borderColor: Colors.border,
    borderRadius: 12,
    borderWidth: 1,
    color: Colors.text,
    fontSize: 15,
    paddingHorizontal: 16,
    paddingVertical: 14,
  },
  button: { marginTop: 8, backgroundColor: Colors.primary, borderRadius: 12, alignItems: 'center', padding: 16 },
  disabled: { opacity: 0.7 },
  buttonText: { color: Colors.white, fontSize: 16, fontWeight: '800' },
  secondaryButton: { alignItems: 'center', padding: 12 },
  secondaryText: { color: Colors.primary, fontWeight: '700' },
});

export default RegisterScreen;
