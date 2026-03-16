# Hướng dẫn Cài đặt & Triển khai

## 1. Yêu cầu hệ thống

- PHP >= 8.1
- MySQL >= 8.0
- Node.js >= 16
- Composer >= 2.0
- Docker & Docker Compose (optional, cho production)

## 2. Cài đặt Local Development

### Bước 1: Clone Repository

```bash
git clone <repository-url>
cd ql_su_kien
```

### Bước 2: Cài đặt PHP Dependencies

```bash
composer install
```

### Bước 3: Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### Bước 4: Cấu hình Database

Chỉnh sửa file `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ql_su_kien
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 5: Chạy Migrations

```bash
php artisan migrate
php artisan db:seed
```

### Bước 6: Cài đặt Node Dependencies

```bash
npm install
```

### Bước 7: Build Assets

```bash
npm run dev
# hoặc
npm run build
```

### Bước 8: Khởi động Server

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 3. Tài khoản Default

### Admin

- Email: `admin@example.com`
- Password: `password`

### Student

- Email: `student@example.com`
- Password: `password`

## 4. Cấu trúc Thư mục

```
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controllers cho web routes
│   │   ├── Controllers/Api/   # Controllers cho API
│   │   ├── Middleware/        # Middleware
│   │   └── Requests/          # Form Request validation
│   ├── Models/                # Eloquent Models
│   ├── Services/              # Business Logic
│   └── Traits/                # Reusable traits
├── config/                    # Configuration files
├── database/
│   ├── migrations/            # Database migrations
│   ├── seeders/               # Database seeders
│   └── factories/             # Model factories for testing
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # CSS files
│   └── js/                    # JavaScript files
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes
├── storage/                   # Logs, uploads, etc
├── tests/                     # Unit & Feature tests
└── docker/                    # Docker configuration
```

## 5. API Endpoints

### Public Endpoints

- `GET /api/events` - Lấy danh sách sự kiện
- `GET /api/events/{id}` - Chi tiết sự kiện
- `GET /api/events/search/{keyword}` - Tìm kiếm sự kiện

### Protected Endpoints (Yêu cầu Auth)

- `POST /api/registrations/{eventId}` - Đăng ký tham gia
- `DELETE /api/registrations/{eventId}` - Hủy đăng ký
- `GET /api/registrations/history` - Lịch sử tham gia
- `GET /api/notifications` - Danh sách thông báo
- `GET /api/points/total` - Tổng điểm
- `GET /api/points/history` - Lịch sử điểm
- `GET /api/points/leaderboard` - Bảng xếp hạng

### Admin Endpoints

- `POST /api/admin/events` - Tạo sự kiện
- `PUT /api/admin/events/{id}` - Cập nhật sự kiện
- `DELETE /api/admin/events/{id}` - Xóa sự kiện
- `GET /api/admin/users` - Danh sách người dùng
- `POST /api/admin/users` - Tạo người dùng
- `GET /api/admin/statistics/dashboard` - Thống kê dashboard

## 6. Chạy Tests

### Unit Tests

```bash
php artisan test --filter Unit
```

### Feature Tests

```bash
php artisan test --filter Feature
```

### Tất cả Tests

```bash
php artisan test
```

### Với Coverage Report

```bash
php artisan test --coverage
```

## 7. Cấu hình Broadcasting (Real-time Notifications)

### Setup Pusher (Optional)

1. Tạo tài khoản tại [pusher.com](https://pusher.com)
2. Cấu hình `.env`:

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

3. Chạy server broadcasting:

```bash
php artisan serve
```

## 8. Deployment với Docker

### Build Image

```bash
docker-compose build
```

### Khởi động Containers

```bash
docker-compose up -d
```

### Chạy Migrations

```bash
docker-compose exec app php artisan migrate --force
```

### Seed Database

```bash
docker-compose exec app php artisan db:seed
```

### View Logs

```bash
docker-compose logs -f app
```

### Stop Containers

```bash
docker-compose down
```

## 9. File Storage Configuration

Hình ảnh và file được lưu tại:

- Hình sự kiện: `storage/app/public/events/`
- Avatar người dùng: `storage/app/public/avatars/`
- Media: `storage/app/public/media/`

Tạo symbolic link:

```bash
php artisan storage:link
```

## 10. Environment Variables

Xem file `.env.example` để hiểu các biến cấu hình:

- `APP_DEBUG` - Bật/tắt debug mode
- `DB_*` - Cấu hình database
- `MAIL_*` - Cấu hình email
- `BROADCAST_DRIVER` - Driver broadcasting (log, pusher, redis)

## 11. Troubleshooting

### 1000x Permission Denied

```bash
chmod -R 775 storage bootstrap/cache
```

### Database Connection Error

- Kiểm tra MySQL service đang chạy
- Kiểm tra thông tin kết nối trong `.env`

### Composer Errors

```bash
composer install --no-cache
```

### Node/npm Errors

```bash
npm install --legacy-peer-deps
```

## 12. Hỗ trợ & Liên hệ

Email: quang.dp.64cntt@ntu.edu.vn
Trang: [Khoa CNTT - Đại học Nha Trang](https://ntu.edu.vn)

---

**Last updated**: March 2026
**Version**: 1.0.0
