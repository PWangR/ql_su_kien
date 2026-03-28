# 📋 Dự Án Quản Lý Sự Kiện NTU - Tài Liệu Tổng Hợp

**Phiên bản:** 1.0  
**Ngày cập nhật:** 28/03/2026  
**Tác giả:** Khoa CNTT - Đại học Nha Trang

---

## 🎯 Mục Đích Dự Án

Dự án **QL_SU_KIEN** (Quản Lý Sự Kiện NTU) là một **ứng dụng web quản lý sự kiện toàn diện** dành cho sinh viên và cán bộ Khoa CNTT, Đại học Nha Trang.

### ✨ Chức Năng Chính

| Đối tượng         | Chức năng                                                              |
| ----------------- | ---------------------------------------------------------------------- |
| **Sinh viên**     | Đăng ký sự kiện, theo dõi điểm, xem lịch sử tham gia, bầu cử           |
| **Quản trị viên** | Tạo/chỉnh sửa/xóa sự kiện, quản lý người dùng, thống kê, cộng/trừ điểm |
| **Hệ thống**      | Thông báo real-time, QR check-in, bảng xếp hạng, bầu cử trực tuyến     |

---

## 🔧 Công Nghệ Sử Dụng

### Backend Stack

| Thành phần          | Công nghệ             | Phiên bản |
| ------------------- | --------------------- | --------- |
| Framework           | **Laravel**           | 11+       |
| PHP                 | **PHP-FPM**           | 8.2+      |
| Database            | **MySQL**             | 8.0       |
| Authentication      | **Laravel Sanctum**   | 3.3+      |
| Excel Import/Export | **Maatwebsite Excel** | 3.1+      |
| QR Code             | **SimpleQRCode**      | 4.2+      |
| Caching             | **Redis**             | 7.0+      |
| Web Server          | **Nginx**             | 1.24      |

### Frontend Stack

| Thành phần    | Công nghệ         | Phiên bản |
| ------------- | ----------------- | --------- |
| Build Tool    | **Vite**          | 5.0+      |
| CSS Framework | **Tailwind CSS**  | 3.3+      |
| CSS Plugins   | Forms, Typography | -         |
| HTTP Client   | **Axios**         | -         |
| Testing       | Jest              | -         |

### Infrastructure

| Thành phần        | Công nghệ                                     |
| ----------------- | --------------------------------------------- |
| Containerization  | **Docker** + **Docker Compose**               |
| Version Control   | **Git**                                       |
| Testing Framework | **PHPUnit** 10                                |
| Code Style        | **Laravel Pint**                              |
| Error Handling    | **Spatie Laravel Ignition**                   |
| Broadcasting      | **Laravel Broadcasting** (Pusher/Redis ready) |

---

## 📦 Cấu Trúc Database

### Core Models (13 mô hình)

```
Người Dùng & Vai Trò
├── User (nguoi_dung) - Người dùng hệ thống
└── vai_tro: admin, sinh_vien

Quản Lý Sự Kiện
├── SuKien (su_kien) - Sự kiện chính
├── LoaiSuKien (loai_su_kien) - Danh mục sự kiện
├── DangKy (dang_ky) - Đăng ký tham gia
└── ThuVienDaPhuongTien (thu_vien_da_phuong_tien) - Ảnh/Video/Tài liệu

Hệ Thống Điểm
├── LichSuDiem (lich_su_diem) - Lịch sử điểm
└── nguon: tham_gia_su_kien, phat_tru, ...

Thông Báo
└── ThongBao (thong_bao) - Thông báo tức thì

Bầu Cử Trực Tuyến
├── BauCu (bau_cu) - Cuộc bầu cử
├── UngCuVien (ung_cu_vien) - Ứng cử viên
├── CuTri (cu_tri) - Cử tri
├── PhieuBau (phieu_bau) - Phiếu bầu
└── ChiTietPhieuBau (chi_tiet_phieu_bau) - Chi tiết phiếu bầu

Khác
└── MauBaiDang (mau_bai_dang) - Mẫu bài đăng
```

### Quan Hệ Chính

