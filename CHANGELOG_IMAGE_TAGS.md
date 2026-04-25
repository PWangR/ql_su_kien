# Changelog - Tính Năng Tag Ảnh (Image Tags Feature)

**Ngày**: 25/04/2026
**Phiên bản**: 1.0.0
**Mô tả**: Bổ xung tính năng tag/nhãn cho các tệp media, cho phép phân loại, lọc, và sắp xếp ảnh/video/tài liệu

---

## 📋 Danh Sách Thay Đổi

### ✨ Tính Năng Mới

#### 1. **Model & Database**

- **Tạo mới**: `app/Models/TheAnh.php`
    - Model quản lý các thẻ tên (tags)
    - Relationships: `thuVienDaPhuongTien()` (many-to-many), `nguoiTao()`
    - Scopes: `active()`, `ordered()`
    - Methods: `getContrastColor()` - tính toán màu chữ từ màu nền

- **Tạo mới**: `database/migrations/2026_04_25_000001_create_the_anh_table.php`
    - Bảng `the_anh`: lưu trữ danh sách thẻ tên
    - Cột: `ma_the_anh` (PK), `ten_the` (unique), `mo_ta`, `mau_sac`, `ma_nguoi_tao`, timestamps
    - Support soft delete

- **Tạo mới**: `database/migrations/2026_04_25_000002_create_the_anh_thu_vien_table.php`
    - Bảng pivot: `the_anh_thu_vien` liên kết media với tag (many-to-many)
    - Cột: `id`, `ma_the_anh` (FK), `ma_phuong_tien` (FK), timestamps
    - Unique constraint: một tag chỉ gán một lần cho mỗi media

#### 2. **Controller & Routes**

- **Cập nhật**: `app/Http/Controllers/Admin/MediaController.php`
    - `index()`: Thêm lọc theo `the_anh` và `loai_tep`; eager load `theAnh` relationship
    - `upload()`: Xử lý gắn tag, tạo tag mới, validation cho tag
    - `destroy()`: Thêm detach tags trước khi xóa media
    - **Mới**: `tagsJson()` - trả về danh sách tag (JSON) cho frontend
    - **Mới**: `tagsCreate()` - API tạo tag mới

- **Cập nhật**: `routes/web.php`
    - Thêm route: `GET /admin/media/tags/json` → `media.tags.json`
    - Thêm route: `POST /admin/media/tags/create` → `media.tags.create`

#### 3. **View & UI**

- **Cập nhật**: `resources/views/admin/media/index.blade.php`
    - Thêm **phần lọc**: hiển thị bộ lọc hiện tại, nút xóa lọc
    - Thêm **hiển thị tag**: tags badge với màu sắc trên mỗi card media, click để lọc
    - **Nâng cấp modal upload**:
        - Thêm section "Thẻ tên (Tags)": checkbox list các tag hiện có
        - Thêm section "Tạo thẻ mới": input tên thẻ + color picker
        - Tải động danh sách tag từ server (AJAX)
    - CSS: styles cho tag badges, filter badges, tag selector
    - JavaScript: `loadTags()`, `getContrastColor()`, form validation

#### 4. **Model Relationship**

- **Cập nhật**: `app/Models/ThuVienDaPhuongTien.php`
    - Thêm relationship: `theAnh()` - belongsToMany với TheAnh model

---

## 🔄 Quy Trình Cài Đặt

1. **Chạy migration**:

    ```bash
    php artisan migrate
    ```

2. **(Tùy chọn) Seed dữ liệu mẫu** (nếu có seeder):

    ```bash
    php artisan db:seed
    ```

3. **Xóa cache** (nếu cần):

    ```bash
    php artisan cache:clear
    php artisan config:clear
    ```

4. **Kiểm tra**:
    - Truy cập `/admin/media` → phải thấy giao diện lọc tag mới
    - Nhấn "Upload file" → phải thấy section tag mới

---

## 📊 Database Schema Changes

### Bảng Mới: `the_anh`

```sql
CREATE TABLE `the_anh` (
  `ma_the_anh` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `ten_the` VARCHAR(100) UNIQUE NOT NULL,
  `mo_ta` VARCHAR(255) NULL,
  `mau_sac` VARCHAR(7) DEFAULT '#007bff',
  `ma_nguoi_tao` BIGINT NULL,
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (`ma_nguoi_tao`) REFERENCES `nguoi_dung`(`ma_sinh_vien`),
  INDEX (`ten_the`),
  INDEX (`created_at`)
);
```

