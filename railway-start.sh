#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Railway automatically creates .env from environment variables
# Generate APP_KEY if not already set in environment
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "📝 Generating application key..."
    php artisan key:generate --force || echo "⚠️  Key generation skipped (may already be set)"
else
    echo "✅ APP_KEY already configured"
fi

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Optimize Laravel for production
echo "⚡ Optimizing Laravel..."
php artisan config:cache || echo "⚠️  Config cache skipped"
php artisan route:cache || echo "⚠️  Route cache skipped"
php artisan view:cache || echo "⚠️  View cache skipped"

# Start the server
echo "🌐 Starting server on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
