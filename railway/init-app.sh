#!/bin/bash
# Railway Laravel Init Script
# Make sure this file has executable permissions: chmod +x railway/init-app.sh

# Exit the script if any command fails
set -e

echo "⏳ Waiting for database to be ready..."
max_attempts=30
attempt=1
while [ $attempt -le $max_attempts ]; do
    if php artisan db:show > /dev/null 2>&1; then
        echo "✅ Database is ready!"
        break
    fi
    echo "Attempt $attempt/$max_attempts - Database not ready, waiting 2 seconds..."
    sleep 2
    attempt=$((attempt + 1))
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ Database connection failed after $max_attempts attempts"
    exit 1
fi

echo "🔗 Creating storage symlink..."
php artisan storage:link || true

echo "🗄️ Running database migrations..."
php artisan migrate --force

echo "🧹 Clearing cache..."
php artisan optimize:clear

echo "⚡ Caching configuration..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "✅ Laravel initialization complete!"
