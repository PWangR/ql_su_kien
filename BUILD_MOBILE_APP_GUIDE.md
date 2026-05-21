# Tao app mobile that cho Android

Du an mobile hien dung Expo managed workflow. Khong can Expo Go khi phat hanh app: ta build ra file APK/AAB bang EAS.

- APK: cai truc tiep len dien thoai de demo/test noi bo.
- AAB: dua len Google Play Console.

## Dieu kien can co

- Node.js va npm.
- Tai khoan Expo mien phi.
- Backend Laravel dang truy cap duoc tu dien thoai.

Neu backend chay Docker tren may tinh cua ban, dien thoai phai cung Wi-Fi va API URL se co dang:

```text
http://IP_MAY_TINH:8080
```

Neu build app de dung ben ngoai mang noi bo, can dua backend len server/domain that, nen dung HTTPS:

```text
https://your-domain.com
```

## Build APK de cai truc tiep

Tai thu muc goc du an:

```bat
build-mobile-apk.bat
```

Script se hoi API URL. Neu dang demo cung Wi-Fi, co the nhan Enter de dung IP LAN duoc de xuat.

Lan dau EAS co the yeu cau:

```bash
Log in to EAS
Create new project
Generate Android keystore
```

Chon dong y theo huong dan trong terminal. Khi build xong, EAS se hien link tai file APK.

## Build AAB de dua len Google Play

```bat
build-mobile-aab.bat
```

Nhap API URL production HTTPS. Sau khi build xong, tai file `.aab` va upload len Google Play Console.

## Files da cau hinh

- `mobile_app/app.json`: ten app, package Android, permission camera/internet.
- `mobile_app/eas.json`: profile build `preview` APK va `production` AAB.
- `mobile_app/build-android-apk.bat`: build APK demo noi bo.
- `mobile_app/build-android-aab.bat`: build AAB production.

## Luu y quan trong ve API URL

App that khong giong Expo Go dev server. API URL duoc dong goi vao app luc build.

Neu build APK voi:

```text
http://192.168.1.10:8080
```

thi dien thoai chi goi duoc API khi:

- backend dang chay
- dien thoai cung mang voi may tinh
- Windows Firewall cho phep port `8080`
- IP may tinh khong thay doi

Neu IP thay doi, can build APK lai hoac dung domain co dinh.
