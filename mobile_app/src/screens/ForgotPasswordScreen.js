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
import useAuthStore from '../store/authStore';
import Colors from '../constants/Colors';

const ForgotPasswordScreen = ({ navigation }) => {
  const forgotPassword = useAuthStore((state) => state.forgotPassword);
  const resendVerificationEmail = useAuthStore((state) => state.resendVerificationEmail);
  const [email, setEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const [resending, setResending] = useState(false);

  const sendReset = async () => {
    if (!email) {
      Alert.alert('Thong bao', 'Vui long nhap email.');
      return;
    }

    setLoading(true);
    const result = await forgotPassword(email);
    setLoading(false);
    Alert.alert(result.success ? 'Da gui email' : 'Khong thanh cong', result.message);
  };

  const resendVerification = async () => {
    if (!email) {
      Alert.alert('Thong bao', 'Vui long nhap email.');
      return;
    }

    setResending(true);
    const result = await resendVerificationEmail(email);
    setResending(false);
    Alert.alert(result.success ? 'Da gui email' : 'Khong thanh cong', result.message);
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.content}>
        <Text style={styles.title}>Ho tro tai khoan</Text>
        <Text style={styles.subtitle}>Nhap email de dat lai mat khau hoac gui lai email xac thuc.</Text>

        <TextInput
          style={styles.input}
          placeholder="Email"
          value={email}
          onChangeText={setEmail}
          keyboardType="email-address"
          autoCapitalize="none"
        />

        <TouchableOpacity style={styles.button} onPress={sendReset} disabled={loading}>
          {loading ? <ActivityIndicator color={Colors.white} /> : <Text style={styles.buttonText}>Gui link dat lai mat khau</Text>}
        </TouchableOpacity>

        <TouchableOpacity style={styles.outlineButton} onPress={resendVerification} disabled={resending}>
          {resending ? <ActivityIndicator color={Colors.primary} /> : <Text style={styles.outlineText}>Gui lai email xac thuc</Text>}
        </TouchableOpacity>

        <TouchableOpacity onPress={() => navigation.goBack()} style={styles.backButton}>
          <Text style={styles.backText}>Quay lai dang nhap</Text>
        </TouchableOpacity>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.white },
  content: { flex: 1, justifyContent: 'center', padding: 24 },
  title: { fontSize: 30, fontWeight: '900', color: Colors.primary },
  subtitle: { color: Colors.textMuted, marginTop: 8, marginBottom: 28, lineHeight: 20 },
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
  button: { marginTop: 18, backgroundColor: Colors.primary, borderRadius: 12, alignItems: 'center', padding: 16 },
  buttonText: { color: Colors.white, fontSize: 15, fontWeight: '800' },
  outlineButton: { marginTop: 12, borderColor: Colors.primary, borderWidth: 1, borderRadius: 12, alignItems: 'center', padding: 16 },
  outlineText: { color: Colors.primary, fontSize: 15, fontWeight: '800' },
  backButton: { alignItems: 'center', padding: 16 },
  backText: { color: Colors.textMuted, fontWeight: '700' },
});

export default ForgotPasswordScreen;
