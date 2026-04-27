@echo off
setlocal

if /i not "%~1"=="run-minimized" (
    start "" /min cmd /c ""%~f0" run-minimized"
    exit /b
)

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

<<<<<<< HEAD
endlocal
=======
start "" /min cmd /c ""%PHP_EXE%" artisan serve --host=127.0.0.1 --port=8000"
timeout /t 2 /nobreak >nul
start "" "http://127.0.0.1:8000/"

endlocal
>>>>>>> 5a04e4c658c4983b2f9012602b8c4700d2230ef0