```
SuKien
  ├─→ (1:N) DangKy
  │     └─→ (N:1) User
  ├─→ (1:N) LichSuDiem
  ├─→ (1:N) ThuVienDaPhuongTien
  └─→ (N:1) LoaiSuKien

BauCu
  ├─→ (1:N) UngCuVien
  ├─→ (1:N) PhieuBau
  │     └─→ (1:N) ChiTietPhieuBau
  └─→ (1:N) CuTri
```

---

## 🔌 API Endpoints

Hệ thống cung cấp **40+ REST API endpoints** được phân chia theo phân quyền:

### 1️⃣ Public Routes (Không cần đăng nhập)

```
GET    /api/events              # Danh sách toàn bộ sự kiện
GET    /api/events/{id}         # Chi tiết một sự kiện
GET    /api/events/search/{keyword}  # Tìm kiếm sự kiện
POST   /api/login               # Đăng nhập
POST   /api/logout              # Đăng xuất
```

### 2️⃣ Protected Routes (Yêu cầu auth token)

**User Profile:**

```
GET    /api/user                # Thông tin user hiện tại
GET    /api/user/profile        # Thông tin profile chi tiết
POST   /api/user/profile/update # Cập nhật profile
POST   /api/user/change-password # Đổi mật khẩu
```

**Event Registration:**

```
POST   /api/registrations/{eventId}      # Đăng ký sự kiện
DELETE /api/registrations/{eventId}      # Hủy đăng ký
GET    /api/registrations/history        # Lịch sử đăng ký
```

**Notifications (8 endpoints):**

```
GET    /api/notifications           # Danh sách thông báo
GET    /api/notifications/unread    # Chỉ thông báo chưa đọc
POST   /api/notifications/{id}/read # Đánh dấu một thông báo
POST   /api/notifications/read-all  # Đánh dấu tất cả
DELETE /api/notifications/{id}      # Xóa thông báo
```

**Points (3 endpoints):**

```
GET    /api/points/total            # Tổng điểm hiện tại
GET    /api/points/history          # Lịch sử cộng/trừ điểm
GET    /api/points/leaderboard      # Bảng xếp hạng sinh viên
```

### 3️⃣ Admin Only Routes (Quyền quản trị viên)

**Event Management:**

```
POST   /api/admin/events              # Tạo sự kiện mới
PUT    /api/admin/events/{id}         # Cập nhật sự kiện
DELETE /api/admin/events/{id}         # Xóa sự kiện
```

**User Management:**

```
GET    /api/admin/users               # Danh sách người dùng
POST   /api/admin/users               # Tạo người dùng mới
PUT    /api/admin/users/{id}          # Cập nhật thông tin user
DELETE /api/admin/users/{id}          # Xóa người dùng
POST   /api/admin/users/{id}/lock     # Khóa tài khoản
POST   /api/admin/users/{id}/unlock   # Mở khóa tài khoản
```

**Registration Management:**

```
GET    /api/admin/registrations              # Danh sách đăng ký
PUT    /api/admin/registrations/{id}         # Cập nhật trạng thái
GET    /api/admin/events/{eventId}/participants  # Danh sách tham gia
```

**Points Management:**

```
POST   /api/admin/points/add              # Cộng điểm
POST   /api/admin/points/subtract         # Trừ điểm
GET    /api/admin/points/statistics       # Thống kê điểm
```

**Media Management:**

```
GET    /api/media                # Danh sách media
POST   /api/media                # Upload file
DELETE /api/media/{id}           # Xóa file
```

**Statistics:**

```
GET    /api/admin/statistics/events        # Thống kê sự kiện
GET    /api/admin/statistics/users         # Thống kê người dùng
GET    /api/admin/statistics/dashboard     # Dashboard tổng hợp
```

---

## 📁 Cấu Trúc Thư Mục

