# TÀI LIỆU MÔ TẢ YÊU CẦU THIẾT KẾ FRONTEND

## Hệ thống: Website Quản Lý Sự Kiện -- Khoa CNTT, Đại học Nha Trang

Nền tảng triển khai: **PHP (Laravel) -- chạy trên môi trường Laragon**

------------------------------------------------------------------------

# 1. Mục tiêu tài liệu

Tài liệu này mô tả **chi tiết yêu cầu thiết kế giao diện (Frontend)**
cho hệ thống website quản lý sự kiện.\
Mục tiêu là giúp **lập trình viên frontend hoặc designer có thể hình
dung rõ giao diện và tái tạo lại toàn bộ hệ thống** chỉ dựa vào mô tả.

Bao gồm: - Bố cục layout - Thành phần giao diện - Hành vi người dùng -
Dữ liệu hiển thị - Luồng tương tác

------------------------------------------------------------------------

# 2. Nguyên tắc thiết kế chung

## 2.1 Phong cách giao diện

Phong cách: - Hiện đại - Tối giản - Tập trung vào nội dung

Màu sắc đề xuất:

  Thành phần   Màu
  ------------ ---------
  Primary      #2563EB
  Secondary    #0F172A
  Background   #F8FAFC
  Card         #FFFFFF
  Danger       #EF4444
  Success      #22C55E

Font đề xuất:

-   Font chính: **Roboto / Inter**
-   Font tiêu đề: **Montserrat**
-   Font dự phòng: Arial, sans-serif

------------------------------------------------------------------------

# 3. Layout tổng thể Website

Tất cả các trang đều sử dụng **layout chung**.

## 3.1 Header

Header nằm ở **top website**.

Chiều cao: \~70px

Thành phần:

    -----------------------------------------------------
    Logo | Trang chủ | Sự kiện | Lịch sử | Thông báo | Avatar
    -----------------------------------------------------

Chi tiết:

Logo - nằm bên trái - click → quay về trang chủ

Menu điều hướng

-   Trang chủ
-   Sự kiện
-   Lịch sử tham gia
-   Thông báo

Avatar người dùng - nằm góc phải - click mở dropdown

Dropdown gồm:

-   Hồ sơ cá nhân
-   Cài đặt giao diện
-   Đăng xuất

------------------------------------------------------------------------

# 3.2 Sidebar (chỉ admin)

Admin có sidebar bên trái.

    --------------------------------
    Dashboard
    Quản lý sự kiện
    Quản lý người dùng
    Thư viện media
    Template bài đăng
    Thống kê
    --------------------------------

Chiều rộng: 240px

------------------------------------------------------------------------

# 3.3 Footer

Footer hiển thị cuối trang.

Nội dung:

-   Copyright
-   Thông tin khoa CNTT
-   Email liên hệ

------------------------------------------------------------------------

# 4. Trang Đăng Nhập

URL:

    /login

## Bố cục

Trang dạng **card login ở giữa màn hình**

    ----------------------------------
            LOGO
    ----------------------------------
    |  Email                         |
    |  Password                      |
    |                                |
    |        [ Đăng nhập ]           |
    |                                |
    | Quên mật khẩu                  |
    ----------------------------------

## Thành phần

Email input Password input Button đăng nhập

## Hành vi

Nếu sai: - hiển thị thông báo đỏ

Nếu đúng: - chuyển đến trang dashboard

------------------------------------------------------------------------

# 5. Trang Trang Chủ (Home)

URL

    /

## Bố cục

    Header

    Banner sự kiện nổi bật

    Danh sách sự kiện

    Footer

------------------------------------------------------------------------

## 5.1 Banner sự kiện

Slider hiển thị:

-   hình ảnh sự kiện
-   tiêu đề
-   nút xem chi tiết

------------------------------------------------------------------------

## 5.2 Danh sách sự kiện

Dạng grid card

    ---------------------------------
    | ảnh sự kiện                   |
    | tiêu đề                       |
    | thời gian                     |
    | địa điểm                      |
    | [Xem chi tiết]                |
    ---------------------------------

Hiển thị 3 hoặc 4 card mỗi hàng

------------------------------------------------------------------------

# 6. Trang Chi Tiết Sự Kiện

URL

    /event/{id}

