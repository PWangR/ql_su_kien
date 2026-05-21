@echo off
setlocal

where docker >nul 2>nul
if errorlevel 1 (
    echo Docker chua duoc cai dat hoac chua co trong PATH.
    pause
    exit /b 1
)

docker compose down
pause