```
ql_su_kien/
│
├── 📄 app/
│   ├── Http/
│   │   ├── Controllers/Api/          → 7 API Controllers (EventController, ...)
│   │   ├── Middleware/               → Auth, Admin, Role-based middleware
│   │   └── Requests/                 → 6 Form Validation classes
│   │
│   ├── Models/                       → 13 Eloquent Models
│   │   ├── User.php                  → Người dùng
│   │   ├── SuKien.php                → Sự kiện
│   │   ├── DangKy.php                → Đăng ký
│   │   ├── LichSuDiem.php            → Lịch sử điểm
│   │   ├── ThongBao.php              → Thông báo
│   │   ├── BauCu.php                 → Bầu cử
│   │   ├── PhieuBau.php              → Phiếu bầu
│   │   ├── UngCuVien.php             → Ứng cử viên
│   │   ├── CuTri.php                 → Cử tri
│   │   └── ... (6 Model khác)
│   │
│   ├── Services/                     → Business Logic (40+ methods)
│   │   ├── EventService.php          → Quản lý sự kiện
│   │   ├── PointService.php          → Quản lý điểm
│   │   ├── NotificationService.php   → Quản lý thông báo
│   │   └── ... (3 Service khác)
│   │
│   ├── Imports/                      → Excel Import
│   │   ├── CuTriImport.php
│   │   ├── NguoiDungImport.php
│   │   ├── SuKienImport.php
│   │   └── UngCuVienImport.php
│   │
│   ├── Traits/                       → Reusable code blocks
│   ├── Notifications/                → Notification classes
│   └── Exceptions/                   → Custom Exceptions
│
├── 📄 database/
│   ├── migrations/                   → 17 migration files
│   │   ├── create_users_table.php
│   │   ├── create_su_kien_table.php
│   │   ├── create_dang_ky_table.php
│   │   ├── create_lich_su_diem_table.php
│   │   ├── create_bau_cu_table.php
│   │   └── ... (12 migrations khác)
│   │
│   ├── seeders/                      → Database seeders
│   └── factories/                    → Model factories
│
├── 📄 resources/
│   ├── views/                        → Blade templates
│   │   ├── dashboard.blade.php
│   │   ├── events/
│   │   │   ├── index.blade.php
│   │   │   ├── show.blade.php
│   │   │   └── create.blade.php
│   │   └── admin/
│   │
│   ├── css/
│   │   ├── app.css                   → Main styles
│   │   └── loading.css               → Loading animation
│   │
│   └── js/
│       ├── app.js                    → Main JS entry
│       ├── bootstrap.js              → Bootstrap config
│       └── modules/                  → JS modules
│
├── 📄 routes/
│   ├── api.php                       → API routes (40+)
│   ├── web.php                       → Web routes
│   └── console.php                   → Console commands
│
├── 📄 config/
│   ├── app.php                       → App settings
│   ├── auth.php                      → Authentication
│   ├── database.php                  → Database
│   ├── filesystems.php               → File storage
│   ├── queue.php                     → Queue jobs
│   ├── cache.php                     → Caching
│   ├── mail.php                      → Mail
│   ├── logging.php                   → Logging
│   └── cors.php                      → CORS settings
│
├── 📄 storage/
│   ├── app/                          → Uploaded files
│   ├── framework/                    → Cache, sessions
│   └── logs/                         → Application logs
│
├── 📄 tests/
│   ├── Feature/                      → Feature tests
│   ├── Unit/                         → Unit tests
│   └── TestCase.php                  → Test base class
│
├── 📄 docker/
│   └── nginx/
│       └── default.conf              → Nginx config
│
├── 📄 public/
│   ├── index.php                     → Entry point
│   ├── storage                       → Symbolic link
│   └── robots.txt
│
├── Dockerfile                        → Docker image definition
├── docker-compose.yml                → Multi-container setup
├── composer.json                     → PHP dependencies
├── package.json                      → Node dependencies
├── vite.config.js                    → Vite build config
├── tailwind.config.js                → Tailwind CSS config
├── postcss.config.js                 → PostCSS config
├── phpunit.xml                       → PHPUnit config
│
└── 📚 Documentation
    ├── README.md                     → Project overview
    ├── README_NEW.md                 → Updated README
    ├── QUICKSTART.md                 → Quick setup guide
    ├── SETUP.md                      → Detailed setup
    ├── COMPLETED_IMPROVEMENTS.md     → Improvements done
    └── doc/                          → Additional docs
        ├── event_system_workflows.md
        ├── frontend_requirements_event_system.md
        └── thiet_ke_co_so_du_lieu_quan_ly_su_kien_tieng_viet.md
```

---

## ✨ Các Features Chính

### 1. 🔐 Authentication & Authorization

