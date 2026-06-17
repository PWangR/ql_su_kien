# QL Su Kien

He thong quan ly su kien danh cho moi truong sinh vien, gom website quan tri - nguoi dung bang Laravel va ung dung mobile bang Expo/React Native.

Du an ho tro cac luong chinh: quan ly su kien, dang ky tham gia, diem danh QR, tinh diem ren luyen/tham gia, gui thong bao, quan ly bau cu, bao cao, quan ly nguoi dung, thu vien media va chatbot cau hinh qua Gemini.

## Muc luc

- [Tong quan chuc nang](#tong-quan-chuc-nang)
- [Cong nghe su dung](#cong-nghe-su-dung)
- [Cau truc thu muc](#cau-truc-thu-muc)
- [Yeu cau moi truong](#yeu-cau-moi-truong)
- [Cai dat nhanh bang Docker](#cai-dat-nhanh-bang-docker)
- [Cai dat local bang Laragon hoac PHP thu cong](#cai-dat-local-bang-laragon-hoac-php-thu-cong)
- [Chay ung dung mobile Expo](#chay-ung-dung-mobile-expo)
- [Build Android APK/AAB](#build-android-apkaab)
- [Lenh thuong dung](#lenh-thuong-dung)
- [Kiem thu](#kiem-thu)
- [Luu y trien khai](#luu-y-trien-khai)

## Tong quan chuc nang

### Website Laravel

- Dang nhap, dang ky, quen mat khau, xac thuc email.
- Trang nguoi dung: xem su kien, dang ky/huy dang ky, lich su tham gia, thong bao, ho so ca nhan.
- Diem danh QR cho sinh vien va man hinh quet diem danh.
- Trang quan tri:
  - dashboard;
  - quan ly su kien, loai su kien, mau bai dang;
  - quan ly nguoi dung;
  - quan ly thong bao va lich gui thong bao;
  - quan ly media, the anh, upload hinh anh;
  - thong ke, bao cao va export Excel;
  - cau hinh SMTP;
  - cau hinh Gemini chatbot;
  - log hoat dong;
  - quan ly bau cu, ung cu vien, cu tri va ket qua bau cu.
- API Laravel Sanctum phuc vu mobile app.
- Scheduler Laravel tu dong cap nhat trang thai su kien, gui thong bao den han va don log cu.

### Ung dung mobile Expo

Ung dung nam trong thu muc `mobile_app`, dung chung backend Laravel qua API `/api`.

- Dang nhap/dang ky/quen mat khau.
- Xem danh sach va chi tiet su kien.
- Dang ky hoac huy dang ky su kien.
- Quet QR diem danh bang camera.
- Xem diem, lich su tham gia, thong bao.
- Bau cu va xem ket qua.
- Cap nhat ho so, doi mat khau.
- Chatbot.

## Cong nghe su dung

### Backend va website

- PHP `^8.1`
- Laravel `^10.10`
- Laravel Sanctum `^3.3`
- MySQL 8
- Redis 7 trong cau hinh Docker
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

`docker-compose.yml` khai bao cac service:

- `nginx`: web server, mac dinh `http://localhost:8080`
- `app`: PHP-FPM chay Laravel
- `scheduler`: chay `php artisan schedule:work`
- `node`: Vite dev server, mac dinh `http://localhost:5173`
- `db`: MySQL 8, map ra host port `3307`
- `redis`: Redis, map ra host port `6380`

## Cau truc thu muc

```text
.
|-- app/                  # Models, Controllers, Services, Policies, Commands
|-- bootstrap/            # Bootstrap Laravel
|-- config/               # Cau hinh Laravel
|-- database/             # Migrations, factories, seeders
|-- docker/               # Cau hinh nginx va entrypoint container
|-- doc/                  # Tai lieu, so do, hinh minh hoa
|-- mobile_app/           # Ung dung Expo/React Native
|-- public/               # Public web root, asset build, storage link
|-- resources/            # Blade views, CSS, JS
|-- routes/               # web.php, api.php, console.php
|-- storage/              # Logs, cache, uploaded files
|-- tests/                # Unit va Feature tests
|-- docker-compose.yml
|-- Dockerfile
|-- composer.json
|-- package.json
`-- ql_su_kien.sql        # File SQL mau/import thu cong neu can
```

## Yeu cau moi truong

Chon mot trong hai cach chay backend:

### Cach 1: Docker

- Docker Desktop
- Docker Compose

### Cach 2: Local/Laragon

- PHP 8.1 tro len
- Composer 2
- Node.js 18 tro len
- MySQL 8 hoac MariaDB tuong thich
- Laragon neu chay theo domain local `ql_su_kien.test`

### Mobile

- Node.js 18 tro len
- npm
- Expo CLI/EAS CLI co the chay qua `npx`
- Expo Go tren dien thoai hoac Android Emulator

## Cai dat nhanh bang Docker

Day la cach de chay du an dong nhat nhat tren Windows.

### Cach nhanh bang script

1. Mo Docker Desktop.
2. Chay file:

```bat
start-docker.bat
```

Script se:

- tao `.env` tu `.env.docker.example` neu chua co;
- build va khoi dong container;
- chay `composer install`;
- chay migration;
- tao storage link.

Sau khi xong, truy cap:

```text
http://localhost:8080
```

### Lenh Docker thu cong

```bash
docker compose up -d --build
docker compose exec app composer install --no-interaction --prefer-dist
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed
docker compose exec app php artisan storage:link --force
```

Neu muon import du lieu tu file SQL co san:

```bat
import-docker-db.bat
```

Kiem tra database Docker:

```bat
check-docker-db.bat
```

Dung container:

```bat
stop-docker.bat
```

Hoac:

```bash
docker compose down
```

Xoa ca database volume de lam lai tu dau:

```bash
docker compose down -v
```

### Cong mac dinh Docker

| Dich vu | URL/port |
| --- | --- |
| Laravel web | `http://localhost:8080` |
| Vite dev server | `http://localhost:5173` |
| MySQL tren host | `localhost:3307` |
| Redis tren host | `localhost:6380` |

Co the doi port bang cac bien trong `.env`:

```dotenv
APP_PORT=8081
VITE_PORT=5174
DB_FORWARD_PORT=3308
REDIS_FORWARD_PORT=6381
```

## Cai dat local bang Laragon hoac PHP thu cong

### 1. Cai dependencies

```bash
composer install
npm install
```

### 2. Tao file moi truong

Repo hien co `.env.docker.example`. Neu chua co `.env`, co the copy file nay roi sua lai thong tin database cho moi truong local:

```bash
copy .env.docker.example .env
```

Voi Laragon/MySQL local, cac bien thuong can chinh:

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

Tao app key:

```bash
php artisan key:generate
```

### 3. Tao database

Tao database ten `ql_su_kien` trong MySQL, sau do chay:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

Neu muon dung file SQL mau:

```bash
mysql -u root -p ql_su_kien < ql_su_kien.sql
```

### 4. Build asset va chay web

Moi truong dev:

```bash
npm run dev
php artisan serve
```

Mac dinh `php artisan serve` chay tai:

```text
http://127.0.0.1:8000
```

Voi Laragon, co the chay script:

```bat
start-laragon.bat
```

Script nay clear cache Laravel, build asset va huong dan mo:

```text
http://ql_su_kien.test
```

## Tai khoan mac dinh

Seeder mac dinh trong `DatabaseSeeder` tao:

| Vai tro | Email | Mat khau |
| --- | --- | --- |
| Admin | `admin@local.test` | `12345678` |
| Sinh vien | `sv@local.test` | `12345678` |

Repo cung co seeder demo rieng `CreateDemoUsers` tao:

| Vai tro | Email | Mat khau |
| --- | --- | --- |
| Admin demo | `admin@example.com` | `password` |
| Sinh vien demo | `student@example.com` | `password` |

Chay seeder demo neu can:

```bash
php artisan db:seed --class=CreateDemoUsers
```

## Chay ung dung mobile Expo

Mobile app nam trong:

```text
mobile_app/
```

Ung dung goi API qua bien:

```text
EXPO_PUBLIC_API_URL
```

Trong code, `mobile_app/src/services/api.js` se tu noi them `/api`, vi vay gia tri bien moi truong chi can la domain backend, khong them `/api`.

Dung dung:

```text
http://localhost:8080
```

Khong dung:

```text
http://localhost:8080/api
```

### Cach nhanh bang script

Chay backend truoc:

```bat
start-docker.bat
```

Sau do chay mobile:

```bat
start-mobile.bat
```

Script se vao `mobile_app`, cai dependencies neu can, gan `EXPO_PUBLIC_API_URL` theo che do va mo Expo.

Co the chay truc tiep:

```bat
start-mobile.bat phone
start-mobile.bat emulator
start-mobile.bat web
start-mobile.bat default
start-mobile.bat laravel
start-mobile.bat manual
```

### Chay thu cong

```bash
cd mobile_app
npm install
```

Neu chay web/mobile tren cung may voi Docker:

```bash
set EXPO_PUBLIC_API_URL=http://localhost:8080
npm start
```

Neu chay Android Emulator:

```bash
set EXPO_PUBLIC_API_URL=http://10.0.2.2:8080
npm run android
```

Neu chay dien thoai that qua Expo Go, dien thoai khong dung duoc `localhost` cua may tinh. Hay dung IP LAN cua may tinh:

```bash
set EXPO_PUBLIC_API_URL=http://192.168.1.10:8080
npm run phone
```

Neu backend chay bang `php artisan serve`, nen mo bang:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Sau do dung:

```text
http://IP_MAY_TINH:8000
```

## Build Android APK/AAB

### Build APK noi bo

Dung script o thu muc goc:

```bat
build-mobile-apk.bat
```

Hoac trong `mobile_app`:

```bash
npm run build:android:apk
```

APK dung profile `preview` trong `mobile_app/eas.json`.

### Build AAB production

Dung script o thu muc goc:

```bat
build-mobile-aab.bat
```

Hoac trong `mobile_app`:

```bash
npm run build:android:aab
```

AAB dung profile `production` trong `mobile_app/eas.json`.

Luu y: `EXPO_PUBLIC_API_URL` duoc dong goi vao app tai thoi diem build. Khi doi domain backend, can build lai APK/AAB.

## Lenh thuong dung

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

## Kiem thu

Chay toan bo test Laravel:

```bash
php artisan test
```

Chay test theo nhom/file:

```bash
php artisan test tests/Unit
php artisan test tests/Feature
php artisan test tests/Feature/Api/EventApiTest.php
```

Frontend root co script:

```bash
npm test
```

Tuy nhien can kiem tra lai cau hinh Jest truoc khi dua vao CI/CD vi repo hien chi khai bao script trong `package.json`.

## API chinh

Tat ca route mobile/API nam duoi prefix:

```text
/api
```

Mot so endpoint chinh:

| Nhom | Endpoint |
| --- | --- |
| Auth | `POST /api/login`, `POST /api/register`, `POST /api/logout` |
| Su kien | `GET /api/events`, `GET /api/events/{id}`, `GET /api/events/search/{keyword}` |
| Dang ky | `POST /api/registrations/{eventId}`, `DELETE /api/registrations/{eventId}` |
| Diem danh QR | `POST /api/registrations/app-scan`, `POST /api/registrations/app-scan-batch` |
| Thong bao | `GET /api/notifications`, `POST /api/notifications/{id}/read` |
| Diem | `GET /api/points/total`, `GET /api/points/history`, `GET /api/points/leaderboard` |
| Bau cu | `GET /api/voting`, `POST /api/voting/{id}/vote`, `GET /api/voting/{id}/results` |
| Chatbot | `POST /api/chatbot/ask` |
| Admin API | `POST /api/admin/events`, `GET /api/admin/users`, `GET /api/admin/statistics/dashboard` |

## Scheduler

`app/Console/Kernel.php` dang len lich:

- `app:sync-event-status` moi phut;
- `notifications:send-due` moi phut;
- `logs:prune --days=30` luc 02:00 moi ngay.

Khi chay Docker, service `scheduler` da chay:

```bash
php artisan schedule:work
```

Neu deploy khong dung Docker, can tao cron tren server:

```cron
* * * * * cd /path/to/ql_su_kien && php artisan schedule:run >> /dev/null 2>&1
```

## Luu y trien khai

- Build asset production bang `npm run build`.
- Dat `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-cua-ban`.
- Cau hinh MySQL production rieng, khong dung mat khau mac dinh.
- Chay `php artisan migrate --force` tren server.
- Chay `php artisan storage:link` va dam bao `storage/`, `bootstrap/cache/` co quyen ghi.
- Cau hinh scheduler/cron de thong bao va trang thai su kien hoat dong dung.
- Cau hinh SMTP trong admin hoac `.env` neu can gui email that.
- Neu build mobile production, cap nhat `EXPO_PUBLIC_API_URL` thanh domain HTTPS backend roi build lai app.
- Docker compose hien tai phu hop local/dev. Truoc khi dua len production can harden lai `.env`, port public, volume, backup database, HTTPS va cache config.

## Tai lieu lien quan trong repo

- `DOCKER_GUIDE.md`: huong dan chay Docker.
- `MOBILE_RUN_GUIDE.md`: huong dan chay mobile app.
- `BUILD_MOBILE_APP_GUIDE.md`: huong dan build mobile.
- `TAI_LIEU_TONG_QUAN_DU_AN.md`: tai lieu tong quan du an.
- `TEST_MANUAL_GUIDE.md`: huong dan test thu cong.
- `mobile_app/API_DOCUMENTATION.md`: tai lieu API cho mobile.
- `doc/`: so do, use case, ERD va tai lieu phan tich thiet ke.

## Ghi chu phat trien

- User model dung bang `nguoi_dung`, khoa chinh `ma_sinh_vien`, mat khau luu trong cot `mat_khau`.
- API xac thuc bang Laravel Sanctum.
- Upload public dung disk `public`, URL dua vao `APP_URL/storage`.
- Mobile app tu dong them `/api` vao `EXPO_PUBLIC_API_URL`.
- Khi sua luong dang ky/diem danh/thong bao, nen chay lai cac test trong `tests/Feature/Api` va `tests/Unit/Services`.
