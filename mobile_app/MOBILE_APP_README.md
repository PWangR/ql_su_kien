# Mobile App - Event Management System

Đây là phiên bản Mobile của Hệ thống Quản lý Sự kiện, xây dựng bằng React Native với Expo, kế thừa toàn bộ giao diện và chức năng của phiên bản Web.

## Tính Năng Chính

### 1. **Danh Sách Sự Kiện (Event List)**

- ✅ Hiển thị danh sách sự kiện dạng card với hình ảnh
- ✅ Tìm kiếm sự kiện theo tên, địa điểm
- ✅ Lọc theo trạng thái:
    - Sắp tổ chức
    - Đang diễn ra
    - Đã kết thúc
- ✅ Lọc theo loại/danh mục sự kiện
- ✅ Phân trang tự động (Infinite Scroll)
- ✅ Pull-to-Refresh để cập nhật

**Components:**

- `EventListScreen`: Màn hình chính hiển thị danh sách
- `EventCard`: Component card cho mỗi sự kiện
- `SearchBar`: Thanh tìm kiếm
- `EventFilters`: Bộ lọc trạng thái và danh mục

### 2. **Chi Tiết Sự Kiện (Event Detail)**

- ✅ Hiển thị hình ảnh sự kiện với status badge
- ✅ Hiển thị thông tin cơ bản:
    - Tiêu đề, mô tả, loại sự kiện
    - Thời gian diễn ra
    - Địa điểm tổ chức
    - Điểm thưởng
    - Số lượng người tham gia
- ✅ Thông tin người tổ chức
- ✅ Nút đăng ký/hủy đăng ký sự kiện
- ✅ Kiểm tra trạng thái đăng ký
- ✅ Nút chia sẻ sự kiện

**Component:**

- `EventDetailScreen`: Màn hình chi tiết sự kiện

### 3. **Quản Lý Thông Báo (Notifications)**

- ✅ Hiển thị danh sách thông báo
- ✅ Đánh dấu thông báo đã đọc
- ✅ Xóa thông báo
- ✅ Cập nhật thông báo chưa đọc

### 4. **Hồ Sơ Cá Nhân (Profile)**

- ✅ Hiển thị thông tin tài khoản
- ✅ Xem điểm thưởng tích luỹ
- ✅ Lịch sử tham gia sự kiện
- ✅ Đổi mật khẩu
- ✅ Cập nhật thông tin cá nhân
- ✅ Đăng xuất

### 5. **Quét Mã QR (QR Scanner)**

- ✅ Quét mã QR điểm danh sự kiện
- ✅ Đồng bộ hàng chờ offline
- ✅ Thông báo kết quả quét

### 6. **Xác Thực (Authentication)**

- ✅ Đăng nhập với email/mật khẩu
- ✅ Quản lý token tự động (Sanctum)
- ✅ Lưu token vào AsyncStorage
- ✅ Tự động khôi phục session

## Cấu Trúc Dự Án

```
mobile_app/
├── src/
│   ├── screens/              # Các màn hình chính
│   │   ├── LoginScreen.js
│   │   ├── EventListScreen.js      # ✨ NEW - Danh sách với lọc/tìm kiếm
│   │   ├── EventDetailScreen.js    # 🎨 Enhanced - Bố cục cải thiện
│   │   ├── ProfileScreen.js
│   │   ├── NotificationScreen.js
│   │   └── QRScannerScreen.js
│   ├── components/           # Các component tái sử dụng
│   │   ├── EventCard.js           # ✨ NEW - Card sự kiện
│   │   ├── EventFilters.js        # ✨ NEW - Bộ lọc
│   │   ├── SearchBar.js           # ✨ NEW - Thanh tìm kiếm
│   ├── services/             # API call & Services
│   │   └── api.js
│   ├── store/                # State management (Zustand)
│   │   ├── authStore.js
│   │   ├── queueStore.js
│   │   └── filterStore.js    # ✨ NEW - Quản lý trạng thái lọc
│   ├── navigation/           # React Navigation
│   │   └── AppNavigator.js
├── App.js
├── app.json
├── package.json
└── README.md
```

## Công Nghệ Sử Dụng

- **Framework**: React Native 0.83.2
- **CLI**: Expo 55.0.8
- **State Management**: Zustand 5.0.12
- **Navigation**: React Navigation 7.x
- **HTTP Client**: Axios 1.13.6
- **Storage**: AsyncStorage 2.2.0
- **Icons**: Expo Vector Icons
- **Camera**: Expo Camera (QR Scanner)

## Cài Đặt & Chạy

### Prerequisites

- Node.js >= 16
- npm hoặc yarn
- Expo CLI: `npm install -g expo-cli`

### Installation

```bash
cd mobile_app
npm install
# hoặc
yarn install
```

### Development

```bash
# Chạy ở web
npm run web

# Chạy ở Android
npm run android

# Chạy ở iOS
npm run ios

# Chạy Expo tương tác
npm start
```

## Cấu Hình API

Cập nhật URL API trong `src/services/api.js`:

```javascript
const BASE_URL = "http://192.168.1.211:8000/api";
```

