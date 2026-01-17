#!/bin/bash

echo "========================================"
echo "EduFocus Production Build Script"
echo "========================================"
echo ""

echo "[1/7] Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev
if [ $? -ne 0 ]; then
    echo "ERROR: Composer install failed"
    exit 1
fi

echo ""
echo "[2/7] Installing Node dependencies..."
npm install
if [ $? -ne 0 ]; then
    echo "ERROR: NPM install failed"
    exit 1
fi

echo ""
echo "[3/7] Building frontend assets..."
npm run build
if [ $? -ne 0 ]; then
    echo "ERROR: Frontend build failed"
    exit 1
fi

echo ""
echo "[4/7] Running database migrations..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "ERROR: Migration failed"
    exit 1
fi

echo ""
echo "[5/7] Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "[6/7] Optimizing autoloader..."
composer dump-autoload --optimize

echo ""
echo "[7/7] Clearing development caches..."
php artisan cache:clear

echo ""
echo "========================================"
echo "Build completed successfully!"
echo "========================================"
echo ""
echo "Next steps:"
echo "1. Verify .env file is configured for production"
echo "2. Set APP_ENV=production and APP_DEBUG=false"
echo "3. Configure database connection"
echo "4. Set up web server (Apache/Nginx)"
echo "5. Configure SSL certificate"
echo ""

