# Hướng Dẫn Tính Năng Tag Ảnh (Image Tags Feature)

## Tổng Quan

Tính năng tag ảnh cho phép bạn:

- ✅ Gắn nhãn (tag) cho các tệp ảnh, video, tài liệu trong thư viện media
- ✅ Tạo thẻ tên (tag) mới khi upload tệp
- ✅ Lọc/sắp xếp tệp theo thẻ tên
- ✅ Sử dụng màu sắc để phân biệt các thẻ

---

## Cài Đặt

### 1. Chạy Migration

Chạy lệnh sau để tạo bảng `the_anh` (tags) và `the_anh_thu_vien` (pivot table):

```bash
php artisan migrate
```

Điều này sẽ:

- Tạo bảng `the_anh` lưu trữ danh sách các thẻ
- Tạo bảng `the_anh_thu_vien` để liên kết media với tag (many-to-many)
- Tạo các index cần thiết

### 2. Các File Đã Được Tạo/Cập Nhật

**Migrations:**

- `database/migrations/2026_04_25_000001_create_the_anh_table.php` - Bảng chứa các thẻ tên
- `database/migrations/2026_04_25_000002_create_the_anh_thu_vien_table.php` - Bảng liên kết media-tag

**Models:**

- `app/Models/TheAnh.php` - Model quản lý các thẻ tên (NEW)
- `app/Models/ThuVienDaPhuongTien.php` - Cập nhật relationship với TheAnh

**Controllers:**

- `app/Http/Controllers/Admin/MediaController.php` - Cập nhật methods upload, index, thêm tagsJson(), tagsCreate()

**Views:**

- `resources/views/admin/media/index.blade.php` - Cập nhật giao diện hiển thị tag và form upload

**Routes:**

- `routes/web.php` - Thêm routes: `media.tags.json`, `media.tags.create`

---

## Cách Sử Dụng

### A. Upload File Với Tag

1. Vào **Admin → Thư Viện Media**
2. Nhấn nút **"Upload file"** (góc trên phải)
3. Trong modal:
    - **Chọn file**: Bắt buộc
    - **Loại file**: Tự động phát hiện hoặc chọn thủ công
    - **Thẻ tên (Tags)**: Chọn một hoặc nhiều thẻ hiện có
    - **Tạo thẻ mới**: Nhập tên thẻ mới + chọn màu
    - **Công khai**: Tùy chọn

4. Nhấn **"Upload"** để hoàn tất

**Lưu ý:**

- Nếu tạo thẻ mới (`ten_the_moi` không rỗng), thẻ sẽ được tạo và gắn vào file tự động
- Có thể gắn nhiều thẻ cho một file
- Thẻ được lưu với tên người tạo (người upload)

### B. Lọc File Theo Tag

1. Trên trang **Thư Viện Media**, phần **"Lọc"** ở trên cùng
2. Có hai cách:
    - **Nhấn vào tag trên card**: Ấn vào tag hiển thị trên bất kỳ file nào để lọc theo tag đó
    - **Sử dụng bộ lọc**: Xóa bộ lọc bằng nút **"×"** hoặc **"Xóa tất cả"**

3. Các file sẽ được lọc theo tag được chọn

### C. Quản Lý Tag (Backend)

#### Các Method Trong MediaController:

**1. `tagsJson()` - Lấy danh sách tag (JSON)**

```
GET /admin/media/tags/json
Response: [
  {
    "ma_the_anh": 1,
    "ten_the": "Sự kiện quan trọng",
    "mo_ta": null,
    "mau_sac": "#007bff",
    ...
  }
]
```

**2. `tagsCreate()` - Tạo tag mới**

```
POST /admin/media/tags/create
Parameters:
  - ten_the (string, required, max:100, unique)
  - mo_ta (string, optional, max:255)
  - mau_sac (string, optional, hex color format)
```

---

## Cấu Trúc Database

### Bảng: `the_anh` (Tags)

