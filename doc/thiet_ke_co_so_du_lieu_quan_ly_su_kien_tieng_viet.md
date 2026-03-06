# THIẾT KẾ CƠ SỞ DỮ LIỆU

## Hệ thống Quản lý Sự kiện -- Khoa Công nghệ Thông tin

**Đơn vị:** Trường Đại học Nha Trang

---

# I. Nguyên tắc thiết kế

- Tuân thủ chuẩn chuẩn hóa dữ liệu mức 3 (3NF)
- Tách biệt dữ liệu theo từng thực thể rõ ràng
- Sử dụng khóa chính (PK) và khóa ngoại (FK) với ràng buộc ON DELETE/ON UPDATE rõ ràng
- Thêm kiểu dữ liệu, ràng buộc NOT NULL, UNIQUE, DEFAULT, INDEX cho tất cả cột
- Sử dụng soft delete (cột `deleted_at`) cho các bảng chính để đảm bảo tính toàn vẹn dữ liệu
- Đảm bảo tính mở rộng, dễ bảo trì và phù hợp triển khai thực tế
- Thêm trigger và view hỗ trợ báo cáo khi cần

---

# II. Danh sách các bảng dữ liệu

1.  Bảng vai trò  
2.  Bảng người dùng  
3.  Bảng loại sự kiện  
4.  Bảng sự kiện  
5.  Bảng đăng ký  
6.  Bảng lịch sử điểm  
7.  Bảng thư viện đa phương tiện  
8.  Bảng mẫu bài đăng  
9.  Bảng thông báo  
10. Bảng lịch sử chatbot  
11. Bảng nhật ký hệ thống  

---

# III. Chi tiết từng bảng

## 1. Bảng vai trò

