# ✅ Thiết kế lại Luồng Quản lý Đăng ký Sự kiện - Hướng dẫn Triển khai

## 📋 Tổng quan các thay đổi

### 1. **Cơ sở dữ liệu (Database)**

#### Bảng mới: `chi_tiet_diem_danh`

```
- ma_chi_tiet_diem_danh (id)
- ma_dang_ky (FK)
- ma_su_kien (FK)
- ma_sinh_vien (FK)
- loai_diem_danh: enum['dau_buoi', 'cuoi_buoi'] - Loại điểm danh
- diem_danh_at: timestamp - Thời gian điểm danh
```

#### Thay đổi bảng `dang_ky`:

- Thêm cột `created_at` và `updated_at` (kích hoạt timestamps)
- Cập nhật enum `trang_thai_tham_gia`:
    - **Cũ**: ['da_dang_ky','da_tham_gia','vang_mat','huy']
    - **Mới**: ['da_dang_ky','da_tham_gia','vang_mat','chua_du_dieu_kien','huy']

---

## 🔄 Luồng mới

### **Luồng 1: Đủ điều kiện cộng điểm (2 lần điểm danh)**

```
1. Thông báo sự kiện
2. Người dùng đăng ký → trạng thái: "da_dang_ky"
3. Điểm danh đầu buổi → tạo chi_tiet_diem_danh (dau_buoi)
4. Điểm danh cuối buổi → tạo chi_tiet_diem_danh (cuoi_buoi)
5. Hệ thống kiểm tra: nếu >= 2 lần → cộng điểm + tạo lich_su_diem
6. Chuyển trạng thái: "da_tham_gia"
7. Lưu lịch sử tham gia và điểm ✅
```

### **Luồng 2: Không đủ điều kiện cộng điểm (< 2 lần điểm danh)**

```
1. Thông báo sự kiện
2. Người dùng đăng ký → trạng thái: "da_dang_ky"
3. Điểm danh 1 lần (ví dụ: chỉ đầu buổi)
4. Hết thời gian sự kiện
5. Command tự động cập nhật trạng thái
6. Chuyển trạng thái: "chua_du_dieu_kien" ⚠️
7. Không cộng điểm ❌
```

### **Luồng 3: Không tham gia (Vắng mặt)**

```
1. Thông báo sự kiện
2. Người dùng đăng ký → trạng thái: "da_dang_ky"
3. Người dùng không tham gia (không điểm danh)
4. Hết thời gian sự kiện
5. Command tự động cập nhật trạng thái
6. Chuyển trạng thái: "vang_mat"
7. Không cộng điểm ❌
```

---

## 🚀 Hướng dẫn triển khai

### **Bước 1: Chạy Migrations**

```bash
# Mở Laragon Terminal (hoặc CMD trong thư mục ql_su_kien)
php artisan migrate
```

Nếu gặp lỗi, chạy:

```bash
php artisan migrate --force
```

### **Bước 2: Cấu hình Scheduled Tasks** (Optional)

Thêm vào `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Chạy every 5 minutes để cập nhật trạng thái sự kiện kết thúc
    $schedule->command('events:update-status')->everyFiveMinutes();
}
```

### **Bước 3: Test Luồng Mới**

#### Test Luồng 1 - Đủ điều kiện:

```bash
# 1. Tạo sự kiện test
# 2. Đăng ký
# 3. Điểm danh 2 lần (dau_buoi + cuoi_buoi)
# 4. Kiểm tra database:
# - chi_tiet_diem_danh: 2 records
# - lich_su_diem: 1 record (cộng điểm)
# - dang_ky: trạng thái = "da_tham_gia"
```

#### Test Luồng 2 - Không đủ:

```bash
# 1. Tạo sự kiện test
# 2. Đăng ký
# 3. Điểm danh 1 lần
# 4. Sửa thời gian kết thúc sự kiện thành quá khứ
# 5. Chạy: php artisan events:update-status
# 6. Kiểm tra: trạng thái = "chua_du_dieu_kien", lich_su_diem = 0 records
```

---

## 📝 Các hàm/API sử dụng

### **Đăng ký sự kiện** (Không đổi)

```
POST /api/registrations/register
{
    "ma_su_kien": 1
}
```

**Lưu ý**: Chỉ cho phép khi sự kiện chưa bắt đầu

### **Hủy đăng ký** (Cập nhật)

```
POST /api/registrations/cancel
{
    "ma_su_kien": 1
}
```

