@echo off
setlocal

cd /d "%~dp0"

echo Starting Municipal Building Permit Repository System...
echo.

echo Starting Laravel server...
start cmd /k "php artisan serve --host=127.0.0.1 --port=8000"

echo Starting Vite (npm run dev)...
start cmd /k "npm run dev"

echo.
echo URL: http://127.0.0.1:8000
echo Login: admin / password123
echo.

endlocal