## Layout

    Header

    Ảnh sự kiện lớn

    Thông tin sự kiện

    Nút đăng ký

    Danh sách media

    Footer

------------------------------------------------------------------------

## Thông tin sự kiện

Bao gồm

-   tiêu đề
-   mô tả
-   thời gian
-   địa điểm
-   số lượng tham gia

------------------------------------------------------------------------

## Button đăng ký

Trạng thái:

Nếu chưa đăng ký:

    [ Đăng ký tham gia ]

Nếu đã đăng ký

    [ Hủy đăng ký ]

------------------------------------------------------------------------

# 7. Trang Lịch Sử Tham Gia

URL

    /history

## Bố cục

Bảng dữ liệu

    ---------------------------------------------------
    Sự kiện | Ngày tham gia | Điểm | Trạng thái
    ---------------------------------------------------
    Seminar AI | 12/03 | 10 | Hoàn thành
    Workshop Web | 20/03 | 8 | Hoàn thành
    ---------------------------------------------------

------------------------------------------------------------------------

# 8. Trang Hồ Sơ Người Dùng

URL

    /profile

## Bố cục

    Avatar

    Tên
    Email
    Lớp

    [ Chỉnh sửa ]

------------------------------------------------------------------------

# 9. Trang Thông Báo

URL

    /notifications

## Danh sách thông báo

    --------------------------------------
    [Icon] Sự kiện AI đã thay đổi thời gian
    --------------------------------------
    [Icon] Bạn đã được cộng điểm
    --------------------------------------

------------------------------------------------------------------------

# 10. Trang Admin Dashboard

URL

    /admin

## Bố cục

    Sidebar | Dashboard content

## Các widget

-   Tổng số sự kiện
-   Tổng người tham gia
-   Sự kiện sắp diễn ra

------------------------------------------------------------------------

# 11. Trang Quản Lý Sự Kiện (Admin)

URL

    /admin/events

## Bảng sự kiện

    --------------------------------------------------
    Tên | Thời gian | Địa điểm | Người tham gia | Action
    --------------------------------------------------
    AI Seminar | 12/3 | Hall A | 120 | Edit Delete
    --------------------------------------------------

------------------------------------------------------------------------

# 12. Trang Tạo Sự Kiện

URL

    /admin/events/create

## Form

Tiêu đề

Mô tả

Thời gian

Địa điểm

Ảnh sự kiện

Số lượng tối đa

Button

    [ Tạo sự kiện ]

------------------------------------------------------------------------

# 13. Trang Media Library

URL

    /admin/media

Hiển thị dạng grid

    [img] [img] [img]
    [img] [img] [img]

Có nút

    Upload

------------------------------------------------------------------------

# 14. Trang Template Bài Đăng

URL

    /admin/templates

Hiển thị danh sách template

Admin có thể:

-   tạo template
-   chỉnh sửa
-   xóa

------------------------------------------------------------------------

# 15. Chatbot tư vấn

Nằm góc phải dưới.

Icon chat.

Click mở box

    -----------------------
    Chatbot
    -----------------------
    Xin chào
    Bạn cần hỏi gì?
    -----------------------

Các câu hỏi phổ biến:

-   sự kiện sắp diễn ra
-   cách đăng ký
-   địa điểm

------------------------------------------------------------------------

# 16. Responsive

Website phải hỗ trợ:

Desktop\
Tablet\
Mobile

Mobile:

Menu chuyển thành **hamburger menu**.

------------------------------------------------------------------------

# 17. Cấu trúc thư mục frontend đề xuất (Laravel)

    resources/views

    layouts/
        app.blade.php

    auth/
        login.blade.php

    events/
        index.blade.php
        show.blade.php

    profile/
        profile.blade.php

    admin/
        dashboard.blade.php
        events.blade.php
        create-event.blade.php
        media.blade.php
        templates.blade.php

------------------------------------------------------------------------

# 18. Tổng kết

Frontend của hệ thống gồm:

Trang người dùng: - Login - Trang chủ - Danh sách sự kiện - Chi tiết sự
kiện - Lịch sử tham gia - Hồ sơ - Thông báo

Trang quản trị: - Dashboard - Quản lý sự kiện - Media - Template - Thống
kê

Tài liệu này cung cấp **đủ chi tiết để lập trình viên có thể xây dựng
lại giao diện hoàn chỉnh của hệ thống quản lý sự kiện**.
