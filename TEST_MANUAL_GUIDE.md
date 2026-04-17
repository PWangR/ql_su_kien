# 🎮 Hướng dẫn Test Thủ công bằng Giao diện

## 🎯 Test Scenario 1: Điểm danh 2 lần → Cộng điểm ✅

### Bước 1: Tạo sự kiện test

1. Đăng nhập với tài khoản Admin
2. Vào **Admin > Sự kiện > Tạo mới**
3. Điền form:
    - **Tên sự kiện**: `Test Điểm danh 2 lần`
    - **Thời gian bắt đầu**: `Hôm nay 09:00`
    - **Thời gian kết thúc**: `Hôm nay 12:00`
    - **Số lượng tối đa**: `30`
    - **Điểm cộng**: `10`
    - **Loại sự kiện**: (chọn bất kỳ)
4. Click **Tạo sự kiện**

✅ Ghi lại **ID sự kiện** (ví dụ: 123)

---

### Bước 2: Tạo sinh viên test (nếu chưa có)

1. Vào **Admin > Người dùng > Thêm mới**
2. Điền:
    - **Email**: `test1@example.com`
    - **Mã sinh viên**: `64130001`
    - **Họ tên**: `Nguyễn Văn Test`
    - **Vai trò**: `Sinh viên`
    - **Mật khẩu**: `password`
3. Click **Tạo**

✅ Ghi lại **ID sinh viên**: `64130001`

---

### Bước 3: Đăng ký sự kiện

1. Đăng xuất admin
2. Đăng nhập với tài khoản `test1@example.com / password`
3. Tìm sự kiện vừa tạo (`Test Điểm danh 2 lần`)
4. Click **Đăng ký**

✅ Kiểm tra message: "Đăng ký thành công"

---

### Bước 4A: Điểm danh đầu buổi

1. Đăng nhập lại Admin
2. Vào **Admin > Điểm danh > Quét QR**
3. Nhập:
    - **Mã sinh viên**: `64130001`
    - **Sự kiện**: `Test Điểm danh 2 lần`
    - **Loại điểm danh**: `dau_buoi` (Đầu buổi)
4. Click **Điểm danh**

✅ Kiểm tra message: "Điểm danh đầu buổi thẻ sinh viên thành công!"

**Kiểm tra DB:**

```sql
SELECT * FROM chi_tiet_diem_danh
WHERE ma_sinh_vien = '64130001';
-- Kết quả: 1 row (dau_buoi)
```

---

### Bước 4B: Điểm danh cuối buổi

1. Vẫn ở Admin > Điểm danh > Quét QR
2. Nhập:
    - **Mã sinh viên**: `64130001`
    - **Sự kiện**: `Test Điểm danh 2 lần`
    - **Loại điểm danh**: `cuoi_buoi` (Cuối buổi)
3. Click **Điểm danh**

✅ Kiểm tra message: "Điểm danh cuối buổi thẻ sinh viên thành công!"

**Kiểm tra DB:**

```sql
SELECT * FROM chi_tiet_diem_danh
WHERE ma_sinh_vien = '64130001';
-- Kết quả: 2 rows (1 dau_buoi, 1 cuoi_buoi)

SELECT * FROM lich_su_diem
WHERE ma_sinh_vien = '64130001';
-- Kết quả: 1 row (cộng 10 điểm)
```

---

### Bước 5: Kiểm tra kết quả

1. Đăng nhập lại sinh viên `test1@example.com`
2. Click **Lịch sử tham gia**
3. Kiểm tra sự kiện vừa test:
    - [ ] Trạng thái: **"Đã tham gia"** ✅
    - [ ] Điểm: **+10** ✅
    - [ ] Chi tiết điểm danh (nếu UI hỗ trợ):
        - Đầu buổi ✅
        - Cuối buổi ✅

---

## 🎯 Test Scenario 2: Điểm danh 1 lần → Không cộng điểm ❌

### Bước 1: Tạo sự kiện test 2

