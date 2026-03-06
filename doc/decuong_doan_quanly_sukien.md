# TRƯỜNG ĐẠI HỌC NHA TRANG

## KHOA CÔNG NGHỆ THÔNG TIN

# ĐỀ TÀI ĐỒ ÁN TỐT NGHIỆP

## XÂY DỰNG WEBSITE QUẢN LÝ SỰ KIỆN KHOA CÔNG NGHỆ THÔNG TIN TRƯỜNG ĐẠI HỌC NHA TRANG

**Sinh viên đề xuất:** Dương Phú Quảng -- 64131942\
**Lớp:** 64.CNTT-1\
**Email:** quang.dp.64cntt@ntu.edu.vn

------------------------------------------------------------------------

# I. Đặt vấn đề

Trong môi trường đại học, các sự kiện như hội thảo, seminar, sinh hoạt
câu lạc bộ và hoạt động ngoại khóa được tổ chức thường xuyên nhằm phục
vụ học tập, nghiên cứu và phong trào sinh viên. Tuy nhiên, công tác quản
lý các sự kiện hiện nay chủ yếu thông qua các kênh rời rạc như mạng xã
hội, email hoặc biểu mẫu thủ công, dẫn đến tình trạng dữ liệu phân tán,
khó theo dõi số lượng người tham gia, việc tổng hợp và thống kê báo cáo
kém hiệu quả, đồng thời người tham gia khó cập nhật kịp thời các thay
đổi của sự kiện.

Trước thực trạng đó, việc xây dựng một website quản lý sự kiện tập trung
dành cho sinh viên và giảng viên là cần thiết. Hệ thống không chỉ hỗ trợ
quản lý thông tin sự kiện, đăng ký và thông báo, mà còn hướng tới mở
rộng các chức năng như ghi nhận lịch sử tham gia kèm theo điểm đánh giá,
quản lý nội dung đa phương tiện, tùy chỉnh giao diện người dùng và tích
hợp chatbot tư vấn, góp phần nâng cao hiệu quả tổ chức và trải nghiệm
tham gia sự kiện trong nhà trường.

------------------------------------------------------------------------

# II. Mục đích

Mục đích của đề tài là xây dựng một ứng dụng web quản lý sự kiện cho
sinh viên và giảng viên, cho phép người dùng dễ dàng tiếp cận thông tin,
đăng ký và theo dõi quá trình tham gia các sự kiện; đồng thời hỗ trợ ban
tổ chức trong việc tạo lập, quản lý và giám sát sự kiện một cách tập
trung, khoa học và hiệu quả.

Thông qua đề tài, sinh viên vận dụng các kiến thức đã học về phân tích
yêu cầu, thiết kế hệ thống, xây dựng cơ sở dữ liệu và phát triển ứng
dụng web để triển khai một hệ thống có tính ứng dụng thực tế. Bên cạnh
các chức năng quản lý cơ bản, hệ thống hướng tới mở rộng các tính năng
như ghi nhận và lưu trữ lịch sử tham gia kèm theo điểm đánh giá, quản lý
nội dung đa phương tiện, tùy chỉnh giao diện người dùng, chuẩn hóa nội
dung đăng tải và tích hợp chatbot tư vấn nhằm nâng cao trải nghiệm và
hiệu quả sử dụng.

## Công nghệ dự kiến sử dụng

**Frontend** - ReactJS để xây dựng giao diện hiện đại, linh hoạt và dễ
mở rộng.

**Backend** - Laravel (PHP) để xử lý nghiệp vụ, phân quyền và quản lý dữ
liệu.

**Cơ sở dữ liệu** - MySQL để lưu trữ dữ liệu sự kiện, người dùng và lịch
sử tham gia.

**Cơ chế thông báo** - Socket.io để gửi thông báo thời gian thực khi có
cập nhật liên quan đến sự kiện.

------------------------------------------------------------------------

# III. Yêu cầu

## 1. Về lý thuyết

-   Khảo sát và phân tích nhu cầu của các nhóm người dùng trong hệ thống
    gồm sinh viên, giảng viên và ban tổ chức sự kiện.
-   Nghiên cứu các hệ thống quản lý sự kiện hiện có để tham khảo quy
    trình tổ chức và mô hình chức năng.
-   Nghiên cứu kiến thức nền tảng về phân tích và thiết kế hệ thống
    thông tin.
-   Thiết kế cơ sở dữ liệu cho bài toán quản lý sự kiện.

### Công nghệ nghiên cứu

-   HTML, CSS, JavaScript
-   ReactJS
-   Laravel (PHP)
-   MySQL

------------------------------------------------------------------------

## 2. Về cài đặt và thực nghiệm

### a. Quản trị viên / Ban tổ chức (Admin)

-   Quản lý tài khoản người dùng và phân quyền theo vai trò.
-   Tạo, chỉnh sửa, cập nhật và xóa sự kiện.
-   Quản lý thông tin chi tiết: thời gian, địa điểm, số lượng tham gia,
    hình ảnh.
-   Quản lý danh sách đăng ký và danh sách tham gia thực tế.
-   Thiết lập tiêu chí chấm điểm và ghi nhận điểm tham gia.
-   Quản lý và tra cứu lịch sử điểm.
-   Quản lý thư viện nội dung đa phương tiện (hình ảnh, video, tài
    liệu).
-   Tạo và quản lý template bài đăng sự kiện.
-   Gửi thông báo khi có thay đổi sự kiện.
-   Thống kê và xuất báo cáo hoạt động sự kiện.

### b. Người dùng (Sinh viên, Giảng viên)

-   Đăng ký và đăng nhập hệ thống.
-   Xem danh sách và tìm kiếm sự kiện.
-   Đăng ký và hủy đăng ký tham gia.
-   Nhận thông báo thời gian thực.
-   Theo dõi lịch sử tham gia và điểm đánh giá.
-   Quản lý thông tin cá nhân.
-   Tùy chỉnh giao diện hiển thị.

### c. Chatbot tư vấn sự kiện

-   Trả lời tự động các câu hỏi thường gặp:
    -   Thời gian sự kiện
    -   Địa điểm
    -   Nội dung
    -   Cách thức đăng ký

------------------------------------------------------------------------

# IV. Tài liệu tham khảo chính

-   Nguyễn Hải Triều -- Bài giảng *Phát triển phần mềm mã nguồn mở*, Đại
    học Nha Trang
-   Hà Thị Thanh Ngà -- Bài giảng *Phân tích và thiết kế hệ thống thông
    tin*, Đại học Nha Trang
-   Laravel Framework Documentation
-   ReactJS Documentation
-   Socket.io Documentation
