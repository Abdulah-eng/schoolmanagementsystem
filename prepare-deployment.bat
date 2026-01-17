@echo off
echo Preparing Laravel application for deployment...

echo Installing Composer dependencies...
composer install --no-dev --optimize-autoloader

echo Installing NPM dependencies and building assets...
call npm install
call npm run build

echo Optimizing Laravel...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo.
echo Deployment preparation complete!
echo.
echo Next steps:
echo 1. Set up your database
echo 2. Configure .env file with production values
echo 3. Run: php artisan migrate --force
echo 4. Deploy to your chosen platform
pause