**Thay thế `192.168.1.211` bằng địa chỉ IP của máy chủ PHP.**

## API Endpoints

### Public Routes

```
GET  /events                    # Danh sách sự kiện (có lọc/tìm)
GET  /events/{id}              # Chi tiết sự kiện
GET  /api/event-types          # Danh sách loại sự kiện
POST /login                    # Đăng nhập
```

**Query Parameters cho /events:**

- `search`: Tìm kiếm theo tên hoặc địa điểm
- `trang_thai`: Lọc theo trạng thái (sap_to_chuc, dang_dien_ra, da_ket_thuc)
- `loai`: Lọc theo loại sự kiện (ma_loai_su_kien)
- `page`: Trang hiện tại (mặc định: 1)
- `limit`: Số item trên trang (mặc định: 10)

### Protected Routes (Yêu cầu token)

```
GET    /user                   # Thông tin người dùng
GET    /user/profile           # Hồ sơ chi tiết
POST   /user/profile/update    # Cập nhật hồ sơ
POST   /user/change-password   # Đổi mật khẩu

POST   /registrations/{eventId}           # Đăng ký sự kiện
DELETE /registrations/{eventId}           # Hủy đăng ký
GET    /registrations/check/{eventId}     # Kiểm tra trạng thái đăng ký
GET    /registrations/history             # Lịch sử tham gia

GET    /notifications          # Danh sách thông báo
GET    /notifications/unread   # Thông báo chưa đọc
POST   /notifications/{id}/read # Đánh dấu đã đọc
POST   /notifications/read-all  # Đánh dấu tất cả đã đọc
DELETE /notifications/{id}     # Xóa thông báo

GET    /points/total          # Tổng điểm
GET    /points/history        # Lịch sử điểm
GET    /points/leaderboard    # Bảng xếp hạng

POST   /registrations/app-scan        # Quét QR điểm danh
POST   /registrations/app-scan-batch  # Quét QR hàng loạt
```

## Trạng Thái Quản Lý (State Management)

### AuthStore (useAuthStore)

```javascript
{
  token: string | null,
  user: object | null,
  isLoading: boolean,
  login(email, password),
  logout(),
  restoreToken()
}
```

### FilterStore (useFilterStore)

```javascript
{
  selectedStatus: string,    // sap_to_chuc, dang_dien_ra, da_ket_thuc
  selectedCategory: string,  // ma_loai_su_kien
  searchQuery: string,
  currentPage: number,
  setSelectedStatus(status),
  setSelectedCategory(category),
  setSearchQuery(query),
  setCurrentPage(page),
  clearFilters()
}
```

### QueueStore (useQueueStore)

```javascript
{
    queue: (array, addToQueue(action), removeFromQueue(id), syncQueue());
}
```

## Các Tính Năng Nổi Bật

### 1. Offline First Architecture

- Lưu hành động vào queue khi offline
- Tự động đồng bộ khi online
- AsyncStorage cho lưu trữ local

### 2. Responsive Design

- Tự động thích ứng với kích thước màn hình
- Layouts linh hoạt cho mobile, tablet
- Safe Area được xử lý tự động

### 3. Performance Optimization

- FlatList với removeClippedSubviews
- Memoization cho components
- Lazy loading với infinite scroll

### 4. State Persistence

- Lưu filter preferences
- Lưu auth token
- AsyncStorage persistence

## Gỡ Lỗi

### Lỗi Kết Nối API

1. Kiểm tra BASE_URL trong `src/services/api.js`
2. Đảm bảo máy chủ Laravel đang chạy
3. Kiểm tra firewall cho port 8000

### Lỗi Authentication

- Kiểm tra token expiry (Sanctum)
- Xóa AsyncStorage: `expo-cli run-web` > Clear Storage
- Login lại

### Lỗi Loading Component

- Kiểm tra ActivityIndicator import
- Đảm bảo dependencies cài đặt đầy đủ
- Chạy `npm install` lại

## Phát Triển Tiếp

### Planned Features

- [ ] Social sharing integration
- [ ] Push notifications (Firebase Cloud Messaging)
- [ ] Offline event details caching
- [ ] Event favorites/bookmarks
- [ ] Advanced filters (date range, distance)
- [ ] Event reviews & ratings
- [ ] Image gallery for events
- [ ] Map integration (Google Maps)

### Cải Thiện UI/UX

- [ ] Dark mode support
- [ ] Animated transitions
- [ ] Custom fonts
- [ ] Branded colors & theming
- [ ] Pull-to-refresh animations

## Troubleshooting

### Port 8000 is already in use

```bash
lsof -i :8000  # Mac/Linux
netstat -ano | findstr :8000  # Windows
kill -9 <PID>
```

### Expo cache issues

```bash
expo start --clear
```

### Dependencies install issues

```bash
rm -rf node_modules package-lock.json
npm install
```

## Contributors

- Frontend: React Native Development Team
- Backend: Laravel API Integration
- UI/UX Design: Mobile App Design Team

## License

Proprietary - Event Management System NTU

## Support

For issues and feature requests, contact the development team.

---

**Phiên bản**: 1.0.0  
**Cập nhật lần cuối**: 2024  
**Status**: ✅ Production Ready
