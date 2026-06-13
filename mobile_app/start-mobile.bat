@echo off
setlocal EnableExtensions EnableDelayedExpansion

set NODE_DIR=
if exist "D:\laragon\bin\nodejs\node-v24.11.1-win-x64\node.exe" set NODE_DIR=D:\laragon\bin\nodejs\node-v24.11.1-win-x64
if not "%NODE_DIR%"=="" set "PATH=%NODE_DIR%;%PATH%"

where node >nul 2>nul
if errorlevel 1 (
    if exist "D:\laragon\bin\nodejs\node-v18\node.exe" set NODE_DIR=D:\laragon\bin\nodejs\node-v18
    if "!NODE_DIR!"=="" if exist "D:\laragon\bin\nodejs\node-v24.11.1-win-x64\node.exe" set NODE_DIR=D:\laragon\bin\nodejs\node-v24.11.1-win-x64
    if "!NODE_DIR!"=="" (
        echo Node.js chua duoc cai dat hoac chua co trong PATH.
        pause
        exit /b 1
    )
    set "PATH=!NODE_DIR!;%PATH%"
)

where npm >nul 2>nul
if errorlevel 1 (
    if "!NODE_DIR!"=="" if exist "D:\laragon\bin\nodejs\node-v18\npm.cmd" set NODE_DIR=D:\laragon\bin\nodejs\node-v18
    if "!NODE_DIR!"=="" if exist "D:\laragon\bin\nodejs\node-v24.11.1-win-x64\npm.cmd" set NODE_DIR=D:\laragon\bin\nodejs\node-v24.11.1-win-x64
    if not "!NODE_DIR!"=="" set "PATH=!NODE_DIR!;%PATH%"
)

where npm >nul 2>nul
if errorlevel 1 (
    echo npm chua duoc cai dat hoac chua co trong PATH.
    pause
    exit /b 1
)

where npx >nul 2>nul
if errorlevel 1 (
    if "!NODE_DIR!"=="" if exist "D:\laragon\bin\nodejs\node-v18\npx.cmd" set NODE_DIR=D:\laragon\bin\nodejs\node-v18
    if "!NODE_DIR!"=="" if exist "D:\laragon\bin\nodejs\node-v24.11.1-win-x64\npx.cmd" set NODE_DIR=D:\laragon\bin\nodejs\node-v24.11.1-win-x64
    if not "!NODE_DIR!"=="" set "PATH=!NODE_DIR!;%PATH%"
)

where npx >nul 2>nul
if errorlevel 1 (
    echo npx chua duoc cai dat hoac chua co trong PATH.
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
    echo 1. Dien thoai that qua Expo Go + backend Docker ^(port 8080^)
    echo 2. Android Emulator + backend Docker ^(port 8080^)
    echo 3. Web browser + backend Docker ^(port 8080^)
    echo 4. Expo DevTools mac dinh + backend Docker ^(port 8080^)
    echo 5. Dien thoai that + Laravel artisan serve ^(port 8000^)
    echo 6. Nhap API URL thu cong
    set /p MODE=Nhap 1, 2, 3, 4, 5 hoac 6: 
)

if "%MODE%"=="phone" set MODE=1
if "%MODE%"=="emulator" set MODE=2
if "%MODE%"=="android" set MODE=2
if "%MODE%"=="web" set MODE=3
if "%MODE%"=="default" set MODE=4
if "%MODE%"=="laravel" set MODE=5
if "%MODE%"=="artisan" set MODE=5
if "%MODE%"=="manual" set MODE=6

if "%MODE%"=="1" goto phone
if "%MODE%"=="2" goto emulator
if "%MODE%"=="3" goto web
if "%MODE%"=="4" goto default
if "%MODE%"=="5" goto laravel_phone
if "%MODE%"=="6" goto manual_url

echo Lua chon khong hop le.
pause
exit /b 1

:phone
call :detect_lan_ip
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

:laravel_phone
set APP_PORT=8000
call :detect_lan_ip
set EXPO_PUBLIC_API_URL=http://%LAN_IP%:%APP_PORT%
echo API URL: %EXPO_PUBLIC_API_URL%
echo.
echo Luu y: truoc khi chay mode nay, Laravel phai duoc mo bang:
echo php artisan serve --host=0.0.0.0 --port=8000
echo.
echo Neu dung Laragon domain ql_su_kien.test, dien thoai thuong khong resolve duoc domain do.
echo Hay dung IP may tinh nhu URL o tren.
npx expo start --lan --clear
goto end

:manual_url
echo Vi du:
echo - Docker tren may tinh: http://localhost:8080
echo - Dien thoai that + Docker: http://192.168.1.10:8080
echo - Dien thoai that + artisan serve: http://192.168.1.10:8000
set /p EXPO_PUBLIC_API_URL=Nhap API URL backend Laravel ^(khong them /api^): 
if "%EXPO_PUBLIC_API_URL%"=="" (
    echo API URL khong duoc de trong.
    pause
    exit /b 1
)
echo API URL: %EXPO_PUBLIC_API_URL%
npx expo start --lan --clear
goto end

:detect_lan_ip
set LAN_IP=
for /f "usebackq tokens=*" %%i in (`powershell -NoProfile -Command "$ip=(Get-NetIPAddress -AddressFamily IPv4 -ErrorAction SilentlyContinue | Where-Object { $_.IPAddress -notlike '127.*' -and $_.IPAddress -notlike '169.254.*' -and $_.InterfaceAlias -notmatch 'vEthernet|Docker|WSL|Loopback|VirtualBox|VMware|Bluetooth' -and $_.PrefixOrigin -ne 'WellKnown' } | Sort-Object InterfaceMetric | Select-Object -First 1 -ExpandProperty IPAddress); if ($ip) { $ip }"`) do set LAN_IP=%%i
if "%LAN_IP%"=="" (
    for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr /R /C:"IPv4.*192\\.168\\." /C:"IPv4.*10\\." /C:"IPv4.*172\\."') do (
        if "!LAN_IP!"=="" set LAN_IP=%%i
    )
    set LAN_IP=%LAN_IP: =%
)
if "%LAN_IP%"=="" (
    echo Khong tu tim duoc IP LAN.
    set /p LAN_IP=Nhap IP may tinh cua ban ^(vi du 192.168.1.10^): 
)
echo IP LAN dang dung: %LAN_IP%
exit /b 0

:end
pause