- ✅ Đăng nhập/Đăng xuất với API tokens (Laravel Sanctum)
- ✅ Phân quyền role-based (Admin/Sinh viên)
- ✅ Password mã hóa bcrypt
- ✅ Hệ thống khóa tài khoản
- ✅ Hỗ trợ xác minh email
- ✅ Session management

### 2. 📅 Quản Lý Sự Kiện

- ✅ CRUD đầy đủ (Tạo, Đọc, Cập nhật, Xóa)
- ✅ Phân loại sự kiện (LoaiSuKien)
- ✅ Theo dõi trạng thái sự kiện
    - Sắp tổ chức
    - Đang diễn ra
    - Đã kết thúc
    - Hủy
- ✅ Quản lý số lượng người tham gia (tối đa)
- ✅ Upload ảnh sự kiện
- ✅ QR Code generation cho check-in
- ✅ Cập nhật real-time

### 3. 👥 Quản Lý Người Dùng

- ✅ Đăng ký/Cập nhật profile
- ✅ Upload ảnh đại diện
- ✅ Quản lý tài khoản
- ✅ Khóa/Mở khóa tài khoản
- ✅ Thống kê người dùng
- ✅ Đổi mật khẩu

### 4. 📊 Hệ Thống Điểm

- ✅ Tự động cộng điểm khi tham gia sự kiện
- ✅ Theo dõi lịch sử điểm chi tiết
- ✅ Bảng xếp hạng (Leaderboard)
- ✅ Quản trị viên có thể cộng/trừ điểm
- ✅ Nhiều nguồn điểm (tham gia, phạt trừ, ...)
- ✅ Thống kê chi tiết điểm

### 5. 🔔 Thông Báo Real-time

- ✅ Thông báo sự kiện sắp diễn ra
- ✅ Cập nhật điểm
- ✅ Thông báo hệ thống
- ✅ Đếm thông báo chưa đọc
- ✅ Lịch sử thông báo
- ✅ Đánh dấu đã đọc

### 6. 📸 Quản Lý Media

- ✅ Upload ảnh sự kiện
- ✅ Lưu trữ tài liệu
- ✅ Quản lý video
- ✅ Phân loại file (hình ảnh/video/tài liệu)
- ✅ Tự động xóa khi model bị xóa
- ✅ Tối ưu hóa file size

### 7. 🗳️ Bầu Cử Trực Tuyến

- ✅ Tạo cuộc bầu cử
- ✅ Quản lý ứng cử viên
- ✅ Đăng ký cử tri
- ✅ Đẩy phiếu bầu
- ✅ Theo dõi kết quả real-time
- ✅ Báo cáo chi tiết
- ✅ Các tùy chọn bầu cử linh hoạt

### 8. 📈 Analytics & Reports

- ✅ Thống kê sự kiện (số lượng, tham gia, ...)
- ✅ Thống kê người dùng (tổng, hoạt động, ...)
- ✅ Phân tích điểm
- ✅ Dashboard quản trị viên
- ✅ Báo cáo chi tiết theo sự kiện
- ✅ Export dữ liệu

### 9. 💾 Import/Export

- ✅ Import người dùng từ Excel
- ✅ Import danh sách cử tri
- ✅ Import ứng cử viên
- ✅ Import sự kiện
- ✅ Export kết quả

---

## 🐳 Docker Setup

### Services

```yaml
Services:
  app         # PHP 8.2-FPM container
  db          # MySQL 8.0 database
  nginx       # Web server (port 80, 443)
  redis       # Cache & queue (port 6379)

Network: ql-su-kien-net (bridge)
Volumes: dbdata (persistent MySQL data)
```

### Bắt Đầu Nhanh

```bash
# Clone dự án
git clone <repository-url> ql_su_kien
cd ql_su_kien

# Start services
docker-compose up -d

# Chạy migration
docker-compose exec app php artisan migrate

# Seed database (nếu cần)
docker-compose exec app php artisan db:seed

# Xem logs
docker-compose logs -f app

# Stop services
docker-compose down
```

### Truy Cập

- **Web Application:** http://localhost
- **MySQL:** localhost:3306 (port từ docker-compose)
- **Redis:** localhost:6379

---

## ⚙️ Configuration Quan Trọng

### Frontend Configuration

**[tailwind.config.js](tailwind.config.js)**

