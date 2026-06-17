import { create } from 'zustand';
import AsyncStorage from '@react-native-async-storage/async-storage';
import api, { BASE_URL, setUnauthorizedHandler } from '../services/api';

const useAuthStore = create((set) => ({
  user: null,
  token: null,
  isLoading: true,

  restoreToken: async () => {
    try {
      const token = await AsyncStorage.getItem('userToken');
      const userStr = await AsyncStorage.getItem('userData');
      if (token && userStr) {
        try {
          const response = await api.get('/user');
          const user = response.data.data || response.data.user || JSON.parse(userStr);
          await AsyncStorage.setItem('userData', JSON.stringify(user));
          set({ token, user, isLoading: false });
        } catch (error) {
          await AsyncStorage.removeItem('userToken');
          await AsyncStorage.removeItem('userData');
          set({ token: null, user: null, isLoading: false });
        }
      } else {
        set({ isLoading: false });
      }
    } catch (e) {
      set({ isLoading: false });
    }
  },

  login: async (email, password) => {
    try {
      const response = await api.post('/login', { email, password });
      const { token, user } = response.data.data;

      await AsyncStorage.setItem('userToken', token);
      await AsyncStorage.setItem('userData', JSON.stringify(user));

      set({ token, user, isLoading: false });
      return { success: true };
    } catch (error) {
      const isNetworkError = !error.response;
      return {
        success: false,
        message: error.response?.data?.message || (
          isNetworkError
            ? `Không thể kết nối tới máy chủ: ${BASE_URL}. Hãy kiểm tra Laravel đang chạy và điện thoại cùng mạng Wi-Fi.`
            : 'Không thể kết nối tới máy chủ. Vui lòng thử lại.'
        ),
        errors: error.response?.data?.errors,
      };
    }
  },

  register: async (payload) => {
    try {
      const response = await api.post('/register', payload);
      return {
        success: response.data.success,
        message: response.data.message || 'Đăng ký thành công. Vui lòng kiểm tra email.',
      };
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Không thể đăng ký tài khoản.',
        errors: error.response?.data?.errors,
      };
    }
  },

  forgotPassword: async (email) => {
    try {
      const response = await api.post('/forgot-password', { email });
      return {
        success: response.data.success,
        message: response.data.message || 'Đã gửi email đặt lại mật khẩu.',
      };
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Không thể gửi email đặt lại mật khẩu.',
        errors: error.response?.data?.errors,
      };
    }
  },

  resendVerificationEmail: async (email) => {
    try {
      const response = await api.post('/email/resend', { email });
      return {
        success: response.data.success,
        message: response.data.message || 'Đã gửi lại email xác thực.',
      };
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Không thể gửi lại email xác thực.',
      };
    }
  },

  setUser: async (user) => {
    await AsyncStorage.setItem('userData', JSON.stringify(user));
    set({ user });
  },

  logout: async () => {
    try {
      await api.post('/logout');
    } catch (e) {
      // Ignore server-side logout failures and clear local state.
    } finally {
      await AsyncStorage.removeItem('userToken');
      await AsyncStorage.removeItem('userData');
      set({ token: null, user: null });
    }
  },
}));

setUnauthorizedHandler(() => {
  useAuthStore.setState({ token: null, user: null, isLoading: false });
});

export default useAuthStore;
