@echo off
setlocal
set LOG_FILE=docker-start.log

where docker >nul 2>nul
if errorlevel 1 (
    echo Docker chua duoc cai dat hoac chua co trong PATH.
    pause
    exit /b 1
)

if not exist .env (
    if exist .env.docker.example (
        copy .env.docker.example .env >nul
        echo Da tao file .env tu .env.docker.example.
    )
)

echo Ghi log vao %LOG_FILE%
echo ==== Docker start log ==== > %LOG_FILE%
docker --version >> %LOG_FILE% 2>&1
docker compose version >> %LOG_FILE% 2>&1

echo Dang kiem tra cau hinh Docker Compose...
docker compose config >> %LOG_FILE% 2>&1
if errorlevel 1 (
    echo Cau hinh Docker Compose bi loi. Noi dung log:
    type %LOG_FILE%
    pause
    exit /b 1
)

echo Dang build va khoi dong cac container...
docker compose up -d --build >> %LOG_FILE% 2>&1
if errorlevel 1 (
    echo Khoi dong Docker that bai.
    echo Noi dung log:
    type %LOG_FILE%
    pause
    exit /b 1
)

echo Dang cai/cap nhat PHP dependencies...
docker compose exec app composer install --no-interaction --prefer-dist >> %LOG_FILE% 2>&1
if errorlevel 1 (
    echo Composer install that bai.
    echo Noi dung log:
    type %LOG_FILE%
    pause
    exit /b 1
)

echo Dang chuan bi Laravel...
docker compose exec app php artisan config:clear >> %LOG_FILE% 2>&1
docker compose exec app php artisan migrate --force >> %LOG_FILE% 2>&1
if errorlevel 1 (
    echo Migration that bai.
    echo Noi dung log:
    type %LOG_FILE%
    pause
    exit /b 1
)

docker compose exec app php artisan storage:link --force >> %LOG_FILE% 2>&1

echo.
echo Da khoi dong xong.
echo Web:  http://localhost:8080
echo Vite: http://localhost:5173
echo MySQL host port: 3307
echo.
pause