| Column       | Type        | Mô Tả                          |
| ------------ | ----------- | ------------------------------ |
| ma_the_anh   | id (PK)     | Khóa chính                     |
| ten_the      | string(100) | Tên thẻ (duy nhất)             |
| mo_ta        | string(255) | Mô tả thẻ (tùy chọn)           |
| mau_sac      | string(7)   | Mã màu hex (mặc định: #007bff) |
| ma_nguoi_tao | bigint      | FK → nguoi_dung.ma_sinh_vien   |
| created_at   | timestamp   | Thời gian tạo                  |
| updated_at   | timestamp   | Thời gian cập nhật             |
| deleted_at   | timestamp   | Soft delete                    |

### Bảng: `the_anh_thu_vien` (Media-Tag Junction)

| Column                             | Type      | Mô Tả                                       |
| ---------------------------------- | --------- | ------------------------------------------- |
| id                                 | id (PK)   | Khóa chính                                  |
| ma_the_anh                         | bigint    | FK → the_anh.ma_the_anh                     |
| ma_phuong_tien                     | bigint    | FK → thu_vien_da_phuong_tien.ma_phuong_tien |
| created_at                         | timestamp | Thời gian tạo                               |
| updated_at                         | timestamp | Thời gian cập nhật                          |
| unique(ma_the_anh, ma_phuong_tien) | -         | Một tag chỉ gán một lần cho mỗi media       |

---

## Quan Hệ Model (Relationships)

### TheAnh Model

```php
// Một tag có nhiều media
public function thuVienDaPhuongTien()

// Người tạo tag
public function nguoiTao()
```

### ThuVienDaPhuongTien Model

```php
// Một media có nhiều tag
public function theAnh()
```

---

## Các Tính Năng Đặc Biệt

### 1. Lựa Chọn Màu Sắc

- Mỗi tag có màu sắc riêng (hex color)
- Giao diện tự động tính toán màu chữ (trắng/đen) dựa trên độ sáng của màu nền
- Hỗ trợ color picker khi tạo thẻ mới

### 2. Soft Delete

- Thẻ có thể bị xóa mềm (soft delete)
- Các media được gắn với thẻ đã xóa sẽ vẫn giữ nguyên
- Scope `active()` chỉ hiển thị các thẻ chưa bị xóa

### 3. Tải Động Tag

- Khi mở modal upload, danh sách tag được tải từ server (API call)
- Các thẻ mới tạo sẽ tự động xuất hiện trong dropdown

### 4. Validation

- Tên thẻ: duy nhất, tối đa 100 ký tự
- Mô tả: tối đa 255 ký tự
- Mã màu: phải là định dạng hex hợp lệ (#RRGGBB)

---

## Các Scopes Hữu Ích

### Trong Model TheAnh:

```php
// Chỉ lấy các thẻ chưa bị xóa
TheAnh::active()->get();

// Sắp xếp theo tên
TheAnh::ordered()->get();

// Kết hợp
TheAnh::active()->ordered()->get();
```

---

## Ví Dụ Sử Dụng Code

### Thêm tag cho media:

```php
$media = ThuVienDaPhuongTien::find(1);
$media->theAnh()->attach([1, 2, 3]); // Gắn 3 tag
```

### Lấy media theo tag:

```php
$tag = TheAnh::find(1);
$medias = $tag->thuVienDaPhuongTien()->get();
```

### Lọc media theo tag:

```php
$medias = ThuVienDaPhuongTien::whereHas('theAnh', function($q) {
    $q->where('ma_the_anh', 1);
})->get();
```

---

## Troubleshooting

### 1. Bảng không tồn tại

```
Chạy: php artisan migrate
```

### 2. Tag không xuất hiện trong dropdown

```
- Kiểm tra dữ liệu trong bảng `the_anh`
- Kiểm tra route `admin.media.tags.json` hoạt động
- Kiểm tra console browser để xem lỗi JavaScript
```

### 3. Màu tag không hiển thị đúng

```
- Kiểm tra format màu hex trong database (phải là #RRGGBB)
- Kiểm tra method getContrastColor() hoạt động đúng
```

### 4. Không thể tạo tag mới khi upload

```
- Kiểm tra trường `ten_the_moi` có giá trị không
- Kiểm tra validator trong MediaController::upload()
- Xem thông báo lỗi trong response
```

---

## API Endpoints

| Method | Endpoint                 | Chức Năng                |
| ------ | ------------------------ | ------------------------ |
| GET    | /admin/media             | Hiển thị thư viện media  |
| POST   | /admin/media/upload      | Upload file với tag      |
| DELETE | /admin/media/{id}        | Xóa file                 |
| GET    | /admin/media/tags/json   | Lấy danh sách tag (JSON) |
| POST   | /admin/media/tags/create | Tạo tag mới              |

---

## Lưu Ý Quan Trọng

1. **Backup Database**: Trước khi chạy migration, hãy backup database
2. **Permission**: Đảm bảo user đã login có quyền upload
3. **Storage**: Kiểm tra không gian lưu trữ còn đủ
4. **Performance**: Nếu có hàng ngàn media, nên thêm pagination/infinite scroll
5. **Soft Delete**: Thẻ bị xóa vẫn lưu trong DB, có thể khôi phục

---

## Phát Triển Tiếp Theo (Đề Xuất)

- [ ] Tạo quản lý tag riêng (CRUD admin)
- [ ] Thêm "người dùng yêu thích tag"
- [ ] Ghi nhập tag mà người dùng hay dùng nhất
- [ ] Bulk edit tags cho nhiều file cùng lúc
- [ ] Export/import danh sách media theo tag
- [ ] Tag automation dựa trên tên file hoặc loại file

---

**Ngày tạo**: 25/04/2026
**Phiên bản**: 1.0
