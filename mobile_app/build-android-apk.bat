@echo off
setlocal EnableExtensions EnableDelayedExpansion

where node >nul 2>nul
if errorlevel 1 (
    echo Node.js chua duoc cai dat hoac chua co trong PATH.
    pause
    exit /b 1
)

where npm >nul 2>nul
if errorlevel 1 (
    echo npm chua duoc cai dat hoac chua co trong PATH.
    pause
    exit /b 1
)

if not exist package.json (
    echo Hay chay file nay trong thu muc mobile_app.
    pause
    exit /b 1
)

set DEFAULT_API_URL=
for /f "usebackq tokens=*" %%i in (`powershell -NoProfile -Command "$ip=(Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.IPAddress -notlike '127.*' -and $_.IPAddress -notlike '169.254.*' -and $_.PrefixOrigin -ne 'WellKnown' } | Sort-Object InterfaceMetric | Select-Object -First 1 -ExpandProperty IPAddress); if ($ip) { 'http://' + $ip + ':8080' }"`) do set DEFAULT_API_URL=%%i

if "%DEFAULT_API_URL%"=="" set DEFAULT_API_URL=http://192.168.1.10:8080

echo API URL se duoc dong goi vao APK.
echo Neu backend chay bang Docker trong mang noi bo, dung dang: http://IP_MAY_TINH:8080
echo Neu la app phat hanh that, nen dung domain HTTPS cong khai.
set /p API_URL=Nhap API URL [%DEFAULT_API_URL%]: 
if "%API_URL%"=="" set API_URL=%DEFAULT_API_URL%

echo Dang cap nhat eas.json voi API URL: %API_URL%
powershell -NoProfile -ExecutionPolicy Bypass -Command "$path='eas.json'; $json=Get-Content $path -Raw | ConvertFrom-Json; $json.build.preview.env.EXPO_PUBLIC_API_URL='%API_URL%'; $json.build.development.env.EXPO_PUBLIC_API_URL='%API_URL%'; $json | ConvertTo-Json -Depth 10 | Set-Content $path -Encoding UTF8"
if errorlevel 1 (
    echo Khong cap nhat duoc eas.json.
    pause
    exit /b 1
)

if not exist node_modules (
    echo Dang cai dependencies...
    npm install
    if errorlevel 1 (
        echo npm install that bai.
        pause
        exit /b 1
    )
)

echo.
echo Neu day la lan dau, EAS co the yeu cau dang nhap Expo va tao project.
echo Sau khi build xong, terminal se hien link tai file APK.
echo.
npx eas-cli build --platform android --profile preview
pause
