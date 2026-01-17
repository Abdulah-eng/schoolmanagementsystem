@echo off
echo ========================================
echo EduFocus Application Setup
echo ========================================
echo.

echo [1/6] Installing PHP dependencies...
composer install
if %errorlevel% neq 0 (
    echo ERROR: Composer install failed. Make sure PHP and Composer are installed.
    pause
    exit /b 1
)

echo.
echo [2/6] Installing Node.js dependencies...
npm install
if %errorlevel% neq 0 (
    echo ERROR: NPM install failed. Make sure Node.js is installed.
    pause
    exit /b 1
)

echo.
echo [3/6] Building frontend assets...
npm run build
if %errorlevel% neq 0 (
    echo ERROR: Frontend build failed.
    pause
    exit /b 1
)

echo.
echo [4/6] Running database migrations...
php artisan migrate
if %errorlevel% neq 0 (
    echo ERROR: Database migration failed.
    pause
    exit /b 1
)

echo.
echo [5/6] Seeding demo data...
php artisan db:seed
if %errorlevel% neq 0 (
    echo ERROR: Database seeding failed.
    pause
    exit /b 1
)

echo.
echo [6/6] Starting development server...
echo.
echo ========================================
echo Setup Complete! 
echo ========================================
echo.
echo The application will be available at: http://127.0.0.1:8000
echo.
echo Demo Users:
echo - Student: alex.johnson@student.com / password123
echo - Admin: admin@edufocus.com / admin123
echo - Parent: parent@edufocus.com / parent123
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

php artisan serve
