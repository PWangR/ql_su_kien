import React, { useEffect, useState, useCallback, useMemo, useRef } from 'react';
import { 
  StyleSheet, 
  Text, 
  View, 
  FlatList, 
  TouchableOpacity, 
  ActivityIndicator, 
  Dimensions, 
  ScrollView,
  RefreshControl,
  StatusBar,
  Platform
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import useAuthStore from '../store/authStore';
import useQueueStore from '../store/queueStore';
import Colors from '../constants/Colors';
import Typography from '../constants/Typography';
import EventCard from '../components/EventCard';
import RemoteImage from '../components/RemoteImage';

const { width } = Dimensions.get('window');

const HomeScreen = ({ navigation }) => {
  const [featured, setFeatured] = useState([]);
  const [loopedFeatured, setLoopedFeatured] = useState([]);
  const [latest, setLatest] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [activeCategory, setActiveCategory] = useState(null);
  const [currentSlide, setCurrentSlide] = useState(0);

  const carouselRef = useRef(null);
  const autoScrollTimer = useRef(null);

  const user = useAuthStore((state) => state.user);
  const logout = useAuthStore((state) => state.logout);
  const queue = useQueueStore((state) => state.queue);
  const syncQueue = useQueueStore((state) => state.syncQueue);
  const isSyncing = useQueueStore((state) => state.isSyncing);
  const isAdmin = ['admin', 'super_admin'].includes(user?.vai_tro);

  const fetchHomeData = async () => {
    try {
      const response = await api.get('/home');
      if (response.data.success) {
        const featuredData = response.data.data.featured;
        setFeatured(featuredData);
        // Create looped array for infinite carousel (5 items x3 for seamless loop)
        if (featuredData && featuredData.length > 0) {
          setLoopedFeatured([...featuredData, ...featuredData, ...featuredData]);
        }
        setLatest(response.data.data.latest);
        setCategories(response.data.data.categories);
      }
    } catch (error) {
      console.error('Lỗi khi tải dữ liệu trang chủ:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    fetchHomeData();
    const interval = setInterval(() => {
      syncQueue();
    }, 15000);
    return () => clearInterval(interval);
  }, []);

  // Remove auto-scroll - manual scroll only

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    fetchHomeData();
  }, []);

  const handleEventPress = useCallback((event) => {
    navigation.navigate('EventDetail', { eventId: event.ma_su_kien, event });
  }, [navigation]);

  const renderLatestEvent = useCallback(
    ({ item }) => (
      <View style={styles.latestItem}>
        <EventCard
          event={item}
          onPress={() => handleEventPress(item)}
        />
      </View>
    ),
    [handleEventPress]
  );

  const renderHero = () => (
    <View style={styles.heroSection}>
      <View style={styles.heroBadge}>
        <MaterialIcons name="school" size={14} color="rgba(255,255,255,0.7)" />
        <Text style={styles.heroBadgeText}>Khoa CNTT — ĐH Nha Trang</Text>
      </View>
      <Text style={[Typography.h1, styles.heroTitle]}>Khám phá Sự Kiện{"\n"}Của Khoa</Text>
      <Text style={[Typography.body, styles.heroSubtitle]}>
        Tham gia hội thảo, seminar, câu lạc bộ và tích lũy điểm rèn luyện.
      </Text>
      
      <View style={styles.heroActions}>
        <TouchableOpacity 
          style={styles.heroBtnPrimary}
          onPress={() => navigation.navigate('EventsList')}
        >
          <MaterialIcons name="calendar-today" size={18} color={Colors.white} />
          <Text style={styles.heroBtnText}>Xem tất cả</Text>
        </TouchableOpacity>
        
        <TouchableOpacity 
          style={styles.heroBtnOutline}
          onPress={() => navigation.navigate('QRScanner')}
        >
          <MaterialIcons name="qr-code-scanner" size={18} color={Colors.white} />
          <Text style={styles.heroBtnText}>{isAdmin ? 'Quét QR sinh viên' : 'Quét QR sự kiện'}</Text>
        </TouchableOpacity>
        
        <TouchableOpacity 
          style={[styles.heroBtnOutline, { borderColor: Colors.danger }]}
          onPress={logout}
        >
          <MaterialIcons name="logout" size={18} color={Colors.danger} />
        </TouchableOpacity>
      </View>

      {/* Featured Slider - Infinite Loop Carousel */}
      {loopedFeatured.length > 0 && (
        <View style={styles.carouselContainer}>
          <ScrollView 
            ref={carouselRef}
            horizontal 
            pagingEnabled 
            showsHorizontalScrollIndicator={false}
            snapToInterval={width - 48}
            decelerationRate="fast"
            contentContainerStyle={styles.carouselContent}
            scrollEventThrottle={16}
            onMomentumScrollEnd={(e) => {
              const position = e.nativeEvent.contentOffset.x;
              const index = Math.round(position / (width - 48));
              setCurrentSlide(index % (featured.length || 1));
            }}
          >
            {loopedFeatured.map((item, idx) => (
              <TouchableOpacity 
                key={`${item.ma_su_kien}-${idx}`}
                style={styles.slide}
                onPress={() => handleEventPress(item)}
                activeOpacity={0.9}
              >
                <RemoteImage
                  path={item.anh_su_kien}
                  style={styles.slideImage}
                  fallbackIcon="event"
                  iconColor="rgba(255,255,255,0.2)"
                  fallbackStyle={styles.slidePlaceholder}
                />
                <View style={styles.slideOverlay}>
                  <Text style={[Typography.h3, styles.slideTitle]} numberOfLines={2}>
                    {item.ten_su_kien}
                  </Text>
                  <Text style={styles.slideMeta}>
                    {new Date(item.thoi_gian_bat_dau).toLocaleDateString('vi-VN')} • {item.dia_diem}
                  </Text>
                </View>
              </TouchableOpacity>
            ))}
          </ScrollView>
          
          {/* Carousel Indicators */}
          {featured.length > 0 && (
            <View style={styles.carouselIndicators}>
              {featured.map((_, idx) => (
                <View 
                  key={idx}
                  style={[
                    styles.indicator,
                    currentSlide % featured.length === idx && styles.indicatorActive
                  ]}
                />
              ))}
            </View>
          )}
        </View>
      )}
    </View>
  );

  const renderCategoryFilter = () => (
    <View style={styles.filterSection}>
      <View style={styles.sectionHeader}>
        <Text style={[Typography.h2, { fontSize: 20 }]}>Sự kiện mới nhất</Text>
        <TouchableOpacity onPress={() => navigation.navigate('EventsList')}>
          <Text style={styles.seeAll}>Xem tất cả →</Text>
        </TouchableOpacity>
      </View>
      <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.categoriesList}>
        <TouchableOpacity 
          style={[styles.categoryTab, !activeCategory && styles.categoryTabActive]}
          onPress={() => setActiveCategory(null)}
        >
          <Text style={[styles.categoryTabText, !activeCategory && styles.categoryTabTextActive]}>Tất cả</Text>
        </TouchableOpacity>
        {categories.map((cat) => (
          <TouchableOpacity 
            key={cat.ma_loai_su_kien}
            style={[styles.categoryTab, activeCategory === cat.ma_loai_su_kien && styles.categoryTabActive]}
            onPress={() => setActiveCategory(cat.ma_loai_su_kien)}
          >
            <Text style={[styles.categoryTabText, activeCategory === cat.ma_loai_su_kien && styles.categoryTabTextActive]}>
              {cat.ten_loai}
            </Text>
          </TouchableOpacity>
        ))}
      </ScrollView>
    </View>
  );

  const filteredLatest = useMemo(
    () => (
      activeCategory
        ? latest.filter(ev => ev.ma_loai_su_kien === activeCategory)
        : latest
    ),
    [activeCategory, latest]
  );

  const listHeader = useMemo(
    () => (
      <>
        {renderHero()}
        {renderCategoryFilter()}
      </>
    ),
    [loopedFeatured, featured, currentSlide, categories, activeCategory, isAdmin]
  );

  return (
    <SafeAreaView style={styles.container} edges={['top', 'left', 'right']}>
      <StatusBar barStyle="light-content" />
      
      {loading && !refreshing ? (
        <View style={styles.loadingFull}>
          <ActivityIndicator size="large" color={Colors.primary} />
        </View>
      ) : (
        <FlatList
          data={filteredLatest}
          keyExtractor={(item) => item.ma_su_kien.toString()}
          ListHeaderComponent={listHeader}
          renderItem={renderLatestEvent}
          contentContainerStyle={styles.listContent}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={Colors.primary} />
          }
          ListEmptyComponent={
            <View style={styles.emptyBox}>
              <MaterialIcons name="event-busy" size={48} color={Colors.textMuted} style={{ opacity: 0.3 }} />
              <Text style={styles.emptyText}>Chưa có sự kiện nào trong mục này.</Text>
            </View>
          }
          removeClippedSubviews={Platform.OS === 'android'}
          initialNumToRender={5}
          maxToRenderPerBatch={6}
          windowSize={7}
          updateCellsBatchingPeriod={60}
        />
      )}

      {/* Floating Buttons */}
      <TouchableOpacity 
        style={styles.fabAi} 
        onPress={() => navigation.navigate('Chatbot')}
      >
        <MaterialIcons name="smart-toy" size={28} color={Colors.white} />
      </TouchableOpacity>

      {/* Queue Sync Bar */}
      {queue.length > 0 && (
        <TouchableOpacity 
          style={[styles.syncStatus, isSyncing && styles.syncStatusActive]} 
          onPress={syncQueue}
          disabled={isSyncing}
        >
          {isSyncing ? <ActivityIndicator size="small" color={Colors.primary} /> : <MaterialIcons name="sync" size={16} color={Colors.primary} />}
          <Text style={styles.syncText}>
            {isSyncing ? 'Đang gửi...' : `${queue.length} điểm danh chờ gửi (Nhấn để gửi)`}
          </Text>
        </TouchableOpacity>
      )}
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.background,
  },
  heroSection: {
    backgroundColor: '#0F172A', 
    paddingTop: 60,
    paddingBottom: 40,
    alignItems: 'center',
  },
  heroBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderWidth: 1,
    borderColor: 'rgba(255,255,255,0.2)',
    borderRadius: 20,
    marginBottom: 20,
    gap: 6,
  },
  heroBadgeText: {
    color: 'rgba(255,255,255,0.7)',
    fontSize: 10,
    fontWeight: '700',
    textTransform: 'uppercase',
    letterSpacing: 1,
  },
  heroTitle: {
    color: Colors.white,
    textAlign: 'center',
    marginBottom: 12,
  },
  heroSubtitle: {
    color: 'rgba(255,255,255,0.6)',
    textAlign: 'center',
    paddingHorizontal: 40,
    marginBottom: 24,
  },
  heroActions: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 40,
  },
  heroBtnPrimary: {
    backgroundColor: Colors.primary,
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 8,
    gap: 8,
  },
  heroBtnOutline: {
    borderWidth: 1,
    borderColor: 'rgba(255,255,255,0.3)',
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderRadius: 8,
    gap: 8,
  },
  heroBtnText: {
    color: Colors.white,
    fontWeight: '700',
    fontSize: 13,
  },
  carouselContainer: {
    width: '100%',
  },
  carouselContent: {
    paddingHorizontal: 24,
    gap: 16,
  },
  slide: {
    width: width - 64,
    height: 180,
    borderRadius: 12,
    overflow: 'hidden',
    position: 'relative',
    backgroundColor: '#000',
  },
  slideImage: {
    width: '100%',
    height: '100%',
    opacity: 0.6,
  },
  slidePlaceholder: {
    backgroundColor: '#111',
  },
  slideOverlay: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    padding: 16,
    backgroundColor: 'rgba(0,0,0,0.5)',
  },
  slideTitle: {
    color: Colors.white,
    fontSize: 16,
    marginBottom: 4,
  },
  slideMeta: {
    color: 'rgba(255,255,255,0.7)',
    fontSize: 11,
  },
  filterSection: {
    paddingTop: 32,
    paddingBottom: 16,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 24,
    marginBottom: 16,
  },
  seeAll: {
    color: Colors.primary,
    fontWeight: '700',
    fontSize: 13,
  },
  categoriesList: {
    paddingHorizontal: 24,
  },
  categoryTab: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    marginRight: 12,
    borderBottomWidth: 2,
    borderBottomColor: 'transparent',
  },
  categoryTabActive: {
    borderBottomColor: Colors.primary,
  },
  categoryTabText: {
    fontSize: 14,
    fontWeight: '600',
    color: Colors.textMuted,
  },
  categoryTabTextActive: {
    color: Colors.primary,
  },
  listContent: {
    paddingBottom: 40,
  },
  latestItem: {
    paddingHorizontal: 20,
    marginBottom: 16,
  },
  loadingFull: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyBox: {
    padding: 60,
    alignItems: 'center',
    gap: 12,
  },
  emptyText: {
    color: Colors.textMuted,
    textAlign: 'center',
  },
  fabAi: {
    position: 'absolute',
    bottom: 30,
    right: 20,
    backgroundColor: Colors.warning,
    width: 60,
    height: 60,
    borderRadius: 30,
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 8,
    shadowColor: Colors.warning,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 10,
  },
  syncStatus: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: 16,
    paddingVertical: 8,
    backgroundColor: Colors.white,
    borderRadius: 20,
    position: 'absolute',
    bottom: 110,
    alignSelf: 'center',
    borderWidth: 1,
    borderColor: Colors.borderLight,
    elevation: 4,
    shadowColor: Colors.black,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  syncStatusActive: {
    backgroundColor: Colors.primaryBg,
    borderColor: Colors.primary,
  },
  syncText: {
    marginLeft: 8,
    fontSize: 12,
    color: Colors.text,
    fontWeight: '700',
  },
  carouselIndicators: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 8,
    marginTop: 12,
    paddingHorizontal: 24,
  },
  indicator: {
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: 'rgba(255,255,255,0.4)',
  },
  indicatorActive: {
    backgroundColor: Colors.white,
    width: 24,
  },
});

export default HomeScreen;