1. Admin > Sự kiện > Tạo mới
2. Điền:
    - **Tên**: `Test Điểm danh 1 lần`
    - **Thời gian bắt đầu**: `Hôm nay 14:00`
    - **Thời gian kết thúc**: `Hôm nay 16:00`
    - **Điểm cộng**: `5`
3. Click **Tạo**

✅ Ghi lại ID sự kiện

---

### Bước 2: Tạo sinh viên test 2

1. Admin > Người dùng > Thêm mới
2. Điền:
    - **Email**: `test2@example.com`
    - **Mã sinh viên**: `64130002`
    - **Họ tên**: `Trần Thị Test`
3. Click **Tạo**

---

### Bước 3: Đăng ký và điểm danh 1 lần

1. Đăng nhập `test2@example.com`
2. Đăng ký sự kiện `Test Điểm danh 1 lần`
3. Admin > Quét QR:
    - Mã sinh viên: `64130002`
    - Sự kiện: `Test Điểm danh 1 lần`
    - Loại: `dau_buoi`
    - Click **Điểm danh**

✅ Kiểm tra message: "thành công"

---

### Bước 4: Kiểm tra chưa cộng điểm

**Kiểm tra DB ngay lập tức:**

```sql
SELECT * FROM chi_tiet_diem_danh
WHERE ma_sinh_vien = '64130002';
-- Kết quả: 1 row (dau_buoi)

SELECT * FROM lich_su_diem
WHERE ma_sinh_vien = '64130002';
-- Kết quả: 0 rows ❌ (chưa cộng điểm)
```

---

### Bước 5: Chạy Scheduler để update trạng thái

1. Mở Laragon Terminal:

```bash
cd d:\laragon\www\ql_su_kien
php artisan events:update-status
```

2. Kiểm tra lại DB:

```sql
SELECT trang_thai_tham_gia FROM dang_ky
WHERE ma_sinh_vien = '64130002';
-- Kết quả: 'chua_du_dieu_kien' ⚠️
```

---

### Bước 6: Kiểm tra kết quả

1. Đăng nhập sinh viên `test2@example.com`
2. Click **Lịch sử tham gia**
3. Kiểm tra sự kiện:
    - [ ] Trạng thái: **"Chưa đủ điều kiện"** ⚠️
    - [ ] Điểm: **0** (không cộng) ❌
    - [ ] Chi tiết: chỉ 1 lần đầu buổi

---

## 🎯 Test Scenario 3: Không điểm danh → Vắng mặt 🚫

### Bước 1: Tạo sự kiện test 3

1. Admin > Sự kiện > Tạo mới
2. Điền:
    - **Tên**: `Test Vắng mặt`
    - **Thời gian bắt đầu**: `Hôm nay 11:00`
    - **Thời gian kết thúc**: `Hôm nay 13:00`
    - **Điểm cộng**: `8`
3. Click **Tạo**

---

### Bước 2: Tạo sinh viên test 3

1. Admin > Người dùng > Thêm mới
2. Điền:
    - **Email**: `test3@example.com`
    - **Mã sinh viên**: `64130003`
    - **Họ tên**: `Lê Văn Test`
3. Click **Tạo**

---

### Bước 3: Đăng ký nhưng không điểm danh

1. Đăng nhập `test3@example.com`
2. Đăng ký sự kiện `Test Vắng mặt`
3. **Không làm gì thêm** (không điểm danh)

---

### Bước 4: Chạy Scheduler sau khi sự kiện kết thúc

1. Mở Laragon Terminal:

```bash
php artisan events:update-status
```

---

### Bước 5: Kiểm tra kết quả

**Kiểm tra DB:**

```sql
SELECT trang_thai_tham_gia FROM dang_ky
WHERE ma_sinh_vien = '64130003';
-- Kết quả: 'vang_mat' 🚫

SELECT * FROM lich_su_diem
WHERE ma_sinh_vien = '64130003';
-- Kết quả: 0 rows (không cộng điểm)
```

