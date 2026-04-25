@echo off
setlocal

if /i not "%~1"=="run-minimized" (
    start "" /min cmd /c ""%~f0" run-minimized"
    exit /b
)

cd /d "%~dp0"

set "PHP_EXE=C:\php-8.3.14-nts-Win32-vs16-x64\php.exe"

if not exist "%PHP_EXE%" (
    echo PHP executable not found:
    echo %PHP_EXE%
    pause
    exit /b 1
)

echo Starting Municipal Building Permit Repository System...
echo.
echo URL: http://127.0.0.1:8000
echo Login: admin / password123
echo.

start "" /min cmd /c ""%PHP_EXE%" artisan serve --host=127.0.0.1 --port=8000"
timeout /t 2 /nobreak >nul
start "" "http://127.0.0.1:8000/"

endlocal
