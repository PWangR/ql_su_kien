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

# 2. Cài đặt và thực nghiệm hệ thống

## 2.1 Quản trị viên / Ban tổ chức (Admin)

Quản trị viên là người quản lý toàn bộ hoạt động của hệ thống, bao gồm quản lý sự kiện, người dùng, điểm danh, bầu cử và các nội dung liên quan.

### 2.1.1 Quản lý người dùng
- Tạo, chỉnh sửa và xóa tài khoản người dùng.
- Phân quyền người dùng theo vai trò:
  - Quản trị viên
  - Sinh viên
  - Giảng viên
- Khóa hoặc mở khóa tài khoản khi cần thiết.

### 2.1.2 Quản lý sự kiện
- Tạo mới sự kiện.
- Chỉnh sửa thông tin sự kiện.
- Xóa sự kiện khi không còn sử dụng.
- Cập nhật các thông tin chi tiết:
  - Thời gian tổ chức
  - Địa điểm
  - Số lượng người tham gia
  - Hình ảnh minh họa
  - Nội dung chương trình.

### 2.1.3 Quản lý đăng ký tham gia
- Xem danh sách người đăng ký tham gia sự kiện.
- Quản lý danh sách người tham gia thực tế.
- Cho phép hoặc từ chối đăng ký khi cần thiết.

### 2.1.4 Quản lý điểm tham gia
- Thiết lập tiêu chí chấm điểm cho từng sự kiện.
- Tự động ghi nhận điểm cho người tham gia.
- Tra cứu lịch sử điểm của từng người dùng.

### 2.1.5 Quản lý thư viện đa phương tiện
- Upload hình ảnh, video hoặc tài liệu liên quan đến sự kiện.
- Quản lý và phân loại nội dung trong thư viện media.
- Sử dụng lại nội dung media cho các bài đăng sự kiện.

### 2.1.6 Quản lý template bài đăng
- Tạo template mẫu cho bài đăng sự kiện.
- Chỉnh sửa và tái sử dụng template.
- Áp dụng template khi tạo sự kiện mới.

### 2.1.7 Gửi thông báo hệ thống
- Gửi thông báo khi:
  - Có sự kiện mới
  - Có thay đổi về thời gian hoặc địa điểm
  - Có thông tin quan trọng liên quan đến sự kiện
- Hệ thống gửi thông báo đến người dùng theo thời gian thực.

### 2.1.8 Thống kê và báo cáo
- Thống kê số lượng người tham gia sự kiện.
- Thống kê điểm tham gia.
- Xuất báo cáo dữ liệu sự kiện dưới dạng file.

---

## 2.2 Quản lý điểm danh bằng QR Code

Hệ thống hỗ trợ điểm danh sinh viên bằng mã QR để đảm bảo nhanh chóng và chính xác.

### 2.2.1 Tạo mã QR điểm danh
- Khi tạo sự kiện, hệ thống có thể sinh ra nhiều mã QR phục vụ điểm danh.
- Admin có thể:
  - Quy định số lượng mã QR.
  - Thiết lập thời gian hiệu lực của mã QR.
  - Tải xuống hoặc hiển thị mã QR tại địa điểm sự kiện.

### 2.2.2 Quét mã QR điểm danh
- Sinh viên sử dụng camera điện thoại để quét mã QR.
- Sau khi quét thành công:
  - Hệ thống xác nhận điểm danh.
  - Ghi nhận thời gian điểm danh.
  - Cập nhật trạng thái tham gia sự kiện.

### 2.2.3 Quản lý dữ liệu điểm danh
- Admin có thể:
  - Xem danh sách người đã điểm danh.
  - Theo dõi số lượng điểm danh theo thời gian thực.
  - Xuất danh sách điểm danh khi cần thiết.

---

## 2.3 Quản lý bầu cử / bình chọn

Hệ thống cho phép tổ chức các cuộc bầu cử hoặc bình chọn liên quan đến sự kiện.

