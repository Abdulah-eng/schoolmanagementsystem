@echo off
echo ========================================
echo EduFocus Application Setup with XAMPP
echo ========================================
echo.

echo [1/8] Checking PHP installation...
C:\xampp\php\php.exe --version
if %errorlevel% neq 0 (
    echo ERROR: PHP not found in XAMPP. Please make sure XAMPP is installed and running.
    pause
    exit /b 1
)

echo.
echo [2/8] Checking Composer installation...
C:\xampp\php\php.exe composer.phar --version
if %errorlevel% neq 0 (
    echo ERROR: Composer not found. Please install Composer first.
    echo Download from: https://getcomposer.org/download/
    pause
    exit /b 1
)

echo.
echo [3/8] Updating .env file for MySQL...
echo APP_NAME=EduFocus > .env
echo APP_ENV=local >> .env
echo APP_KEY=base64:yXPZLo/L352Sy5rUdnLJB96+5IjEEJvkJSHSV6Mw5JY= >> .env
echo APP_DEBUG=true >> .env
echo APP_URL=http://127.0.0.1:8000 >> .env
echo. >> .env
echo APP_LOCALE=en >> .env
echo APP_FALLBACK_LOCALE=en >> .env
echo APP_FAKER_LOCALE=en_US >> .env
echo. >> .env
echo APP_MAINTENANCE_DRIVER=file >> .env
echo. >> .env
echo PHP_CLI_SERVER_WORKERS=4 >> .env
echo. >> .env
echo BCRYPT_ROUNDS=12 >> .env
echo. >> .env
echo LOG_CHANNEL=stack >> .env
echo LOG_STACK=single >> .env
echo LOG_DEPRECATIONS_CHANNEL=null >> .env
echo LOG_LEVEL=debug >> .env
echo. >> .env
echo DB_CONNECTION=mysql >> .env
echo DB_HOST=127.0.0.1 >> .env
echo DB_PORT=3306 >> .env
echo DB_DATABASE=edufocus >> .env
echo DB_USERNAME=root >> .env
echo DB_PASSWORD= >> .env
echo. >> .env
echo SESSION_DRIVER=database >> .env
echo SESSION_LIFETIME=120 >> .env
echo SESSION_ENCRYPT=false >> .env
echo SESSION_PATH=/ >> .env
echo SESSION_DOMAIN=127.0.0.1 >> .env
echo. >> .env
echo BROADCAST_CONNECTION=log >> .env
echo FILESYSTEM_DISK=local >> .env
echo QUEUE_CONNECTION=database >> .env
echo. >> .env
echo CACHE_STORE=database >> .env
echo. >> .env
echo MEMCACHED_HOST=127.0.0.1 >> .env
echo. >> .env
echo REDIS_CLIENT=phpredis >> .env
echo REDIS_HOST=127.0.0.1 >> .env
echo REDIS_PASSWORD=null >> .env
echo REDIS_PORT=6379 >> .env
echo. >> .env
echo MAIL_MAILER=log >> .env
echo MAIL_SCHEME=null >> .env
echo MAIL_HOST=127.0.0.1 >> .env
echo MAIL_PORT=2525 >> .env
echo MAIL_USERNAME=null >> .env
echo MAIL_PASSWORD=null >> .env
echo MAIL_FROM_ADDRESS="hello@edufocus.com" >> .env
echo MAIL_FROM_NAME="${APP_NAME}" >> .env
echo. >> .env
echo AWS_ACCESS_KEY_ID= >> .env
echo AWS_SECRET_ACCESS_KEY= >> .env
echo AWS_DEFAULT_REGION=us-east-1 >> .env
echo AWS_BUCKET= >> .env
echo AWS_USE_PATH_STYLE_ENDPOINT=false >> .env
echo. >> .env
echo VITE_APP_NAME="${APP_NAME}" >> .env
echo. >> .env
echo OPENAI_API_KEY=your-openai-api-key-here >> .env

echo.
echo [4/8] Installing PHP dependencies...
C:\xampp\php\php.exe composer.phar install
if %errorlevel% neq 0 (
    echo ERROR: Composer install failed.
    pause
    exit /b 1
)

echo.
echo [5/8] Installing Node.js dependencies...
npm install
if %errorlevel% neq 0 (
    echo ERROR: NPM install failed.
    pause
    exit /b 1
)

echo.
echo [6/8] Building frontend assets...
npm run build
if %errorlevel% neq 0 (
    echo ERROR: Frontend build failed.
    pause
    exit /b 1
)

echo.
echo [7/8] Setting up database...
echo Please make sure you have created the 'edufocus' database in phpMyAdmin
echo 1. Open XAMPP Control Panel
echo 2. Click 'Admin' next to MySQL to open phpMyAdmin
echo 3. Click 'New' in the left sidebar
echo 4. Database name: edufocus
echo 5. Collation: utf8mb4_unicode_ci
echo 6. Click 'Create'
echo.
pause

C:\xampp\php\php.exe artisan migrate
if %errorlevel% neq 0 (
    echo ERROR: Database migration failed. Please check your MySQL connection.
    pause
    exit /b 1
)

echo.
echo [8/8] Seeding demo data...
C:\xampp\php\php.exe artisan db:seed
if %errorlevel% neq 0 (
    echo ERROR: Database seeding failed.
    pause
    exit /b 1
)

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

C:\xampp\php\php.exe artisan serve
