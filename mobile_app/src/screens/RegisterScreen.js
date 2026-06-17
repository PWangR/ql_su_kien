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
import { MaterialIcons } from '@expo/vector-icons';
import useAuthStore from '../store/authStore';
import Colors from '../constants/Colors';

const RegisterScreen = ({ navigation }) => {
  const register = useAuthStore((state) => state.register);
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
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
      Alert.alert('Thông báo', 'Vui lòng nhập đầy đủ thông tin bắt buộc.');
      return;
    }

    setLoading(true);
    const result = await register(form);
    setLoading(false);

    if (result.success) {
      Alert.alert('Đăng ký thành công', result.message, [
        { text: 'Đăng nhập', onPress: () => navigation.goBack() },
      ]);
    } else {
      const firstError = result.errors ? Object.values(result.errors)?.[0]?.[0] : null;
      Alert.alert('Không thành công', firstError || result.message);
    }
  };

  const renderPasswordToggle = () => (
    <TouchableOpacity
      style={styles.passwordToggle}
      onPress={() => setShowPassword((current) => !current)}
      accessibilityRole="button"
      accessibilityLabel={showPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'}
    >
      <MaterialIcons
        name={showPassword ? 'visibility-off' : 'visibility'}
        size={22}
        color={Colors.textMuted}
      />
    </TouchableOpacity>
  );

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <ScrollView contentContainerStyle={styles.content} keyboardShouldPersistTaps="handled">
        <Text style={styles.title}>Tạo tài khoản</Text>
        <Text style={styles.subtitle}>Đăng ký tài khoản sinh viên để tham gia sự kiện.</Text>

        <View style={styles.form}>
          <TextInput style={styles.input} placeholder="Họ tên" value={form.ho_ten} onChangeText={(v) => updateField('ho_ten', v)} />
          <TextInput style={styles.input} placeholder="Mã sinh viên (8 số)" keyboardType="number-pad" maxLength={8} value={form.ma_sinh_vien} onChangeText={(v) => updateField('ma_sinh_vien', v)} />
          <TextInput style={styles.input} placeholder="Lớp, ví dụ 64.CNTT-1" autoCapitalize="characters" value={form.lop} onChangeText={(v) => updateField('lop', v)} />
          <TextInput style={styles.input} placeholder="Email" keyboardType="email-address" autoCapitalize="none" value={form.email} onChangeText={(v) => updateField('email', v)} />
          <TextInput style={styles.input} placeholder="Số điện thoại" keyboardType="phone-pad" value={form.so_dien_thoai} onChangeText={(v) => updateField('so_dien_thoai', v)} />
          <View style={styles.passwordInputWrapper}>
            <TextInput
              style={styles.passwordInput}
              placeholder="Mật khẩu"
              placeholderTextColor={Colors.textMuted}
              secureTextEntry={!showPassword}
              value={form.password}
              onChangeText={(v) => updateField('password', v)}
            />
            {renderPasswordToggle()}
          </View>
          <View style={styles.passwordInputWrapper}>
            <TextInput
              style={styles.passwordInput}
              placeholder="Nhập lại mật khẩu"
              placeholderTextColor={Colors.textMuted}
              secureTextEntry={!showPassword}
              value={form.password_confirmation}
              onChangeText={(v) => updateField('password_confirmation', v)}
            />
            {renderPasswordToggle()}
          </View>

          <TouchableOpacity style={[styles.button, loading && styles.disabled]} onPress={handleSubmit} disabled={loading}>
            {loading ? <ActivityIndicator color={Colors.white} /> : <Text style={styles.buttonText}>Đăng ký</Text>}
          </TouchableOpacity>

          <TouchableOpacity onPress={() => navigation.goBack()} style={styles.secondaryButton}>
            <Text style={styles.secondaryText}>Đã có tài khoản? Đăng nhập</Text>
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
  passwordInputWrapper: {
    backgroundColor: Colors.background,
    borderColor: Colors.border,
    borderRadius: 12,
    borderWidth: 1,
    flexDirection: 'row',
    alignItems: 'center',
  },
  passwordInput: {
    flex: 1,
    color: Colors.text,
    fontSize: 15,
    paddingLeft: 16,
    paddingRight: 8,
    paddingVertical: 14,
  },
  passwordToggle: {
    width: 48,
    height: 48,
    alignItems: 'center',
    justifyContent: 'center',
  },
  button: { marginTop: 8, backgroundColor: Colors.primary, borderRadius: 12, alignItems: 'center', padding: 16 },
  disabled: { opacity: 0.7 },
  buttonText: { color: Colors.white, fontSize: 16, fontWeight: '800' },
  secondaryButton: { alignItems: 'center', padding: 12 },
  secondaryText: { color: Colors.primary, fontWeight: '700' },
});

export default RegisterScreen;