### 2.3.1 Tạo cuộc bầu cử
- Admin có thể tạo một cuộc bầu cử mới.
- Thiết lập các thông tin:
  - Tiêu đề cuộc bầu cử
  - Mô tả
  - Thời gian bắt đầu
  - Thời gian kết thúc.

### 2.3.2 Quản lý lựa chọn bầu cử
- Thêm các lựa chọn hoặc ứng viên.
- Chỉnh sửa hoặc xóa lựa chọn.
- Hiển thị danh sách lựa chọn cho người dùng.

### 2.3.3 Quản lý kết quả bầu cử
- Theo dõi số lượng phiếu bầu.
- Hiển thị kết quả theo thời gian thực.
- Xuất báo cáo kết quả bầu cử.

---

## 2.4 Người dùng (Sinh viên, Giảng viên)

Người dùng là những người tham gia các sự kiện được tổ chức trên hệ thống.

### 2.4.1 Quản lý tài khoản
- Đăng ký tài khoản mới.
- Đăng nhập hệ thống.
- Cập nhật thông tin cá nhân.

### 2.4.2 Xem và tìm kiếm sự kiện
- Xem danh sách các sự kiện đang diễn ra.
- Tìm kiếm sự kiện theo:
  - Tên sự kiện
  - Thời gian
  - Địa điểm
  - Chủ đề.

### 2.4.3 Đăng ký tham gia sự kiện
- Đăng ký tham gia sự kiện.
- Hủy đăng ký khi không thể tham gia.

### 2.4.4 Nhận thông báo
- Nhận thông báo về:
  - Sự kiện mới
  - Thay đổi lịch trình
  - Các thông báo quan trọng.

### 2.4.5 Theo dõi lịch sử tham gia
- Xem các sự kiện đã tham gia.
- Xem điểm tích lũy từ các sự kiện.

### 2.4.6 Tùy chỉnh giao diện
- Thay đổi giao diện hiển thị theo sở thích cá nhân.

### 2.4.7 Điểm danh bằng QR
- Quét mã QR tại sự kiện để xác nhận tham gia.
- Xem trạng thái điểm danh sau khi quét.

### 2.4.8 Tham gia bầu cử
- Xem các cuộc bầu cử đang diễn ra.
- Xem danh sách ứng viên hoặc lựa chọn.
- Thực hiện bỏ phiếu trực tuyến.
- Mỗi người dùng chỉ được bỏ phiếu một lần cho mỗi cuộc bầu cử.

---

## 2.5 Chatbot tư vấn sự kiện

Chatbot hỗ trợ người dùng trong việc tìm kiếm và tra cứu thông tin sự kiện.

### 2.5.1 Trả lời câu hỏi tự động
Chatbot có thể trả lời các câu hỏi thường gặp như:

- Thời gian tổ chức sự kiện
- Địa điểm tổ chức
- Nội dung chương trình
- Cách thức đăng ký tham gia

### 2.5.2 Hướng dẫn sử dụng hệ thống
Chatbot có thể hỗ trợ:

- Hướng dẫn cách đăng ký sự kiện
- Hướng dẫn cách quét QR để điểm danh
- Hướng dẫn cách tham gia bầu cử

### 2.5.3 Tra cứu thông tin nhanh
Người dùng có thể hỏi chatbot để:
- Kiểm tra sự kiện đang diễn ra
- Kiểm tra lịch sự kiện
- Kiểm tra trạng thái đăng ký.

------------------------------------------------------------------------

# IV. Tài liệu tham khảo chính

-   Nguyễn Hải Triều -- Bài giảng *Phát triển phần mềm mã nguồn mở*, Đại
    học Nha Trang
-   Hà Thị Thanh Ngà -- Bài giảng *Phân tích và thiết kế hệ thống thông
    tin*, Đại học Nha Trang
-   Laravel Framework Documentation
-   ReactJS Documentation
-   Socket.io Documentation
