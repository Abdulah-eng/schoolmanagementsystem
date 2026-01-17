#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Create .env file from environment variables if it doesn't exist
# Railway provides environment variables, but Laravel needs .env file
if [ ! -f .env ]; then
    echo "📝 Creating .env file from environment variables..."
    
    # Create .env file with all environment variables
    # Laravel will read from both .env and system environment
    {
        echo "# Generated automatically by Railway"
        [ -n "$APP_NAME" ] && echo "APP_NAME=$APP_NAME" || echo "APP_NAME=EduFocus"
        [ -n "$APP_ENV" ] && echo "APP_ENV=$APP_ENV" || echo "APP_ENV=production"
        [ -n "$APP_KEY" ] && echo "APP_KEY=$APP_KEY" || echo "APP_KEY="
        [ -n "$APP_DEBUG" ] && echo "APP_DEBUG=$APP_DEBUG" || echo "APP_DEBUG=false"
        [ -n "$APP_URL" ] && echo "APP_URL=$APP_URL" || echo "APP_URL=http://localhost"
        echo ""
        [ -n "$DB_CONNECTION" ] && echo "DB_CONNECTION=$DB_CONNECTION" || echo "DB_CONNECTION=pgsql"
        [ -n "$DB_HOST" ] && echo "DB_HOST=$DB_HOST" || echo "DB_HOST=localhost"
        [ -n "$DB_PORT" ] && echo "DB_PORT=$DB_PORT" || echo "DB_PORT=5432"
        [ -n "$DB_DATABASE" ] && echo "DB_DATABASE=$DB_DATABASE" || echo "DB_DATABASE="
        [ -n "$DB_USERNAME" ] && echo "DB_USERNAME=$DB_USERNAME" || echo "DB_USERNAME="
        [ -n "$DB_PASSWORD" ] && echo "DB_PASSWORD=$DB_PASSWORD" || echo "DB_PASSWORD="
        echo ""
        [ -n "$SESSION_DRIVER" ] && echo "SESSION_DRIVER=$SESSION_DRIVER" || echo "SESSION_DRIVER=database"
        [ -n "$CACHE_STORE" ] && echo "CACHE_STORE=$CACHE_STORE" || echo "CACHE_STORE=database"
        [ -n "$QUEUE_CONNECTION" ] && echo "QUEUE_CONNECTION=$QUEUE_CONNECTION" || echo "QUEUE_CONNECTION=database"
        [ -n "$MAIL_MAILER" ] && echo "MAIL_MAILER=$MAIL_MAILER" || echo "MAIL_MAILER=log"
        [ -n "$OPENAI_API_KEY" ] && echo "OPENAI_API_KEY=$OPENAI_API_KEY" || echo "OPENAI_API_KEY="
    } > .env
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null || grep -q "^APP_KEY=$" .env 2>/dev/null; then
    echo "📝 Generating application key..."
    # Use --force to generate and write to .env
    php artisan key:generate --force 2>&1 | head -5 || {
        echo "⚠️  Key generation failed, trying alternative method..."
        # Alternative: generate key and manually update .env
        GENERATED_KEY=$(php artisan key:generate --show 2>&1 | grep -o "base64:[^ ]*" | head -1)
        if [ -n "$GENERATED_KEY" ]; then
            # Use a more portable sed command
            if command -v sed >/dev/null 2>&1; then
                sed -i.bak "s|^APP_KEY=.*|APP_KEY=$GENERATED_KEY|" .env 2>/dev/null || \
                sed -i "s|^APP_KEY=.*|APP_KEY=$GENERATED_KEY|" .env 2>/dev/null || \
                echo "APP_KEY=$GENERATED_KEY" >> .env
            else
                # Fallback: append if sed doesn't work
                echo "APP_KEY=$GENERATED_KEY" >> .env
            fi
            echo "✅ Application key generated: ${GENERATED_KEY:0:20}..."
        else
            echo "❌ ERROR: Could not generate APP_KEY. Please set it manually in Railway variables."
            exit 1
        fi
    }
else
    echo "✅ APP_KEY already configured"
fi

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear any cached config first (in case .env changed)
php artisan config:clear || true

# Optimize Laravel for production
echo "⚡ Optimizing Laravel..."
php artisan config:cache || echo "⚠️  Config cache skipped"
php artisan route:cache || echo "⚠️  Route cache skipped"
php artisan view:cache || echo "⚠️  View cache skipped"

# Start the server
echo "🌐 Starting server on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
