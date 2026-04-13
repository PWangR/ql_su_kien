# ✅ Prompt hoàn chỉnh

Bạn là một lập trình viên fullstack giàu kinh nghiệm, đang làm việc với một hệ thống web (Laravel/PHP + Blade + MySQL). Hãy đọc toàn bộ source code của dự án và thực hiện các yêu cầu sau một cách **chính xác, đồng bộ và không phá vỡ chức năng hiện có**.

---

## 🔧 1. Thêm cấu hình SMTP trong trang Admin

### Yêu cầu:

* Tạo một mục **“Cấu hình SMTP”** trong trang quản trị (Admin Panel)
* Cho phép admin:

  * Nhập và chỉnh sửa các thông tin:

    * SMTP Host
    * Port
    * Username (email)
    * Password
    * Encryption (TLS/SSL)
    * From Address
    * From Name
* Lưu cấu hình vào:

  * Database (bảng `settings` hoặc bảng riêng `smtp_settings`)
* Khi gửi email trong hệ thống:

  * Phải sử dụng cấu hình SMTP này (override `.env` nếu cần)

### Yêu cầu kỹ thuật:

* Tạo migration + model + controller + view (Blade)
* Validate dữ liệu đầu vào
* Bảo mật password (mã hóa nếu cần)
* Có nút “Test gửi email” để kiểm tra cấu hình

---

## 📊 2. Thêm hệ thống Log hoạt động tài khoản (Admin)

### Yêu cầu:

* Ghi lại các hành động quan trọng của user:

  * Đăng nhập / đăng xuất
  * Tạo / sửa / xóa dữ liệu
  * Thao tác admin
* Mỗi log cần có:

  * User ID
  * Hành động (action)
  * Mô tả chi tiết
  * IP address
  * Thời gian

### Admin UI:

* Tạo trang **“Quản lý Log”**
* Cho phép:

  * Xem danh sách log (phân trang)
  * Tìm kiếm theo user / hành động
  * Lọc theo thời gian

### Yêu cầu kỹ thuật:

* Tạo bảng `activity_logs`
* Middleware hoặc Observer để tự động ghi log
* Không làm ảnh hưởng hiệu năng hệ thống

---

## 🌐 3. Fix lỗi UI hiển thị tiếng Việt không dấu

### Mục tiêu:

* Đảm bảo toàn bộ hệ thống hiển thị tiếng Việt có dấu đúng

### Kiểm tra và sửa:

* Encoding:

  * Đảm bảo tất cả file sử dụng UTF-8
  * Thêm `<meta charset="UTF-8">` trong layout
* Database:

  * Charset: `utf8mb4`
  * Collation: `utf8mb4_unicode_ci`
* Laravel:

  * Kiểm tra config database charset
* Font:

  * Sử dụng font hỗ trợ tiếng Việt (ví dụ: Roboto, Arial, sans-serif)

---

## 🎨 4. Fix màu chữ UI

### Mục tiêu:

* Toàn bộ text hiển thị rõ ràng, dễ đọc

### Yêu cầu:

* Chuyển màu chữ từ màu nhạt sang:

  * `#000000` (đen) hoặc màu tương phản tốt
* Áp dụng cho:

  * Text nội dung
  * Form input
  * Table
  * Button (nếu cần)

### Kỹ thuật:

* Kiểm tra và chỉnh sửa:

  * CSS global
  * Tailwind / Bootstrap classes
* Tránh override gây lỗi theme

---

## ⚠️ Yêu cầu chung

* Không phá vỡ các chức năng hiện tái
* Code sạch, dễ maintain
* Tuân theo chuẩn Laravel (MVC)
* Comment rõ ràng các phần code mới
* Nếu có nhiều cách làm → chọn cách tối ưu và phổ biến nhất

---

## 📦 Kết quả mong muốn

* Danh sách file đã chỉnh sửa/thêm mới
* Code đầy đủ cho từng phần (Controller, Model, Migration, View)
* Hướng dẫn cách tích hợp vào hệ thống hiện tại
