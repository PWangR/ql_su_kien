import { create } from 'zustand';
import { persist, createJSONStorage } from 'zustand/middleware';
import AsyncStorage from '@react-native-async-storage/async-storage';
import api from '../services/api';

const useQueueStore = create(
  persist(
    (set, get) => ({
      queue: [],
      isSyncing: false,

      // Thêm vào hàng chờ
      enqueue: (data) => set((state) => ({
        queue: [...state.queue, { ...data, id: Date.now().toString() }]
      })),

      // Bắt đầu quá trình đồng bộ batch
      syncQueue: async () => {
        const { queue, isSyncing } = get();
        
        if (isSyncing || queue.length === 0) return;

        set({ isSyncing: true });

        try {
          // Chỉ gửi lên các ma_su_kien cho ngắn gọn, vì payload batch 
          // có thể có cấu trúc: { events: [{ma_su_kien: 1}, {ma_su_kien: 2}] }
          const batchPayload = {
            events: queue.map(item => ({
              ma_su_kien: item.ma_su_kien,
              action: item.action || 'diem_danh',
              ma_sinh_vien: item.ma_sinh_vien || null
            }))
          };

          const response = await api.post('/registrations/app-scan-batch', batchPayload);

          if (response.data.success) {
            // Thành công -> Xóa hàng chờ
            set({ queue: [] });
            console.log('Đồng bộ hàng chờ thành công!');
          }
        } catch (error) {
          console.error('Lỗi khi đồng bộ hàng chờ điểm danh:', error.message);
          // Không xóa queue nếu bị lỗi mạng
        } finally {
          set({ isSyncing: false });
        }
      },
      
      clearQueue: () => set({ queue: [] })
    }),
    {
      name: 'attendance-queue-storage',
      storage: createJSONStorage(() => AsyncStorage),
    }
  )
);

export default useQueueStore;
