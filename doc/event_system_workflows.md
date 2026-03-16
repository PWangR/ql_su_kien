# WORKFLOW & DIAGRAMS -- EVENT MANAGEMENT SYSTEM

Tài liệu này mô tả luồng hoạt động chi tiết của các chức năng chính
trong hệ thống Website Quản lý sự kiện Khoa CNTT -- Đại học Nha Trang.

Các phần bao gồm: - Workflow chức năng - Use Case Scenario - Activity
Diagram (PlantUML) - Sequence Diagram (PlantUML)

Lưu ý: Tính năng **bầu cử** được loại bỏ theo yêu cầu.

------------------------------------------------------------------------

# 1. Quản lý người dùng (Admin)

## Use Case Scenario

**Actor:** Admin\
**Mô tả:** Admin quản lý tài khoản trong hệ thống.

### Basic Flow

1.  Admin đăng nhập hệ thống.
2.  Truy cập trang quản lý người dùng.
3.  Hệ thống hiển thị danh sách người dùng.
4.  Admin chọn một chức năng:
    -   Tạo người dùng
    -   Chỉnh sửa thông tin
    -   Khóa / mở khóa
    -   Xóa tài khoản
5.  Hệ thống kiểm tra dữ liệu.
6.  Lưu thay đổi vào cơ sở dữ liệu.
7.  Cập nhật danh sách người dùng.

### Alternative Flow

-   Nếu dữ liệu không hợp lệ → hệ thống yêu cầu nhập lại.

------------------------------------------------------------------------

## Activity Diagram

``` plantuml
@startuml
start
:Admin login;
:Open user management;
:View user list;
if (Action?) then (Create)
:Enter user info;
elseif (Edit)
:Update info;
elseif (Delete)
:Delete account;
endif
:Save to database;
:Refresh list;
stop
@enduml
```

------------------------------------------------------------------------

# 2. Quản lý sự kiện

## Use Case Scenario

**Actor:** Admin

### Basic Flow

1.  Admin đăng nhập.
2.  Mở trang quản lý sự kiện.
3.  Chọn tạo sự kiện.
4.  Nhập thông tin:
    -   tên
    -   thời gian
    -   địa điểm
    -   mô tả
5.  Lưu sự kiện.
6.  Hệ thống gửi thông báo cho người dùng.

------------------------------------------------------------------------

## Sequence Diagram

``` plantuml
@startuml
Admin -> WebApp : Create event
WebApp -> Server : Send event data
Server -> Database : Save event
Database --> Server : Success
Server --> WebApp : Event created
WebApp --> Admin : Confirmation
@enduml
```

------------------------------------------------------------------------

# 3. Đăng ký tham gia sự kiện

## Workflow

1.  Người dùng đăng nhập.
2.  Mở danh sách sự kiện.
3.  Chọn sự kiện.
4.  Nhấn đăng ký.
5.  Hệ thống kiểm tra:
    -   đã đăng ký chưa
    -   còn chỗ không
6.  Nếu hợp lệ:
    -   lưu đăng ký
7.  Trả thông báo thành công.

------------------------------------------------------------------------

## Activity Diagram

``` plantuml
@startuml
start
:User login;
:View events;
:Select event;
:Register;
if (Valid?) then (Yes)
:Save registration;
:Show success;
else (No)
:Show error;
endif
stop
@enduml
```

------------------------------------------------------------------------

# 4. Điểm danh QR

## Workflow tạo QR (Admin)

1.  Admin mở trang sự kiện.
2.  Chọn tạo QR.
3.  Thiết lập số lượng QR.
4.  Hệ thống tạo mã QR.
5.  Lưu vào database.
6.  Hiển thị QR cho admin.

------------------------------------------------------------------------

## Workflow quét QR (Sinh viên)

1.  Sinh viên mở chức năng quét QR.
2.  Camera bật.
3.  Quét mã QR.
4.  Hệ thống kiểm tra:
    -   mã hợp lệ
    -   chưa điểm danh
5.  Lưu điểm danh.
6.  Hiển thị kết quả.

------------------------------------------------------------------------

## Sequence Diagram

``` plantuml
@startuml
Student -> MobileApp : Scan QR
MobileApp -> Server : Send QR data
Server -> Database : Validate QR
Database --> Server : Result
Server -> Database : Save attendance
Server --> MobileApp : Attendance success
@enduml
```

------------------------------------------------------------------------

# 5. Quản lý điểm tham gia

## Workflow

1.  Admin thiết lập điểm cho sự kiện.
2.  Khi sinh viên điểm danh:
3.  Hệ thống kiểm tra điều kiện.
4.  Cộng điểm.
5.  Lưu lịch sử điểm.

------------------------------------------------------------------------

# 6. Quản lý thư viện media

## Workflow

1.  Admin mở thư viện media.
2.  Upload file.
3.  Hệ thống kiểm tra định dạng.
4.  Lưu file.
5.  Hiển thị trong thư viện.
6.  Admin có thể:
    -   chỉnh sửa
    -   xóa
    -   gắn vào sự kiện.

------------------------------------------------------------------------

# 7. Quản lý template bài đăng

## Workflow

1.  Admin mở trang template.
2.  Tạo template mới.
3.  Nhập nội dung mẫu.
4.  Lưu template.
5.  Khi tạo sự kiện → chọn template.

------------------------------------------------------------------------

# 8. Thông báo hệ thống

## Workflow

1.  Sự kiện được tạo hoặc chỉnh sửa.
2.  Server tạo thông báo.
3.  Gửi qua hệ thống realtime.
4.  Người dùng nhận thông báo.

------------------------------------------------------------------------

# 9. Chatbot hỗ trợ

## Workflow

1.  Người dùng mở chatbot.
2.  Nhập câu hỏi.
3.  Chatbot xử lý câu hỏi.
4.  Truy vấn dữ liệu hệ thống.
5.  Trả câu trả lời.

------------------------------------------------------------------------

# Tổng kết

Hệ thống bao gồm các module chính:

-   Quản lý người dùng
-   Quản lý sự kiện
-   Đăng ký tham gia
-   Điểm danh QR
-   Quản lý điểm
-   Thư viện media
-   Template bài đăng
-   Thông báo realtime
-   Chatbot hỗ trợ

Các luồng hoạt động trên mô tả cách các actor tương tác với hệ thống và
cách hệ thống xử lý nghiệp vụ.