- Cấu hình colors, fonts, spacing
- Plugins: Forms, Typography
- Dark mode support

**[postcss.config.js](postcss.config.js)**

- Tailwind CSS plugin
- Autoprefixer

**[vite.config.js](vite.config.js)**

- Input: `resources/css/app.css`, `resources/js/app.js`
- Output: `public/build/`
- Laravel Vite Plugin cho hot reload
- HMR settings

### Backend Configuration

**[config/app.php](config/app.php)**

- Timezone (Asia/Ho_Chi_Minh)
- Locale (vi)
- Service providers
- Aliases

**[config/database.php](config/database.php)**

- Default connection: mysql
- Host: từ `.env`
- Database: ql_su_kien
- Charset: utf8mb4

**[config/auth.php](config/auth.php)**

- Default guard: sanctum
- Password hashing: bcrypt
- API token expiration

**[config/filesystems.php](config/filesystems.php)**

- Default disk: local
- Storage path: storage/app
- Public URL: /storage

**Environment Variables (.env)**

```env
APP_NAME="QL Sự Kiện NTU"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=ql_su_kien
DB_USERNAME=root
DB_PASSWORD=your_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
```

---

## 🚀 Cài Đặt & Chạy Không Dùng Docker

### Yêu Cầu Hệ Thống

- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- Composer
- Git

### Hướng Dẫn Cài Đặt

```bash
# 1. Clone repository
git clone <repository-url> ql_su_kien
cd ql_su_kien

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate API key
php artisan key:generate

# 6. Create storage symlink
php artisan storage:link

# 7. Run migrations
php artisan migrate

# 8. Seed database (optional)
php artisan db:seed

# 9. Build frontend assets
npm run build

# 10. Start development server
php artisan serve
# Access: http://localhost:8000

# 11. (Separate terminal) Start Vite development server
npm run dev
```

---

## 📌 Thông Tin Quan Trọng Cần Lưu Ý

### 1. Database

- **Engine:** MySQL 8.0
- **Charset:** utf8mb4_unicode_ci
- **Collation:** utf8mb4_unicode_ci
- **Database Name:** ql_su_kien
- Có 17 migration files tạo 13 database tables chính
- Có relationship constraints (Foreign Keys)

### 2. Authentication

- Sử dụng **Laravel Sanctum** cho API
- Tokens được lưu trong database
- Không sử dụng JWT, dùng database tokens
- Sanctum middleware: `auth:sanctum`

### 3. Authorization

- Admin có quyền: tạo/sửa/xóa sự kiện, quản lý người dùng, cộng/trừ điểm
- Sinh viên chỉ có quyền: xem sự kiện, đăng ký, dùng điểm của mình
- Role kiểm tra qua middleware

### 4. File Upload

- Sử dụng `storage/app` directory
- Có symbolic link từ `public/storage`
- Cần chạy `php artisan storage:link`
- Hỗ trợ các file type: ảnh (jpg, png, gif), video (mp4, webm), tài liệu (pdf, doc)

### 5. API Authentication

Tất cả request tới protected endpoints cần header:

```
Authorization: Bearer {token}
Content-Type: application/json
```

### 6. Migrations

- Chạy migrations: `php artisan migrate`
- Rollback: `php artisan migrate:rollback`
- Fresh (reset): `php artisan migrate:fresh`
- Tất cả migrations theo thứ tự time series

### 7. Testing

```bash
# Run test suite
php artisan test

# Run với coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Feature/EventTest.php
```

### 8. Cache & Queue

- Cache driver: Redis (hoặc array/file cho development)
- Queue connection: Redis (hoặc sync cho development)
- Redis server: localhost:6379 (trong Docker là service redis)

### 9. Logging

- Log files: `storage/logs/`
- Single file: `laravel.log`
- Log level: từ `.env` (debug, info, warning, error, critical)

### 10. Performance

- Sử dụng Eloquent query optimization (eager loading)
- Cache thông báo, điểm, ranking
- Redis cho cache & queue
- Database indexes trên foreign keys

### 11. Công Cụ Dev Hữu Ích

```bash
# Tạo model + migration + controller
php artisan make:model ModelName -mcr

# Tạo migration
php artisan make:migration create_table_name --create=table_name

# Tạo seeder
php artisan make:seeder SeedName

# Clear cache
php artisan cache:clear

# View routes
php artisan route:list

# Tinker (interactive shell)
php artisan tinker
```

