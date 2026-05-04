# Ke hoach test tren dien thoai that

## 1. Chuan bi moi truong

1. Dam bao dien thoai va may chay Laravel dung chung Wi-Fi.
2. Lay IP LAN cua may tinh:
   - Windows: `ipconfig`
   - Dung IPv4 cua card Wi-Fi/LAN, vi du `192.168.1.211`.
3. Chay backend bang IP LAN:
   ```bash
   D:\laragon\bin\php\php-8.4.13-nts-Win32-vs17-x64\php.exe artisan serve --host=0.0.0.0 --port=8000
   ```
4. Cho phep firewall truy cap port `8000`.
5. Chay mobile app voi API URL dung IP LAN:
   ```bash
   cd mobile_app
   set EXPO_PUBLIC_API_URL=http://192.168.1.211:8000
   D:\laragon\bin\nodejs\node-v24.11.1-win-x64\npm.cmd start
   ```
6. Mo app bang Expo Go tren dien thoai va quet QR cua Expo.

## 2. Tai khoan va phien dang nhap

- Dang ky tai khoan sinh vien moi.
- Kiem tra email xac thuc va dang nhap sau khi xac thuc.
- Dang nhap sai mat khau phai hien loi.
- Tat/mo lai app, token phai duoc khoi phuc.
- Dang xuat phai quay ve man hinh dang nhap.
- Quen mat khau va gui lai email xac thuc phai goi API thanh cong.

## 3. Su kien

- Trang chu tai duoc banner, danh muc, su kien moi.
- Danh sach su kien tai duoc du lieu, pull-to-refresh hoat dong.
- Tim kiem theo ten/dia diem.
- Loc theo trang thai va loai su kien.
- Scroll toi cuoi danh sach phai tai them trang, khong ghi de du lieu cu.
- Mo chi tiet su kien, xem anh, thoi gian, dia diem, noi dung, tai lieu.
- Dang ky/huy dang ky, quay lai mo lai chi tiet phai dung trang thai.

## 4. QR diem danh

- Lan dau vao scanner phai hoi quyen camera.
- QR dung dinh dang va con han phai duoc dua vao hang cho.
- Bat che do may bay, scan QR, hang cho phai con.
- Tat che do may bay, nhan dong bo, backend cap nhat diem danh.
- QR het han/sai dinh dang phai bao loi va cho quet lai.

## 5. Ho so, diem, thong bao

- Ho so hien MSSV, lop, so dien thoai, QR ca nhan.
- Chinh sua ho so va quay lai profile thay du lieu moi.
- Doi mat khau: sai mat khau cu phai bao loi, dung phai thanh cong.
- Man hinh diem hien tong diem, lich su diem, bang xep hang.
- Man hinh thong bao hien danh sach, danh dau da doc, danh dau tat ca, xoa thong bao.

## 6. Bau cu va chatbot

- Danh sach bau cu tai duoc cac cuoc dang hien thi.
- Chi tiet bau cu hien ung cu vien, trang thai, gioi han so luong chon.
- Cu tri hop le bo phieu thanh cong, khong duoc bo phieu lan hai.
- Neu hien thi ket qua, bieu do/ty le phai cap nhat.
- Chatbot gui cau hoi va nhan cau tra loi; mat mang phai hien loi de hieu.

## 7. Thiet bi toi thieu can test

- 1 Android man hinh 6.x inch.
- 1 Android man hinh nho hoac cau hinh thap.
- Neu co: 1 iPhone dung Expo Go.

Ghi lai moi loi voi: thiet bi, OS, tai khoan, buoc tai hien, anh man hinh, log API/mobile.
