import { create } from 'zustand';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { persist } from 'zustand/middleware';

const useFilterStore = create(
  persist(
    (set) => ({
      selectedStatus: '',
      selectedCategory: '',
      searchQuery: '',
      currentPage: 1,

      setSelectedStatus: (status) => set({ selectedStatus: status, currentPage: 1 }),
      setSelectedCategory: (category) => set({ selectedCategory: category, currentPage: 1 }),
      setSearchQuery: (query) => set({ searchQuery: query, currentPage: 1 }),
      setCurrentPage: (page) => set({ currentPage: page }),
      clearFilters: () => set({ 
        selectedStatus: '', 
        selectedCategory: '', 
        searchQuery: '',
        currentPage: 1 
      }),
    }),
    {
      name: 'filter-store',
      storage: AsyncStorage,
    }
  )
);

export default useFilterStore;
