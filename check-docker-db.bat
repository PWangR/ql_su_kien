@echo off
setlocal

where docker >nul 2>nul
if errorlevel 1 (
    echo Docker chua duoc cai dat hoac chua co trong PATH.
    pause
    exit /b 1
)

echo Trang thai container:
docker compose ps

echo.
echo Kiem tra MySQL container:
docker compose exec db mysql -uroot -proot -e "SELECT VERSION() AS mysql_version; SHOW DATABASES;"
if errorlevel 1 (
    echo Khong ket noi duoc MySQL container voi user root/root.
    pause
    exit /b 1
)

echo.
echo Danh sach bang trong database ql_su_kien:
docker compose exec db mysql -uroot -proot ql_su_kien -e "SHOW TABLES;"

echo.
echo Kiem tra Laravel ket noi database:
docker compose exec app php artisan migrate:status

pause
