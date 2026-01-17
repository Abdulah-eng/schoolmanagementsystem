#!/bin/bash
# Don't use set -e, handle errors manually to prevent crashes
set +e

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
            echo "⚠️  WARNING: Could not generate APP_KEY automatically."
            echo "⚠️  Please set APP_KEY manually in Railway variables, or the app may not work correctly."
            echo "⚠️  Continuing anyway - you can set it later..."
        fi
    }
else
    echo "✅ APP_KEY already configured"
fi

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force
MIGRATE_EXIT=$?
if [ $MIGRATE_EXIT -ne 0 ]; then
    echo "⚠️  WARNING: Migrations may have failed (exit code: $MIGRATE_EXIT)"
    echo "⚠️  Continuing anyway - check logs for details"
fi

# Clear any cached config first (in case .env changed)
echo "🧹 Clearing old caches..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Optimize Laravel for production
echo "⚡ Optimizing Laravel..."
php artisan config:cache 2>&1
CONFIG_CACHE_EXIT=$?
if [ $CONFIG_CACHE_EXIT -ne 0 ]; then
    echo "⚠️  Config cache failed, but continuing..."
fi

php artisan route:cache 2>&1 || echo "⚠️  Route cache skipped"
php artisan view:cache 2>&1 || echo "⚠️  View cache skipped"

# Verify APP_KEY is set before starting
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "❌ CRITICAL: APP_KEY is not set!"
    echo "❌ Please set APP_KEY in Railway Variables and redeploy"
    echo "❌ Generating a temporary key to prevent crash..."
    TEMP_KEY=$(php artisan key:generate --show 2>/dev/null | grep -o "base64:[^ ]*" | head -1)
    if [ -n "$TEMP_KEY" ]; then
        echo "APP_KEY=$TEMP_KEY" >> .env
        echo "✅ Temporary key set, but please set APP_KEY in Railway Variables!"
    fi
fi

# Start the server (this should never exit unless server crashes)
echo "🌐 Starting server on port $PORT..."
echo "✅ Server starting - check logs if issues occur"
php artisan serve --host=0.0.0.0 --port=$PORT
