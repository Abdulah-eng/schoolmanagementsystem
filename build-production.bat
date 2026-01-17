@echo off
echo ========================================
echo EduFocus Production Build Script
echo ========================================
echo.

echo [1/7] Installing PHP dependencies...
call composer install --optimize-autoloader --no-dev
if %errorlevel% neq 0 (
    echo ERROR: Composer install failed
    exit /b 1
)

echo.
echo [2/7] Installing Node dependencies...
call npm install
if %errorlevel% neq 0 (
    echo ERROR: NPM install failed
    exit /b 1
)

echo.
echo [3/7] Building frontend assets...
call npm run build
if %errorlevel% neq 0 (
    echo ERROR: Frontend build failed
    exit /b 1
)

echo.
echo [4/7] Running database migrations...
call php artisan migrate --force
if %errorlevel% neq 0 (
    echo ERROR: Migration failed
    exit /b 1
)

echo.
echo [5/7] Caching configuration...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache

echo.
echo [6/7] Optimizing autoloader...
call composer dump-autoload --optimize

echo.
echo [7/7] Clearing development caches...
call php artisan cache:clear

echo.
echo ========================================
echo Build completed successfully!
echo ========================================
echo.
echo Next steps:
echo 1. Verify .env file is configured for production
echo 2. Set APP_ENV=production and APP_DEBUG=false
echo 3. Configure database connection
echo 4. Set up web server (Apache/Nginx)
echo 5. Configure SSL certificate
echo.
pause