**Lưu ý**: Chỉ cho phép khi sự kiện chưa bắt đầu

### **Điểm danh (Cập nhật)**

```
POST /events/process-scanner
{
    "action": "diem_danh",
    "ma_su_kien": 1,
    "diff": 0,
    "loai_diem_danh": "dau_buoi"  // ← Thêm tham số này
}
```

**Giá trị**: `dau_buoi` hoặc `cuoi_buoi`

### **Lịch sử tham gia** (Không đổi)

```
GET /api/registrations/history
```

**Trả về**: Thêm `chi_tiet_diem_danh` relationship

### **Admin Điểm danh** (Cập nhật)

```
POST /admin/diem-danh/process-scanner
{
    "mssv": "12345678",
    "ma_su_kien": 1,
    "loai_diem_danh": "dau_buoi"  // ← Thêm tham số này
}
```

---

## 🛠️ Command mới

### **Cập nhật trạng thái sự kiện kết thúc**

```bash
# Cập nhật tất cả sự kiện đã kết thúc
php artisan events:update-status

# Cập nhật sự kiện cụ thể
php artisan events:update-status --event-id=1
```

---

## 📊 Ví dụ Schema

### **Bảng chi_tiet_diem_danh**

| ma_chi_tiet_diem_danh | ma_dang_ky | ma_su_kien | ma_sinh_vien | loai_diem_danh | diem_danh_at        |
| --------------------- | ---------- | ---------- | ------------ | -------------- | ------------------- |
| 1                     | 5          | 10         | 64131940     | dau_buoi       | 2026-04-17 08:00:00 |
| 2                     | 5          | 10         | 64131940     | cuoi_buoi      | 2026-04-17 11:30:00 |

### **Cập nhật bảng dang_ky**

| ma_dang_ky | ma_sinh_vien | ma_su_kien | trang_thai_tham_gia | thoi_gian_dang_ky   | created_at          |
| ---------- | ------------ | ---------- | ------------------- | ------------------- | ------------------- |
| 5          | 64131940     | 10         | da_tham_gia         | 2026-04-16 10:00:00 | 2026-04-17 08:00:00 |

### **Bảng lich_su_diem (cộng điểm)**

| ma_lich_su_diem | ma_sinh_vien | ma_dang_ky | diem | nguon            | loai_log |
| --------------- | ------------ | ---------- | ---- | ---------------- | -------- |
| 123             | 64131940     | 5          | 10   | tham_gia_su_kien | diem     |

---

## ⚠️ Breaking Changes

1. ❌ Người dùng **không thể hủy đăng ký** khi sự kiện đã bắt đầu
2. ✅ Tự động cấp điểm chỉ khi **>= 2 lần điểm danh**
3. 📊 Phân biệt rõ ràng 3 trạng thái:
    - `chua_du_dieu_kien`: Không đủ điểm danh
    - `vang_mat`: Không tham gia làm gì
    - `da_tham_gia`: Đã đủ điều kiện cộng điểm

---

## 🔗 Files thay đổi

### **Migrations**

- ✅ `2026_04_17_add_created_at_to_dang_ky_table.php` - Thêm timestamps
- ✅ `2026_04_17_create_chi_tiet_diem_danh_table.php` - Tạo bảng chi tiết
- ✅ `2026_04_17_add_chua_du_dieu_kien_status_to_dang_ky.php` - Cập nhật enum

### **Models**

- ✅ `app/Models/DangKy.php` - Thêm: chiTietDiemDanh(), so_lan_diem_danh, etc
- ✅ `app/Models/ChiTietDiemDanh.php` - Model mới
- ✅ `app/Models/SuKien.php` - Không thay đổi (đã có thời gian)

### **Services**

- ✅ `app/Services/RegistrationService.php` - Cập nhật logic toàn bộ

### **Controllers**

- ✅ `app/Http/Controllers/EventController.php` - Thêm loai_diem_danh param
- ✅ `app/Http/Controllers/Admin/DiemDanhController.php` - Thêm loai_diem_danh param

### **Commands**

- ✅ `app/Console/Commands/UpdateEventStatusCommand.php` - Command mới

---

## 📞 Support

Nếu gặp vấn đề:

1. Kiểm tra migrations đã chạy: `php artisan migrate:status`
2. Clear cache: `php artisan cache:clear`
3. Xem logs: `storage/logs/laravel.log`

---

**✅ Hoàn thành!** Hệ thống đăng ký sự kiện đã được thiết kế lại theo yêu cầu.
