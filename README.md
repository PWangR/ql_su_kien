# QL Sự Kiện

Hệ thống quản lý sự kiện dành cho môi trường sinh viên, gồm website quản trị - người dùng bằng Laravel và ứng dụng mobile bằng Expo/React Native.

Dự án hỗ trợ các luồng chính: quản lý sự kiện, đăng ký tham gia, điểm danh QR, tính điểm rèn luyện/tham gia, gửi thông báo, quản lý bầu cử, báo cáo, quản lý người dùng, thư viện media và chatbot cấu hình qua Gemini.

## Mục Lục

- [Tổng quan chức năng](#tổng-quan-chức-năng)
- [Công nghệ sử dụng](#công-nghệ-sử-dụng)
- [Cấu trúc thư mục](#cấu-trúc-thư-mục)
- [Yêu cầu môi trường](#yêu-cầu-môi-trường)
- [Cài đặt nhanh bằng Docker](#cài-đặt-nhanh-bằng-docker)
- [Cài đặt local bằng Laragon hoặc PHP thủ công](#cài-đặt-local-bằng-laragon-hoặc-php-thủ-công)
- [Chạy ứng dụng mobile Expo](#chạy-ứng-dụng-mobile-expo)
- [Build Android APK/AAB](#build-android-apkaab)
- [Lệnh thường dùng](#lệnh-thường-dùng)
- [Kiểm thử](#kiểm-thử)
- [Lưu ý triển khai](#lưu-ý-triển-khai)

## Tổng Quan Chức Năng

### Website Laravel

- Đăng nhập, đăng ký, quên mật khẩu, xác thực email.
- Trang người dùng: xem sự kiện, đăng ký/hủy đăng ký, lịch sử tham gia, thông báo, hồ sơ cá nhân.
- Điểm danh QR cho sinh viên và màn hình quét điểm danh.
- Trang quản trị:
  - dashboard;
  - quản lý sự kiện, loại sự kiện, mẫu bài đăng;
  - quản lý người dùng;
  - quản lý thông báo và lịch gửi thông báo;
  - quản lý media, thẻ ảnh, upload hình ảnh;
  - thống kê, báo cáo và export Excel;
  - cấu hình SMTP;
  - cấu hình Gemini chatbot;
  - log hoạt động;
  - quản lý bầu cử, ứng cử viên, cử tri và kết quả bầu cử.
- API Laravel Sanctum phục vụ mobile app.
- Scheduler Laravel tự động cập nhật trạng thái sự kiện, gửi thông báo đến hạn và dọn log cũ.

### Ứng Dụng Mobile Expo

Ứng dụng nằm trong thư mục `mobile_app`, dùng chung backend Laravel qua API `/api`.

- Đăng nhập/đăng ký/quên mật khẩu.
- Xem danh sách và chi tiết sự kiện.
- Đăng ký hoặc hủy đăng ký sự kiện.
- Quét QR điểm danh bằng camera.
- Xem điểm, lịch sử tham gia, thông báo.
- Bầu cử và xem kết quả.
- Cập nhật hồ sơ, đổi mật khẩu.
- Chatbot.

## Công Nghệ Sử Dụng

### Backend Và Website

- PHP `^8.1`
- Laravel `^10.10`
- Laravel Sanctum `^3.3`
- MySQL 8
- Redis 7 trong cấu hình Docker
- Vite 5
- Tailwind CSS
- Chart.js
- Maatwebsite Excel
- Simple QR Code
- PHPUnit 10

### Mobile

- Expo `~55.0.17`
- React `19.2.0`
- React Native `0.83.6`
- React Navigation
- Axios
- AsyncStorage
- Zustand
- Expo Camera, Image Picker, Font, Splash Screen
- EAS Build cho APK/AAB Android

### Docker

`docker-compose.yml` khai báo các service:

- `nginx`: web server, mặc định `http://localhost:8080`
- `app`: PHP-FPM chạy Laravel
- `scheduler`: chạy `php artisan schedule:work`
- `node`: Vite dev server, mặc định `http://localhost:5173`
- `db`: MySQL 8, map ra host port `3307`
- `redis`: Redis, map ra host port `6380`

## Cấu Trúc Thư Mục

```text
.
|-- app/                  # Models, Controllers, Services, Policies, Commands
|-- bootstrap/            # Bootstrap Laravel
|-- config/               # Cấu hình Laravel
|-- database/             # Migrations, factories, seeders
|-- docker/               # Cấu hình nginx và entrypoint container
|-- doc/                  # Tài liệu, sơ đồ, hình minh họa
|-- mobile_app/           # Ứng dụng Expo/React Native
|-- public/               # Public web root, asset build, storage link
|-- resources/            # Blade views, CSS, JS
|-- routes/               # web.php, api.php, console.php
|-- storage/              # Logs, cache, uploaded files
|-- tests/                # Unit và Feature tests
|-- docker-compose.yml
|-- Dockerfile
|-- composer.json
|-- package.json
`-- ql_su_kien.sql        # File SQL mẫu/import thủ công nếu cần
```

## Yêu Cầu Môi Trường

Chọn một trong hai cách chạy backend:

### Cách 1: Docker

- Docker Desktop
- Docker Compose

### Cách 2: Local/Laragon

- PHP 8.1 trở lên
- Composer 2
- Node.js 18 trở lên
- MySQL 8 hoặc MariaDB tương thích
- Laragon nếu chạy theo domain local `ql_su_kien.test`

### Mobile

- Node.js 18 trở lên
- npm
- Expo CLI/EAS CLI có thể chạy qua `npx`
- Expo Go trên điện thoại hoặc Android Emulator

## Cài Đặt Nhanh Bằng Docker

Đây là cách để chạy dự án đồng nhất nhất trên Windows.

### Cách nhanh bằng script

1. Mở Docker Desktop.
2. Chạy file:

```bat
start-docker.bat
```

Script sẽ:

- tạo `.env` từ `.env.docker.example` nếu chưa có;
- build và khởi động container;
- chạy `composer install`;
- chạy migration;
- tạo storage link.

Sau khi xong, truy cập:

```text
http://localhost:8080
```

### Lệnh Docker thủ công

```bash
docker compose up -d --build
docker compose exec app composer install --no-interaction --prefer-dist
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed
docker compose exec app php artisan storage:link --force
```

Nếu muốn import dữ liệu từ file SQL có sẵn:

```bat
import-docker-db.bat
```

Kiểm tra database Docker:

```bat
check-docker-db.bat
```

Dừng container:

```bat
stop-docker.bat
```

Hoặc:

```bash
docker compose down
```

Xóa cả database volume để làm lại từ đầu:

```bash
docker compose down -v
```

### Cổng mặc định Docker

| Dịch vụ | URL/port |
| --- | --- |
| Laravel web | `http://localhost:8080` |
| Vite dev server | `http://localhost:5173` |
| MySQL trên host | `localhost:3307` |
| Redis trên host | `localhost:6380` |

Có thể đổi port bằng các biến trong `.env`:

```dotenv
APP_PORT=8081
VITE_PORT=5174
DB_FORWARD_PORT=3308
REDIS_FORWARD_PORT=6381
```

## Cài Đặt Local Bằng Laragon Hoặc PHP Thủ Công

### 1. Cài dependencies

```bash
composer install
npm install
```

### 2. Tạo file môi trường

Repo hiện có `.env.docker.example`. Nếu chưa có `.env`, có thể copy file này rồi sửa lại thông tin database cho môi trường local:

```bash
copy .env.docker.example .env
```

Với Laragon/MySQL local, các biến thường cần chỉnh:

```dotenv
APP_NAME="QL Su Kien"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://ql_su_kien.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ql_su_kien
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
```

Tạo app key:

```bash
php artisan key:generate
```

### 3. Tạo database

Tạo database tên `ql_su_kien` trong MySQL, sau đó chạy:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

Nếu muốn dùng file SQL mẫu:

```bash
mysql -u root -p ql_su_kien < ql_su_kien.sql
```

### 4. Build asset và chạy web

Môi trường dev:

```bash
npm run dev
php artisan serve
```

Mặc định `php artisan serve` chạy tại:

```text
http://127.0.0.1:8000
```

Với Laragon, có thể chạy script:

```bat
start-laragon.bat
```

Script này clear cache Laravel, build asset và hướng dẫn mở:

```text
http://ql_su_kien.test
```

## Tài Khoản Mặc Định

Seeder mặc định trong `DatabaseSeeder` tạo:

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Admin | `admin@local.test` | `12345678` |
| Sinh viên | `sv@local.test` | `12345678` |

Repo cũng có seeder demo riêng `CreateDemoUsers` tạo:

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Admin demo | `admin@example.com` | `password` |
| Sinh viên demo | `student@example.com` | `password` |

Chạy seeder demo nếu cần:

```bash
php artisan db:seed --class=CreateDemoUsers
```

## Chạy Ứng Dụng Mobile Expo

Mobile app nằm trong:

```text
mobile_app/
```

Ứng dụng gọi API qua biến:

```text
EXPO_PUBLIC_API_URL
```

Trong code, `mobile_app/src/services/api.js` sẽ tự nối thêm `/api`, vì vậy giá trị biến môi trường chỉ cần là domain backend, không thêm `/api`.

Dùng đúng:

```text
http://localhost:8080
```

Không dùng:

```text
http://localhost:8080/api
```

### Cách nhanh bằng script

Chạy backend trước:

```bat
start-docker.bat
```

Sau đó chạy mobile:

```bat
start-mobile.bat
```

Script sẽ vào `mobile_app`, cài dependencies nếu cần, gán `EXPO_PUBLIC_API_URL` theo chế độ và mở Expo.

Có thể chạy trực tiếp:

```bat
start-mobile.bat phone
start-mobile.bat emulator
start-mobile.bat web
start-mobile.bat default
start-mobile.bat laravel
start-mobile.bat manual
```

### Chạy thủ công

```bash
cd mobile_app
npm install
```

Nếu chạy web/mobile trên cùng máy với Docker:

```bash
set EXPO_PUBLIC_API_URL=http://localhost:8080
npm start
```

Nếu chạy Android Emulator:

```bash
set EXPO_PUBLIC_API_URL=http://10.0.2.2:8080
npm run android
```

Nếu chạy điện thoại thật qua Expo Go, điện thoại không dùng được `localhost` của máy tính. Hãy dùng IP LAN của máy tính:

```bash
set EXPO_PUBLIC_API_URL=http://192.168.1.10:8080
npm run phone
```

Nếu backend chạy bằng `php artisan serve`, nên mở bằng:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Sau đó dùng:

```text
http://IP_MAY_TINH:8000
```

## Build Android APK/AAB

### Build APK nội bộ

Dùng script ở thư mục gốc:

```bat
build-mobile-apk.bat
```

Hoặc trong `mobile_app`:

```bash
npm run build:android:apk
```

APK dùng profile `preview` trong `mobile_app/eas.json`.

### Build AAB production

Dùng script ở thư mục gốc:

```bat
build-mobile-aab.bat
```

Hoặc trong `mobile_app`:

```bash
npm run build:android:aab
```

AAB dùng profile `production` trong `mobile_app/eas.json`.

Lưu ý: `EXPO_PUBLIC_API_URL` được đóng gói vào app tại thời điểm build. Khi đổi domain backend, cần build lại APK/AAB.

## Lệnh Thường Dùng

### Laravel

```bash
php artisan optimize:clear
php artisan migrate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan route:list
php artisan schedule:work
```

### Composer

```bash
composer install
composer dump-autoload
```

### Frontend website

```bash
npm install
npm run dev
npm run build
```

### Docker

```bash
docker compose up -d --build
docker compose ps
docker compose logs -f app
docker compose logs -f scheduler
docker compose exec app php artisan route:list
docker compose down
```

### Mobile

```bash
cd mobile_app
npm start
npm run android
npm run web
npm run build:android:apk
npm run build:android:aab
```

## Kiểm Thử

Chạy toàn bộ test Laravel:

```bash
php artisan test
```

Chạy test theo nhóm/file:

```bash
php artisan test tests/Unit
php artisan test tests/Feature
php artisan test tests/Feature/Api/EventApiTest.php
```

Frontend root có script:

```bash
npm test
```

Tuy nhiên cần kiểm tra lại cấu hình Jest trước khi đưa vào CI/CD vì repo hiện chỉ khai báo script trong `package.json`.

## API Chính

Tất cả route mobile/API nằm dưới prefix:

```text
/api
```

Một số endpoint chính:

| Nhóm | Endpoint |
| --- | --- |
| Auth | `POST /api/login`, `POST /api/register`, `POST /api/logout` |
| Sự kiện | `GET /api/events`, `GET /api/events/{id}`, `GET /api/events/search/{keyword}` |
| Đăng ký | `POST /api/registrations/{eventId}`, `DELETE /api/registrations/{eventId}` |
| Điểm danh QR | `POST /api/registrations/app-scan`, `POST /api/registrations/app-scan-batch` |
| Thông báo | `GET /api/notifications`, `POST /api/notifications/{id}/read` |
| Điểm | `GET /api/points/total`, `GET /api/points/history`, `GET /api/points/leaderboard` |
| Bầu cử | `GET /api/voting`, `POST /api/voting/{id}/vote`, `GET /api/voting/{id}/results` |
| Chatbot | `POST /api/chatbot/ask` |
| Admin API | `POST /api/admin/events`, `GET /api/admin/users`, `GET /api/admin/statistics/dashboard` |

## Scheduler

`app/Console/Kernel.php` đang lên lịch:

- `app:sync-event-status` mỗi phút;
- `notifications:send-due` mỗi phút;
- `logs:prune --days=30` lúc 02:00 mỗi ngày.

Khi chạy Docker, service `scheduler` đã chạy:

```bash
php artisan schedule:work
```

Nếu deploy không dùng Docker, cần tạo cron trên server:

```cron
* * * * * cd /path/to/ql_su_kien && php artisan schedule:run >> /dev/null 2>&1
```

## Lưu Ý Triển Khai

- Build asset production bằng `npm run build`.
- Đặt `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-cua-ban`.
- Cấu hình MySQL production riêng, không dùng mật khẩu mặc định.
- Chạy `php artisan migrate --force` trên server.
- Chạy `php artisan storage:link` và đảm bảo `storage/`, `bootstrap/cache/` có quyền ghi.
- Cấu hình scheduler/cron để thông báo và trạng thái sự kiện hoạt động đúng.
- Cấu hình SMTP trong admin hoặc `.env` nếu cần gửi email thật.
- Nếu build mobile production, cập nhật `EXPO_PUBLIC_API_URL` thành domain HTTPS backend rồi build lại app.
- Docker compose hiện tại phù hợp local/dev. Trước khi đưa lên production cần harden lại `.env`, port public, volume, backup database, HTTPS và cache config.

## Tài Liệu Liên Quan Trong Repo

- `DOCKER_GUIDE.md`: hướng dẫn chạy Docker.
- `MOBILE_RUN_GUIDE.md`: hướng dẫn chạy mobile app.
- `BUILD_MOBILE_APP_GUIDE.md`: hướng dẫn build mobile.
- `TAI_LIEU_TONG_QUAN_DU_AN.md`: tài liệu tổng quan dự án.
- `TEST_MANUAL_GUIDE.md`: hướng dẫn test thủ công.
- `mobile_app/API_DOCUMENTATION.md`: tài liệu API cho mobile.
- `doc/`: sơ đồ, use case, ERD và tài liệu phân tích thiết kế.

## Ghi Chú Phát Triển

- User model dùng bảng `nguoi_dung`, khóa chính `ma_sinh_vien`, mật khẩu lưu trong cột `mat_khau`.
- API xác thực bằng Laravel Sanctum.
- Upload public dùng disk `public`, URL dựa vào `APP_URL/storage`.
- Mobile app tự động thêm `/api` vào `EXPO_PUBLIC_API_URL`.
- Khi sửa luồng đăng ký/điểm danh/thông báo, nên chạy lại các test trong `tests/Feature/Api` và `tests/Unit/Services`.
