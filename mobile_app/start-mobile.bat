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

if not exist node_modules (
    echo Dang cai dependencies mobile_app...
    npm install
    if errorlevel 1 (
        echo npm install that bai.
        pause
        exit /b 1
    )
)

set APP_PORT=8080
if not "%1"=="" set MODE=%1

if "%MODE%"=="" (
    echo Chon cach chay mobile_app:
    echo 1. Dien thoai that qua Expo Go ^(cung Wi-Fi^)
    echo 2. Android Emulator
    echo 3. Web browser
    echo 4. Expo DevTools mac dinh
    set /p MODE=Nhap 1, 2, 3 hoac 4: 
)

if "%MODE%"=="phone" set MODE=1
if "%MODE%"=="emulator" set MODE=2
if "%MODE%"=="android" set MODE=2
if "%MODE%"=="web" set MODE=3
if "%MODE%"=="default" set MODE=4

if "%MODE%"=="1" goto phone
if "%MODE%"=="2" goto emulator
if "%MODE%"=="3" goto web
if "%MODE%"=="4" goto default

echo Lua chon khong hop le.
pause
exit /b 1

:phone
for /f "usebackq tokens=*" %%i in (`powershell -NoProfile -Command "$ip=(Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.IPAddress -notlike '127.*' -and $_.IPAddress -notlike '169.254.*' -and $_.PrefixOrigin -ne 'WellKnown' } | Sort-Object InterfaceMetric | Select-Object -First 1 -ExpandProperty IPAddress); if ($ip) { $ip }"`) do set LAN_IP=%%i
if "%LAN_IP%"=="" (
    echo Khong tu tim duoc IP LAN.
    set /p LAN_IP=Nhap IP may tinh cua ban ^(vi du 192.168.1.10^): 
)
set EXPO_PUBLIC_API_URL=http://%LAN_IP%:%APP_PORT%
echo API URL: %EXPO_PUBLIC_API_URL%
echo Mo Expo Go tren dien thoai va quet QR.
npx expo start --lan --clear
goto end

:emulator
set EXPO_PUBLIC_API_URL=http://10.0.2.2:%APP_PORT%
echo API URL: %EXPO_PUBLIC_API_URL%
npx expo start --android --clear
goto end

:web
set EXPO_PUBLIC_API_URL=http://localhost:%APP_PORT%
echo API URL: %EXPO_PUBLIC_API_URL%
npx expo start --web --clear --port 8082
goto end

:default
set EXPO_PUBLIC_API_URL=http://localhost:%APP_PORT%
echo API URL: %EXPO_PUBLIC_API_URL%
npx expo start --clear
goto end

:end
pause
