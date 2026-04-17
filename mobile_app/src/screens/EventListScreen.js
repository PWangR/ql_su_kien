import React, { useEffect, useState, useCallback } from 'react';
import {
  StyleSheet,
  View,
  FlatList,
  ActivityIndicator,
  Text,
  RefreshControl,
  SectionList,
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import useFilterStore from '../store/filterStore';
import useAuthStore from '../store/authStore';
import EventCard from '../components/EventCard';
import SearchBar from '../components/SearchBar';
import EventFilters from '../components/EventFilters';

const EventListScreen = ({ navigation }) => {
  const [events, setEvents] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [pagination, setPagination] = useState({
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
  });

  const selectedStatus = useFilterStore((state) => state.selectedStatus);
  const selectedCategory = useFilterStore((state) => state.selectedCategory);
  const searchQuery = useFilterStore((state) => state.searchQuery);
  const currentPage = useFilterStore((state) => state.currentPage);
  const setSelectedStatus = useFilterStore((state) => state.setSelectedStatus);
  const setSelectedCategory = useFilterStore((state) => state.setSelectedCategory);
  const setSearchQuery = useFilterStore((state) => state.setSearchQuery);
  const setCurrentPage = useFilterStore((state) => state.setCurrentPage);
  const clearFilters = useFilterStore((state) => state.clearFilters);

  const fetchCategories = useCallback(async () => {
    try {
      const response = await api.get('/api/event-types');
      if (response.data.success) {
        setCategories(response.data.data);
      }
    } catch (error) {
      console.error('Lỗi khi tải loại sự kiện:', error);
    }
  }, []);

  const fetchEvents = useCallback(async (page = 1) => {
    try {
      setLoading(true);
      const params = {
        page,
        limit: 10,
      };

      // Add search query if exists
      if (searchQuery) {
        params.search = searchQuery;
      }

      // Add status filter if selected
      if (selectedStatus) {
        params.trang_thai = selectedStatus;
      }

      // Add category filter if selected
      if (selectedCategory) {
        params.loai = selectedCategory;
      }

      const response = await api.get('/events', { params });

      if (response.data.success) {
        setEvents(response.data.data);
        setPagination(response.data.pagination);
      } else {
        setEvents([]);
      }
    } catch (error) {
      console.error('Lỗi khi tải sự kiện:', error);
      setEvents([]);
    } finally {
      setLoading(false);
    }
  }, [searchQuery, selectedStatus, selectedCategory]);

  const onRefresh = useCallback(async () => {
    setRefreshing(true);
    await fetchEvents(1);
    setRefreshing(false);
  }, [fetchEvents]);

  useEffect(() => {
    fetchCategories();
  }, [fetchCategories]);

  useEffect(() => {
    fetchEvents(currentPage);
  }, [searchQuery, selectedStatus, selectedCategory, fetchEvents, currentPage]);

  const handleSearch = useCallback((query) => {
    setSearchQuery(query);
  }, [setSearchQuery]);

  const handleClearSearch = useCallback(() => {
    setSearchQuery('');
  }, [setSearchQuery]);

  const handleStatusChange = useCallback((status) => {
    setSelectedStatus(status);
  }, [setSelectedStatus]);

  const handleCategoryChange = useCallback((category) => {
    setSelectedCategory(category);
  }, [setSelectedCategory]);

  const handleClearFilters = useCallback(() => {
    clearFilters();
  }, [clearFilters]);

  const handleLoadMore = useCallback(() => {
    if (currentPage < pagination.last_page) {
      setCurrentPage(currentPage + 1);
    }
  }, [currentPage, pagination.last_page, setCurrentPage]);

  const handleEventPress = useCallback((event) => {
    navigation.navigate('EventDetail', { eventId: event.ma_su_kien, event });
  }, [navigation]);

  const renderEvent = useCallback(
    ({ item }) => (
      <EventCard
        event={item}
        onPress={() => handleEventPress(item)}
      />
    ),
    [handleEventPress]
  );

  const renderListHeader = () => (
    <View style={styles.header}>
      <View style={styles.headerContent}>
        <Text style={styles.headerTitle}>Sự kiện</Text>
        <Text style={styles.headerSubtitle}>
          Khám phá và đăng ký tham gia các sự kiện
        </Text>
      </View>
    </View>
  );

  const renderFooter = () => {
    if (!loading && pagination.current_page >= pagination.last_page) {
      return null;
    }

    return (
      <View style={styles.footerContainer}>
        {loading && pagination.current_page > 1 && (
          <ActivityIndicator size="small" color="#007bff" />
        )}
      </View>
    );
  };

  const renderEmpty = () => (
    <View style={styles.emptyContainer}>
      <MaterialIcons
        name="event-busy"
        size={64}
        color="#ccc"
        style={styles.emptyIcon}
      />
      <Text style={styles.emptyText}>Không tìm thấy sự kiện nào.</Text>
      {(searchQuery || selectedStatus || selectedCategory) && (
        <Text style={styles.emptySubtext}>
          Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm
        </Text>
      )}
    </View>
  );

  return (
    <View style={styles.container}>
      <FlatList
        data={events}
        renderItem={renderEvent}
        keyExtractor={(item) => item.ma_su_kien.toString()}
        contentContainerStyle={styles.listContent}
        ListHeaderComponent={
          <>
            {renderListHeader()}
            <SearchBar
              placeholder="Tìm kiếm sự kiện..."
              onSearch={handleSearch}
              onClear={handleClearSearch}
            />
            <EventFilters
              categories={categories}
              selectedStatus={selectedStatus}
              selectedCategory={selectedCategory}
              onStatusChange={handleStatusChange}
              onCategoryChange={handleCategoryChange}
              onClearFilters={handleClearFilters}
            />
          </>
        }
        ListEmptyComponent={!loading && renderEmpty()}
        ListFooterComponent={renderFooter()}
        onEndReached={handleLoadMore}
        onEndReachedThreshold={0.5}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            colors={['#007bff']}
            tintColor="#007bff"
          />
        }
        scrollEventThrottle={16}
        removeClippedSubviews={true}
      />

      {loading && events.length === 0 && (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color="#007bff" />
        </View>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  listContent: {
    paddingBottom: 20,
  },
  header: {
    backgroundColor: '#fff',
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#e9ecef',
  },
  headerContent: {
    marginBottom: 8,
  },
  headerTitle: {
    fontSize: 26,
    fontWeight: '700',
    color: '#212529',
    marginBottom: 4,
  },
  headerSubtitle: {
    fontSize: 13,
    color: '#6c757d',
    lineHeight: 18,
  },
  footerContainer: {
    paddingVertical: 16,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyContainer: {
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 80,
    paddingHorizontal: 16,
  },
  emptyIcon: {
    marginBottom: 16,
    opacity: 0.5,
  },
  emptyText: {
    fontSize: 16,
    fontWeight: '500',
    color: '#6c757d',
    marginBottom: 8,
    textAlign: 'center',
  },
  emptySubtext: {
    fontSize: 13,
    color: '#adb5bd',
    textAlign: 'center',
  },
  loadingContainer: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(255, 255, 255, 0.8)',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 1000,
  },
});

export default EventListScreen;
