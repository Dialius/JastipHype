#!/bin/bash

echo "=== FIX SSH KEY & ENV ==="
echo ""

# 1. Fix SSH key permission
echo "🔑 Fixing SSH key permission..."
if [ -f ~/.ssh/hostinger_deploy ]; then
    chmod 600 ~/.ssh/hostinger_deploy
    echo "✅ SSH key permission fixed (600)"
else
    echo "⚠️  SSH key not found at ~/.ssh/hostinger_deploy"
fi

# 2. Pull latest code
echo ""
echo "📥 Pulling latest code..."
cd /home/u909490256/domains/jastiphype.shop
git pull origin master

# 3. Backup current .env
echo ""
echo "📦 Backup current .env..."
if [ -f .env ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ Backup created"
fi

# 4. Copy .env.hostinger to .env
echo ""
echo "📝 Updating .env with production config..."
cp .env.hostinger .env
echo "✅ .env updated"

# 5. Verify database credentials
echo ""
echo "🔍 Verifying database credentials..."
grep "DB_DATABASE" .env
grep "DB_USERNAME" .env

# 6. Set permissions
echo ""
echo "🔧 Setting permissions..."
chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 664 {} \; 2>/dev/null || true
find bootstrap/cache -type f -exec chmod 664 {} \; 2>/dev/null || true

# 7. Clear cache
echo ""
echo "🧹 Clearing cache..."
rm -f bootstrap/cache/*.php
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# 8. Rebuild cache
echo ""
echo "💾 Rebuilding cache..."
php artisan config:cache

echo ""
echo "✅ FIX COMPLETE!"
echo ""
echo "📝 Next steps:"
echo "1. Refresh browser: https://jastiphype.shop"
echo "2. If still error, check: tail -20 storage/logs/laravel.log"
