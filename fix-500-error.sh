#!/bin/bash

echo "=== FIXING 500 ERROR ==="
echo ""

# 1. Set proper permissions
echo "🔧 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;

# 2. Clear all caches
echo "🧹 Clearing all caches..."
php artisan cache:clear 2>/dev/null || echo "Cache clear skipped"
php artisan config:clear 2>/dev/null || echo "Config clear skipped"
php artisan route:clear 2>/dev/null || echo "Route clear skipped"
php artisan view:clear 2>/dev/null || echo "View clear skipped"

# 3. Remove cached files manually
echo "🗑️  Removing cache files manually..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-*.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php

# 4. Check .env exists
echo "📝 Checking .env..."
if [ ! -f .env ]; then
    echo "⚠️  .env not found, copying from .env.hostinger..."
    cp .env.hostinger .env
fi

# 5. Verify APP_KEY
echo "🔑 Checking APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env; then
    echo "⚠️  APP_KEY not set, generating..."
    php artisan key:generate --force
fi

# 6. Rebuild config cache
echo "💾 Rebuilding config cache..."
php artisan config:cache

# 7. Check composer autoload
echo "📦 Optimizing composer autoload..."
composer dump-autoload --optimize --no-dev

# 8. Test artisan
echo "🧪 Testing artisan..."
php artisan --version

echo ""
echo "✅ Fix complete!"
echo ""
echo "📝 Next steps:"
echo "1. Refresh your browser"
echo "2. If still error 500, run: bash check-error-log.sh"
echo "3. Send me the error log output"
