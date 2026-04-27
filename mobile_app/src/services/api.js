import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Đường dẫn cơ sở của máy chủ (có thể thay đổi tùy theo môi trường test)
export const BASE_URL = 'http://192.168.1.211:8000';

const api = axios.create({
  baseURL: `${BASE_URL}/api`,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Interceptor để tự động gắn Token vào header nếu đã đăng nhập
api.interceptors.request.use(
  async (config) => {
    const token = await AsyncStorage.getItem('userToken');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default api;

