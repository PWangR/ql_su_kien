@echo off
setlocal

cd /d "%~dp0mobile_app"
call build-android-apk.bat
