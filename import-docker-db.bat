@echo off
setlocal

where docker >nul 2>nul
if errorlevel 1 (
    echo Docker chua duoc cai dat hoac chua co trong PATH.
    pause
    exit /b 1
)

if not exist ql_su_kien.sql (
    echo Khong tim thay file ql_su_kien.sql trong thu muc du an.
    pause
    exit /b 1
)

echo CANH BAO: Lenh nay se xoa va tao lai database Docker: ql_su_kien
echo No chi anh huong database trong Docker, khong xoa database Laragon.
set /p CONFIRM=Nhap YES de tiep tuc: 
if /I not "%CONFIRM%"=="YES" (
    echo Da huy import.
    pause
    exit /b 0
)

echo Dam bao container dang chay...
docker compose up -d db app
if errorlevel 1 (
    echo Khong khoi dong duoc container db/app.
    pause
    exit /b 1
)

echo Tao lai database Docker...
docker compose exec db mysql -uroot -proot -e "DROP DATABASE IF EXISTS ql_su_kien; CREATE DATABASE ql_su_kien CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if errorlevel 1 (
    echo Tao lai database that bai.
    pause
    exit /b 1
)

echo Dang import ql_su_kien.sql...
docker compose exec -T db mysql -uroot -proot ql_su_kien < ql_su_kien.sql
if errorlevel 1 (
    echo Import SQL that bai.
    pause
    exit /b 1
)

echo Chay migration moi bo sung sau file SQL...
docker compose exec app php artisan config:clear
docker compose exec app php artisan migrate --force
if errorlevel 1 (
    echo Migration sau import that bai.
    pause
    exit /b 1
)

echo.
echo Import database Docker thanh cong.
echo Web: http://localhost:8080
pause
