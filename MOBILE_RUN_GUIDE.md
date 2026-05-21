# Chay mobile_app don gian

Ung dung mobile dung Expo. De tranh phai nho nhieu lenh, du an co san script:

```bat
start-mobile.bat
```

Script nay tu dong:

- vao thu muc `mobile_app`
- cai `npm install` neu chua co `node_modules`
- set `EXPO_PUBLIC_API_URL` theo cach chay
- mo Expo voi dung che do

## Cach chay nhanh

1. Chay backend truoc:

```bat
start-docker.bat
```

2. Chay mobile:

```bat
start-mobile.bat
```

3. Chon mot che do:

- `1`: dien thoai that qua Expo Go, may tinh va dien thoai phai cung Wi-Fi
- `2`: Android Emulator
- `3`: Web browser
- `4`: Expo mac dinh

## Chay truc tiep theo che do

Dien thoai that:

```bat
start-mobile.bat phone
```

Android Emulator:

```bat
start-mobile.bat emulator
```

Web:

```bat
start-mobile.bat web
```

## API URL mac dinh

Khi chay bang Docker, backend web nam o:

```text
http://localhost:8080
```

Voi dien thoai that, `localhost` la chinh dien thoai nen khong dung duoc. Script se tu tim IP LAN cua may tinh, vi du:

```text
http://192.168.1.10:8080
```

Voi Android Emulator, script dung:

```text
http://10.0.2.2:8080
```

Neu dien thoai khong ket noi duoc API, kiem tra:

- may tinh va dien thoai cung Wi-Fi
- Windows Firewall cho phep ket noi vao port `8080`
- backend Docker dang chay
- mo thu tren dien thoai: `http://IP_MAY_TINH:8080`

## Neu Expo Go bao loi 401 Unauthenticated

Loi `401` nghia la dien thoai da ket noi duoc backend, nhung token dang nhap trong Expo Go khong con hop le. Truong hop nay hay xay ra sau khi doi sang database Docker hoac import lai database.

Cach xu ly:

1. Dung Expo dang chay.
2. Chay lai:

```bat
start-mobile.bat phone
```

3. Dang nhap lai tai khoan.

Neu van bi 401, vao cai dat ung dung tren dien thoai va xoa du lieu app Expo Go, hoac go cai dat Expo Go roi cai lai. Sau do quet QR lai.

## Neu chay web khong co giao dien/CSS

Neu ban muon xem mobile_app tren trinh duyet, hay chay:

```bat
start-mobile.bat web
```

Sau do mo dung URL ma Expo in ra trong terminal. Khong mo `http://localhost:8080` vi day la backend Laravel, khong phai mobile_app web.
