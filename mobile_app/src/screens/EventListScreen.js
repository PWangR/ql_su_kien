import React, { useEffect, useState, useCallback } from 'react';
import {
  StyleSheet,
  View,
  FlatList,
  ActivityIndicator,
  Text,
  RefreshControl,
  SectionList,
  Platform,
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import useFilterStore from '../store/filterStore';
import useAuthStore from '../store/authStore';
import EventCard from '../components/EventCard';
import SearchBar from '../components/SearchBar';
import EventFilters from '../components/EventFilters';
import Colors from '../constants/Colors';

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
          Khám phá và đăng ký tham gia các sự kiện mới nhất
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
          <ActivityIndicator size="small" color={Colors.primary} />
        )}
      </View>
    );
  };

  const renderEmpty = () => (
    <View style={styles.emptyContainer}>
      <MaterialIcons
        name="event-busy"
        size={80}
        color={Colors.border}
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
            <View style={styles.listSectionHeader}>
              <Text style={styles.listSectionTitle}>Danh sách sự kiện</Text>
            </View>
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
            colors={[Colors.primary]}
            tintColor={Colors.primary}
          />
        }
        scrollEventThrottle={16}
        removeClippedSubviews={Platform.OS === 'android'}
        initialNumToRender={6}
        maxToRenderPerBatch={10}
        windowSize={10}
        updateCellsBatchingPeriod={50}
      />

      {loading && events.length === 0 && (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color={Colors.primary} />
        </View>
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.background,
  },
  listContent: {
    paddingBottom: 40,
  },
  header: {
    backgroundColor: Colors.white,
    paddingHorizontal: 20,
    paddingTop: 24,
    paddingBottom: 16,
  },
  headerContent: {
    marginBottom: 4,
  },
  headerTitle: {
    fontSize: 28,
    fontWeight: '800',
    color: Colors.text,
    marginBottom: 6,
    letterSpacing: -0.5,
  },
  headerSubtitle: {
    fontSize: 14,
    color: Colors.textMuted,
    lineHeight: 20,
  },
  listSectionHeader: {
    paddingHorizontal: 20,
    paddingTop: 24,
    paddingBottom: 12,
  },
  listSectionTitle: {
    fontSize: 15,
    fontWeight: '700',
    color: Colors.text,
    textTransform: 'uppercase',
    letterSpacing: 1,
  },
  footerContainer: {
    paddingVertical: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyContainer: {
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 100,
    paddingHorizontal: 32,
  },
  emptyIcon: {
    marginBottom: 20,
    opacity: 0.5,
  },
  emptyText: {
    fontSize: 18,
    fontWeight: '700',
    color: Colors.textMuted,
    marginBottom: 10,
    textAlign: 'center',
  },
  emptySubtext: {
    fontSize: 14,
    color: Colors.textMuted,
    textAlign: 'center',
    lineHeight: 20,
  },
  loadingContainer: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(238, 242, 246, 0.8)',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 1000,
  },
});

export default EventListScreen;