1. Đăng nhập sinh viên `test3@example.com`
2. Click **Lịch sử tham gia**
3. Kiểm tra:
    - [ ] Trạng thái: **"Vắng mặt"** 🚫
    - [ ] Điểm: **0** ❌

---

## 🔗 Test Kiểm tra Hủy Đăng ký

### Bước 1: Khi sự kiện chưa bắt đầu → Có thể hủy ✅

1. Tạo sự kiện test 4:
    - **Thời gian bắt đầu**: `Ngày mai 09:00`
    - Click **Tạo**

2. Tạo sinh viên test 4:
    - **Mã sinh viên**: `64130004`

3. Đăng nhập sinh viên, đăng ký sự kiện

4. Vào trang chi tiết sự kiện → Kiểm tra:
    - [ ] Nút **"Hủy đăng ký"** hiển thị ✅
    - Click nút, kiểm tra message: "Hủy đăng ký thành công"

---

### Bước 2: Khi sự kiện đã bắt đầu → Không thể hủy ❌

1. Sửa sự kiện test 1:
    - **Thời gian bắt đầu**: `5 giờ trước`
    - Update

2. Đăng nhập sinh viên `test1@example.com`

3. Vào trang chi tiết sự kiện test 1 → Kiểm tra:
    - [ ] Nút **"Hủy đăng ký"** disabled ❌
    - [ ] Message: "Không thể hủy (Sự kiện đã bắt đầu)"
    - Thử click → show error "Sự kiện đã bắt đầu"

---

## 📋 Checklist Test

### Scenario 1: Điểm danh 2 lần ✅

- [ ] Tạo sự kiện thành công
- [ ] Tạo sinh viên thành công
- [ ] Đăng ký thành công
- [ ] Điểm danh đầu buổi thành công
- [ ] Kiểm tra DB: 1 row chi_tiet_diem_danh
- [ ] Điểm danh cuối buổi thành công
- [ ] Kiểm tra DB: 2 rows chi_tiet_diem_danh
- [ ] Kiểm tra DB: 1 row lich_su_diem (cộng 10 điểm)
- [ ] Lịch sử: trạng thái "Đã tham gia" ✅
- [ ] Lịch sử: hiển thị +10 điểm ✅

### Scenario 2: Điểm danh 1 lần ❌

- [ ] Tạo sự kiện thành công
- [ ] Tạo sinh viên thành công
- [ ] Đăng ký thành công
- [ ] Điểm danh 1 lần thành công
- [ ] Kiểm tra DB: 1 row chi_tiet_diem_danh (dau_buoi)
- [ ] Kiểm tra DB: 0 rows lich_su_diem (chưa cộng)
- [ ] Chạy scheduler: `php artisan events:update-status`
- [ ] Kiểm tra DB: trạng thái = 'chua_du_dieu_kien'
- [ ] Lịch sử: trạng thái "Chưa đủ điều kiện" ⚠️
- [ ] Lịch sử: hiển thị 0 điểm ❌

### Scenario 3: Không điểm danh 🚫

- [ ] Tạo sự kiện thành công
- [ ] Tạo sinh viên thành công
- [ ] Đăng ký thành công
- [ ] Không làm gì (không điểm danh)
- [ ] Chạy scheduler
- [ ] Kiểm tra DB: trạng thái = 'vang_mat'
- [ ] Lịch sử: trạng thái "Vắng mặt" 🚫
- [ ] Lịch sử: hiển thị 0 điểm ❌

### Hủy Đăng ký

- [ ] Chưa bắt đầu → Nút hiển thị & có thể hủy ✅
- [ ] Đã bắt đầu → Nút disabled ❌

---

## 🎬 Ghi chú

- **Nên test lần lượt từng scenario** (không nên làm cùng lúc)
- **Kiểm tra DB sau mỗi bước** để xác nhận
- **Chạy `php artisan cache:clear`** nếu thấy dữ liệu cũ
- **Kiểm tra logs** nếu có lỗi: `tail -f storage/logs/laravel.log`

---

**Báo lại kết quả mỗi scenario nhé! 🚀**
