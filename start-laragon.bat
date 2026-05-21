@echo off
setlocal

set PHP_EXE=D:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe
set NODE_EXE=D:\laragon\bin\nodejs\node-v18\node.exe

if exist public\hot (
    del public\hot
    echo Da xoa public\hot de Laravel dung public\build thay vi Vite dev server.
)

if not exist "%PHP_EXE%" (
    set PHP_EXE=php
)

echo Dang clear cache Laravel...
"%PHP_EXE%" artisan optimize:clear
if errorlevel 1 (
    echo Khong chay duoc artisan. Hay kiem tra PHP/Laragon.
    pause
    exit /b 1
)

if exist "%NODE_EXE%" (
    echo Dang build asset cho Laragon...
    "%NODE_EXE%" node_modules\vite\bin\vite.js build
) else (
    echo Bo qua build asset vi khong tim thay Node Laragon.
)

echo.
echo Da chuan bi xong moi truong Laragon.
echo Hay dam bao Laragon dang Start All, MySQL dang chay, sau do mo:
echo http://ql_su_kien.test
echo.
pause
