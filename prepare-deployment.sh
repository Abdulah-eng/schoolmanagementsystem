#!/bin/bash

# Deployment Preparation Script for Laravel

echo "🚀 Preparing Laravel application for deployment..."

# 1. Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# 2. Install NPM dependencies and build assets
echo "📦 Installing NPM dependencies and building assets..."
npm install
npm run build

# 3. Generate application key if not exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    php artisan key:generate --force
fi

# 4. Clear and cache configuration
echo "⚙️ Optimizing Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 5. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo "✅ Deployment preparation complete!"
echo ""
echo "Next steps:"
echo "1. Set up your database"
echo "2. Configure .env file with production values"
echo "3. Run: php artisan migrate --force"
echo "4. Deploy to your chosen platform"