- ma_vai_tro (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ten_vai_tro (VARCHAR(50), Tên vai trò, duy nhất, NOT NULL)  
- mo_ta (TEXT, Mô tả)  
- thoi_gian_tao (TIMESTAMP, Thời gian tạo, DEFAULT CURRENT_TIMESTAMP)  
- thoi_gian_cap_nhat (TIMESTAMP, Thời gian cập nhật, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)  

**Chức năng:** Quản lý các vai trò trong hệ thống như Quản trị viên, Sinh viên.  
**Gợi ý index:** UNIQUE INDEX idx_ten_vai_tro (ten_vai_tro)

---

## 2. Bảng người dùng

- ma_nguoi_dung (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ma_vai_tro (INT, Khóa ngoại tham chiếu vai_tro.ma_vai_tro, NOT NULL)  
- ma_sinh_vien (VARCHAR(20), Mã sinh viên, duy nhất, NOT NULL)  
- ho_ten (VARCHAR(100), Họ và tên, NOT NULL)  
- email (VARCHAR(100), Email, duy nhất, NOT NULL)  
- mat_khau (VARCHAR(255), Mật khẩu đã mã hóa, NOT NULL)  
- so_dien_thoai (VARCHAR(15), Số điện thoại)  
- trang_thai (ENUM('hoat_dong', 'khong_hoat_dong', 'bi_khoa'), Trạng thái hoạt động, DEFAULT 'hoat_dong')  
- duong_dan_anh (VARCHAR(255), Đường dẫn ảnh đại diện)  
- thoi_gian_tao (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  
- thoi_gian_cap_nhat (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)  
- deleted_at (TIMESTAMP, NULL, Soft delete)  

**Chức năng:** Lưu trữ thông tin tài khoản người dùng.  
**Gợi ý index:** UNIQUE INDEX idx_email (email), UNIQUE INDEX idx_ma_sinh_vien (ma_sinh_vien), INDEX idx_ma_vai_tro (ma_vai_tro)

---

## 3. Bảng loại sự kiện

- ma_loai_su_kien (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ten_loai (VARCHAR(50), Tên loại sự kiện, duy nhất, NOT NULL)  
- mo_ta (TEXT, Mô tả)  
- thoi_gian_tao (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  
- thoi_gian_cap_nhat (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)  

**Chức năng:** Quản lý danh sách các loại sự kiện (Hội thảo, Hội thao, Tình nguyện, Seminar…).  
**Gợi ý index:** UNIQUE INDEX idx_ten_loai (ten_loai)

---

## 4. Bảng sự kiện

- ma_su_kien (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ten_su_kien (VARCHAR(200), Tên sự kiện, NOT NULL)  
- mo_ta_chi_tiet (TEXT, Mô tả chi tiết)  
- ma_loai_su_kien (INT, Khóa ngoại tham chiếu loai_su_kien.ma_loai_su_kien, NOT NULL)  
- thoi_gian_bat_dau (DATETIME, NOT NULL)  
- thoi_gian_ket_thuc (DATETIME, NOT NULL)  
- dia_diem (VARCHAR(200), Địa điểm tổ chức)  
- so_luong_toi_da (INT, Số lượng tối đa, DEFAULT 0)  
- so_luong_hien_tai (INT, Số lượng hiện tại, DEFAULT 0)  
- diem_cong (INT, Điểm cộng khi tham gia, DEFAULT 0)  
- ma_nguoi_tao (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung)  
- ma_nguoi_to_chuc (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung)  
- trang_thai (ENUM('sap_to_chuc', 'dang_dien_ra', 'da_ket_thuc', 'huy'), DEFAULT 'sap_to_chuc')  
- thoi_gian_tao (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  
- thoi_gian_cap_nhat (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)  
- deleted_at (TIMESTAMP, NULL, Soft delete)  

**Chức năng:** Quản lý toàn bộ thông tin sự kiện.  
**Gợi ý index:** INDEX idx_thoi_gian (thoi_gian_bat_dau, thoi_gian_ket_thuc), INDEX idx_trang_thai (trang_thai), INDEX idx_ma_loai_su_kien (ma_loai_su_kien)

---

## 5. Bảng đăng ký

- ma_dang_ky (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ma_nguoi_dung (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung, NOT NULL)  
- ma_su_kien (INT, Khóa ngoại tham chiếu su_kien.ma_su_kien, NOT NULL)  
- thoi_gian_dang_ky (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  
- trang_thai_tham_gia (ENUM('da_dang_ky', 'da_tham_gia', 'vang_mat', 'huy'), DEFAULT 'da_dang_ky')  
- deleted_at (TIMESTAMP, NULL, Soft delete)  

**Ràng buộc duy nhất:** UNIQUE KEY uk_dang_ky (ma_nguoi_dung, ma_su_kien)  
**Chức năng:** Quản lý mối quan hệ nhiều-nhiều giữa người dùng và sự kiện.

---

## 6. Bảng lịch sử điểm

- ma_lich_su_diem (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ma_nguoi_dung (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung, NOT NULL)  
- ma_su_kien (INT, Khóa ngoại tham chiếu su_kien.ma_su_kien)  
- ma_dang_ky (INT, Khóa ngoại tham chiếu dang_ky.ma_dang_ky, NULL)  
- diem (INT, NOT NULL)  
- nguon (ENUM('tham_gia_su_kien', 'thuong_them', 'phat_tru'), DEFAULT 'tham_gia_su_kien')  
- thoi_gian_ghi_nhan (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  

**Chức năng:** Lưu lịch sử tích lũy điểm của sinh viên.  
**Gợi ý index:** INDEX idx_ma_nguoi_dung (ma_nguoi_dung), INDEX idx_ma_dang_ky (ma_dang_ky)

---

## 7. Bảng thư viện đa phương tiện

- ma_phuong_tien (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ma_su_kien (INT, Khóa ngoại tham chiếu su_kien.ma_su_kien)  
- ma_nguoi_tai_len (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung)  
- ten_tep (VARCHAR(255))  
- duong_dan_tep (VARCHAR(500), NOT NULL)  
- loai_tep (ENUM('hinh_anh', 'video', 'tai_lieu', 'khac'))  
- kich_thuoc (BIGINT, Dung lượng byte)  
- la_cong_khai (BOOLEAN, DEFAULT FALSE)  
- thoi_gian_tao (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  
- deleted_at (TIMESTAMP, NULL, Soft delete)  

**Chức năng:** Lưu trữ hình ảnh, video, tài liệu phục vụ sự kiện.

---

## 8. Bảng mẫu bài đăng

- ma_mau (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ten_mau (VARCHAR(100), NOT NULL)  
- noi_dung (TEXT, NOT NULL)  
- ma_nguoi_tao (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung)  
- ma_loai_su_kien (INT, Khóa ngoại tham chiếu loai_su_kien.ma_loai_su_kien)  
- thoi_gian_tao (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  
- thoi_gian_cap_nhat (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)  
- deleted_at (TIMESTAMP, NULL, Soft delete)  

**Chức năng:** Lưu các mẫu nội dung để sử dụng lại khi tạo sự kiện.

---

## 9. Bảng thông báo

- ma_thong_bao (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ma_nguoi_dung (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung, NOT NULL)  
- tieu_de (VARCHAR(200), NOT NULL)  
- noi_dung (TEXT, NOT NULL)  
- da_doc (BOOLEAN, DEFAULT FALSE)  
- loai_thong_bao (ENUM('he_thong', 'nhac_nho_su_kien', 'cap_nhat_diem', 'khac'))  
- ma_su_kien_lien_quan (INT, Khóa ngoại tham chiếu su_kien.ma_su_kien, NULL)  
- thoi_gian_tao (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  

**Chức năng:** Gửi và quản lý thông báo đến người dùng.  
**Gợi ý index:** INDEX idx_ma_nguoi_dung_da_doc (ma_nguoi_dung, da_doc)

---

## 10. Bảng lịch sử chatbot

- ma_lich_su (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ma_nguoi_dung (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung)  
- cau_hoi (TEXT, NOT NULL)  
- cau_tra_loi (TEXT, NOT NULL)  
- thoi_gian_tao (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  

**Chức năng:** Lưu lịch sử trao đổi giữa người dùng và chatbot.

---

## 11. Bảng nhật ký hệ thống

- ma_nhat_ky (INT, Khóa chính, AUTO_INCREMENT, NOT NULL)  
- ma_nguoi_dung (INT, Khóa ngoại tham chiếu nguoi_dung.ma_nguoi_dung)  
- hanh_dong (VARCHAR(100), NOT NULL)  
- mo_ta_chi_tiet (TEXT)  
- thoi_gian_tao (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  

**Chức năng:** Ghi nhận các thao tác quan trọng trong hệ thống để kiểm tra và bảo mật.

---

# IV. Mối quan hệ tổng quát giữa các bảng

- Một vai trò có nhiều người dùng  
- Một loại sự kiện có nhiều sự kiện  
- Một người dùng có thể đăng ký nhiều sự kiện (qua bảng đăng ký)  
- Một sự kiện có nhiều người đăng ký (qua bảng đăng ký)  
- Một người dùng có nhiều thông báo  
- Một người dùng có nhiều bản ghi lịch sử điểm, lịch sử chatbot và nhật ký hệ thống  
- Một sự kiện có nhiều phương tiện đa phương tiện  
- Một mẫu bài đăng có thể thuộc một loại sự kiện  

---

**Ghi chú bổ sung:**
- Tất cả bảng chính đều có `deleted_at` để hỗ trợ soft delete.
- Nên tạo Trigger tự động cập nhật `so_luong_hien_tai` khi insert/delete vào bảng đăng ký.
- Nên tạo View cho các báo cáo phổ biến (tổng điểm sinh viên, danh sách sự kiện sắp diễn ra…).
