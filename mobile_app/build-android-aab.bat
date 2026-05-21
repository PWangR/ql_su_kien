@echo off
setlocal EnableExtensions

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

set /p API_URL=Nhap API URL production HTTPS, vi du https://events.example.com: 
if "%API_URL%"=="" (
    echo API URL khong duoc de trong.
    pause
    exit /b 1
)

echo Dang cap nhat eas.json voi API URL production: %API_URL%
powershell -NoProfile -ExecutionPolicy Bypass -Command "$path='eas.json'; $json=Get-Content $path -Raw | ConvertFrom-Json; $json.build.production.env.EXPO_PUBLIC_API_URL='%API_URL%'; $json | ConvertTo-Json -Depth 10 | Set-Content $path -Encoding UTF8"
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
echo Lenh nay tao file AAB de dua len Google Play Console.
echo.
npx eas-cli build --platform android --profile production
pause