### Bảng Mới: `the_anh_thu_vien`

```sql
CREATE TABLE `the_anh_thu_vien` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `ma_the_anh` BIGINT NOT NULL,
  `ma_phuong_tien` BIGINT NOT NULL,
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP,
  FOREIGN KEY (`ma_the_anh`) REFERENCES `the_anh`(`ma_the_anh`) ON DELETE CASCADE,
  FOREIGN KEY (`ma_phuong_tien`) REFERENCES `thu_vien_da_phuong_tien`(`ma_phuong_tien`) ON DELETE CASCADE,
  UNIQUE (`ma_the_anh`, `ma_phuong_tien`),
  INDEX (`ma_the_anh`),
  INDEX (`ma_phuong_tien`)
);
```

---

## 🎯 Validation Rules

### Upload Media:

```php
'the_anh'       => 'nullable|array',
'the_anh.*'     => 'exists:the_anh,ma_the_anh',
'ten_the_moi'   => 'nullable|string|max:100',
'mau_sac_moi'   => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
```

### Create Tag:

```php
'ten_the'  => 'required|string|max:100|unique:the_anh,ten_the',
'mo_ta'    => 'nullable|string|max:255',
'mau_sac'  => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
```

---

## 📝 File Tài Liệu Mới

- **`INSTALLATION_GUIDE_IMAGE_TAGS.md`**: Hướng dẫn chi tiết cài đặt, sử dụng, API
- **`CHANGELOG_IMAGE_TAGS.md`** (file này): Ghi lại các thay đổi

---

## 🔒 Backward Compatibility

✅ **Tương thích ngược** - Không ảnh hưởng đến chức năng cũ:

- Các media cũ không có tag sẽ vẫn hoạt động bình thường
- Upload mà không chọn tag vẫn được phép
- Không bắt buộc phải sử dụng tính năng tag

---

## 🚀 Performance Considerations

- **Database**:
    - Bảng `the_anh` có index trên `ten_the` và `created_at`
    - Pivot table có unique constraint để tránh trùng lặp
    - Foreign keys có cascade delete

- **Frontend**:
    - AJAX load tags: chỉ tải khi mở modal (lazy loading)
    - CSS class naming: tuân theo BEM convention
    - Color calculation: client-side (không cần server request)

---

## 🧪 Testing Recommendations

### Unit Tests:

- [ ] Model TheAnh: relationships, scopes, attributes
- [ ] Model ThuVienDaPhuongTien: relationship with tags
- [ ] MediaController: upload with tags, tag creation

### Feature Tests:

- [ ] Upload file và gắn tag
- [ ] Tạo tag mới khi upload
- [ ] Lọc media theo tag
- [ ] Xóa media (cùng tags)
- [ ] Soft delete tag

### Manual Tests:

- [ ] Giao diện tag hiển thị đúng
- [ ] Color picker hoạt động
- [ ] Filter/search hoạt động đúng
- [ ] Performance với nhiều media

---

## ⚠️ Known Limitations & Future Enhancements

### Hiện Tại:

- Tag quản lý thông qua upload modal (không có dedicated management page)
- Soft delete tag nhưng không có UI khôi phục
- Không có tag suggestions dựa trên AI

### Đề Xuất Phát Triển:

1. Tạo admin panel quản lý tag riêng (CRUD)
2. Tag suggestions dựa trên content
3. Bulk edit tags cho nhiều media
4. Tag statistics & analytics
5. Tag hierarchy (parent-child tags)
6. Collaborative tagging (multiple users)
7. Tag search/autocomplete
8. Export media by tag

---

## 📞 Support & Contact

Nếu gặp vấn đề:

1. Kiểm tra `INSTALLATION_GUIDE_IMAGE_TAGS.md` phần Troubleshooting
2. Xem error logs: `storage/logs/`
3. Kiểm tra console browser (F12)
4. Verify database migrations: `php artisan migrate:status`

---

**Status**: ✅ Production Ready
**Last Updated**: 25/04/2026 v1.0.0
