import { create } from 'zustand';
import AsyncStorage from '@react-native-async-storage/async-storage';
import api from '../services/api';

const useAuthStore = create((set) => ({
  user: null,
  token: null,
  isLoading: true,

  // Kiểm tra trạng thái đăng nhập khi mở app
  restoreToken: async () => {
    try {
      const token = await AsyncStorage.getItem('userToken');
      const userStr = await AsyncStorage.getItem('userData');
      if (token && userStr) {
        set({ token, user: JSON.parse(userStr), isLoading: false });
      } else {
        set({ isLoading: false });
      }
    } catch (e) {
      set({ isLoading: false });
    }
  },

  // Hàm đăng nhập
  login: async (email, password) => {
    try {
      const response = await api.post('/login', { email, password });
      // Dữ liệu trả về từ Laravel nằm trong response.data.data
      const { token, user } = response.data.data;
      
      await AsyncStorage.setItem('userToken', token);
      await AsyncStorage.setItem('userData', JSON.stringify(user));
      
      set({ token, user, isLoading: false });
      return { success: true };
    } catch (error) {
      console.log('Login Error:', error.response?.data || error.message);
      return { 
        success: false, 
        message: error.response?.data?.message || 'Không thể kết nối tới máy chủ. Vui lòng kiểm tra mạng.' 
      };
    }
  },

  // Hàm đăng xuất
  logout: async () => {
    try {
      await api.post('/logout');
    } catch (e) {
      // Bỏ qua lỗi logout phía server nếu có
    } finally {
      await AsyncStorage.removeItem('userToken');
      await AsyncStorage.removeItem('userData');
      set({ token: null, user: null });
    }
  },
}));

export default useAuthStore;
