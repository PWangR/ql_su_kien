# 🎉 Website Quản Lý Sự Kiện NTU

Hệ thống quản lý sự kiện cho **Khoa Công Nghệ Thông Tin, Đại học Nha Trang**

---

## 📋 Tổng Quan

**Quản Lý Sự Kiện NTU** là một ứng dụng web hiện đại giúp:

- ✅ **Quản trị viên**: Tạo, chỉnh sửa, quản lý sự kiện & người dùng
- ✅ **Sinh viên**: Đăng ký tham gia, theo dõi điểm, xem lịch sử tham gia
- ✅ **Real-time**: Thông báo tức thì, cập nhật sự kiện
- ✅ **Analytics**: Thống kê chi tiết, bảng xếp hạng

## 🚀 Công Nghệ

| Layer          | Technology                          |
| -------------- | ----------------------------------- |
| **Frontend**   | Laravel Blade + Tailwind CSS        |
| **Backend**    | Laravel 11 + PHP 8.2                |
| **Database**   | MySQL 8.0                           |
| **API**        | RESTful API với Laravel Sanctum     |
| **Real-time**  | Laravel Broadcasting (Pusher/Redis) |
| **Deployment** | Docker + Docker Compose             |

## 📦 Features

### 🔐 Authentication & Authorization

- ✅ Login/Logout với session
- ✅ Role-based access control (Admin/Student)
- ✅ Password encryption
- ✅ Account locking system

### 📅 Event Management

- ✅ CRUD operations (Create, Read, Update, Delete)
- ✅ Event categorization
- ✅ Schedule conflict detection
- ✅ Participant management
- ✅ Real-time status updates

### 👥 User Management

- ✅ User registration & profiles
- ✅ Profile picture upload
- ✅ Account status management
- ✅ User statistics

### 📊 Point System

- ✅ Automatic point allocation for participation
- ✅ Point history tracking
- ✅ Leaderboard/Rankings
- ✅ Admin point adjustment

### 🔔 Notifications

- ✅ Event reminders
- ✅ Point updates
- ✅ Real-time notifications
- ✅ Notification history

### 📁 Media Management

- ✅ Image upload for events
- ✅ Document storage
- ✅ Video management
- ✅ File categorization

### 📈 Analytics & Reports

- ✅ Event statistics
- ✅ User statistics
- ✅ Point distribution analytics
- ✅ Admin dashboard

## 🛠️ Installation

### Prerequisites

- PHP >= 8.1
- MySQL >= 8.0
- Node.js >= 16
- Composer >= 2.0

### Quick Setup

```bash
# 1. Clone repository
git clone <repository-url>
cd ql_su_kien

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database
# Edit .env with your database credentials

# 5. Run migrations
php artisan migrate
php artisan db:seed

# 6. Build assets
npm run build

# 7. Start server
php artisan serve
```

Access: `http://localhost:8000`

### Default Credentials

```
Admin:
  Email: admin@example.com
  Password: password

Student:
  Email: student@example.com
  Password: password
```

## 🐳 Docker Setup

```bash
# Build & run containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force

# Stop containers
docker-compose down
```

## 📚 API Documentation

### Base URL

```
http://localhost:8000/api
```

### Public Endpoints

```
GET    /api/events                    - List events
GET    /api/events/{id}               - Event details
GET    /api/events/search/{keyword}   - Search events
```

### Protected Endpoints (Auth Required)

```
POST   /api/registrations/{eventId}   - Register event
DELETE /api/registrations/{eventId}   - Cancel registration
GET    /api/registrations/history     - My event history
GET    /api/notifications             - My notifications
GET    /api/points/total              - My total points
GET    /api/points/history            - My point history
GET    /api/points/leaderboard        - Top students
```

### Admin Endpoints

```
POST   /api/admin/events              - Create event
PUT    /api/admin/events/{id}         - Update event
DELETE /api/admin/events/{id}         - Delete event
GET    /api/admin/users               - List users
POST   /api/admin/users               - Create user
GET    /api/admin/statistics/dashboard - Dashboard stats
```

## 📁 Project Structure

```
ql_su_kien/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Web controllers
│   │   ├── Controllers/Api/      # API controllers
│   │   ├── Middleware/           # Custom middleware
│   │   ├── Requests/             # Form validation
│   │   └── Kernel.php
│   ├── Models/                   # Eloquent models
│   ├── Services/                 # Business logic
│   ├── Policies/                 # Authorization policies
│   └── Traits/                   # Reusable traits
├── config/                       # Configuration files
├── database/
│   ├── migrations/               # Database migrations
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories
├── resources/
│   ├── views/                    # Blade templates
│   ├── css/                      # Tailwind styles
│   └── js/                       # JavaScript files
├── routes/
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
├── tests/
│   ├── Unit/                     # Unit tests
│   └── Feature/                  # Feature tests
└── docker/                       # Docker configuration
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run unit tests
php artisan test --filter Unit

# Run feature tests
php artisan test --filter Feature

# With coverage
php artisan test --coverage
```

## 🔐 Security Features

- ✅ CSRF protection
- ✅ SQL injection prevention (Prepared statements)
- ✅ Password hashing (bcrypt)
- ✅ XSS protection
- ✅ Rate limiting on API
- ✅ Input validation & sanitization
- ✅ Soft deletes for data integrity
- ✅ Role-based access control

## 📊 Database Schema

**Main Tables:**

- `nguoi_dung` - Users
- `su_kien` - Events
- `loai_su_kien` - Event types
- `dang_ky` - Registrations
- `lich_su_diem` - Point history
- `thong_bao` - Notifications
- `thu_vien_da_phuong_tien` - Media library
- `mau_bai_dang` - Post templates
- `bau_cu` - Voting
- `ung_cu_vien` - Candidates
- `cu_tri` - Voters

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Configure CORS
- [ ] Setup SSL certificate
- [ ] Configure email service
- [ ] Setup CDN for assets
- [ ] Enable caching
- [ ] Setup backup strategy
- [ ] Configure monitoring
- [ ] Setup CI/CD pipeline

### Environment Variables

See `.env.example` for complete list

## 📝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open Pull Request

## 🐛 Bug Reports & Feature Requests

- Create issues on GitHub
- Include detailed reproduction steps
- Provide screenshots/logs when applicable

## 📞 Support & Contact

**Author**: Dương Phú Quảng (64131942)  
**Email**: quang.dp.64cntt@ntu.edu.vn  
**Institution**: [Khoa CNTT - Đại học Nha Trang](https://ntu.edu.vn)

## 📄 License

This project is open-sourced software licensed under the MIT license.

## 🙏 Acknowledgments

- Laravel team for the excellent framework
- Tailwind CSS for utility-first CSS
- All contributors and testers

---

**Last Updated**: March 2026  
**Version**: 1.0.0  
**Status**: Production Ready ✅