---

## 👥 Tài Khoản Mặc Định

Sau khi hệ thống được seed, sẽ có các tài khoản test:

| Vai Trò   | Email               | Mật Khẩu |
| --------- | ------------------- | -------- |
| Admin     | admin@example.com   | password |
| Sinh viên | student@example.com | password |

---

## 📚 Các File Documentation

| File                                                                                   | Nội Dung           |
| -------------------------------------------------------------------------------------- | ------------------ |
| [README.md](README.md)                                                                 | Tổng quan dự án    |
| [README_NEW.md](README_NEW.md)                                                         | README cập nhật    |
| [QUICKSTART.md](QUICKSTART.md)                                                         | Setup nhanh chóng  |
| [SETUP.md](SETUP.md)                                                                   | Hướng dẫn chi tiết |
| [COMPLETED_IMPROVEMENTS.md](COMPLETED_IMPROVEMENTS.md)                                 | Danh sách cải tiến |
| [doc/event_system_workflows.md](doc/event_system_workflows.md)                         | Workflow hệ thống  |
| [doc/frontend_requirements_event_system.md](doc/frontend_requirements_event_system.md) | Yêu cầu frontend   |

---

## 🎯 Architecture Layer

```
┌────────────────────────────────────────┐
│   Presentation Layer (Frontend)        │
│   • Blade Templates (resources/views)  │
│   • Vite Assets (CSS/JS)              │
│   • Tailwind CSS Styling              │
└────────────────────────────────────────┘
                    ↓
┌────────────────────────────────────────┐
│   Controller Layer (HTTP)              │
│   • Api Controllers (7 controllers)    │
│   • Request Validation (FormRequest)   │
│   • Response Formatting                │
└────────────────────────────────────────┘
                    ↓
┌────────────────────────────────────────┐
│   Service Layer (Business Logic)       │
│   • EventService (quản lý sự kiện)    │
│   • PointService (quản lý điểm)       │
│   • NotificationService (thông báo)   │
│   • UserService (quản lý user)        │
└────────────────────────────────────────┘
                    ↓
┌────────────────────────────────────────┐
│   Model/ORM Layer (Data Access)        │
│   • Eloquent Models (13 models)        │
│   • Relationships (1:N, N:M)           │
│   • Query Scopes                       │
└────────────────────────────────────────┘
                    ↓
┌────────────────────────────────────────┐
│   Database Layer (Persistence)         │
│   • MySQL 8.0                          │
│   • Migrations (17 files)              │
│   • Seeders                            │
└────────────────────────────────────────┘
```

---

## 🔍 Điều Gì Cần Làm Khi Bắt Đầu

1. **Setup môi trường**
    - Copy `.env.example` → `.env`
    - Cấu hình database trong `.env`
    - Chạy `composer install`

2. **Khởi tạo database**
    - Tạo database MySQL: `ql_su_kien`
    - Chạy migrations: `php artisan migrate`
    - Seed dữ liệu test: `php artisan db:seed` (nếu có)

3. **Xây dựng frontend assets**
    - `npm install` - cài dependencies
    - `npm run dev` - chạy Vite dev server
    - `npm run build` - build production

4. **Chạy ứng dụng**
    - `php artisan serve` - chạy web server
    - Truy cập: http://localhost:8000
    - Đăng nhập với tài khoản test

5. **Khám phá codebase**
    - Đọc các route trong `routes/api.php`
    - Xem controllers trong `app/Http/Controllers/Api/`
    - Xem models trong `app/Models/`
    - Xem services trong `app/Services/`

---

## 📞 Support & Documentation

- **Laravel Documentation:** https://laravel.com
- **Tailwind CSS:** https://tailwindcss.com
- **Vite:** https://vitejs.dev
- **MySQL:** https://dev.mysql.com

---

## 📝 Phiên Bản & Changelog

**v1.0 (Current)**

- 13 Core Models
- 40+ API Endpoints
- Complete event management
- Voting system
- Real-time notifications
- Points leaderboard
- Docker support

---

**Tài liệu này được tạo vào ngày 28/03/2026. Cập nhật lần cuối khi có thay đổi lớn.**
