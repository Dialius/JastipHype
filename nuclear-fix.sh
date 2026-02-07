#!/bin/bash

echo "=== NUCLEAR FIX - CLEAR EVERYTHING ==="
echo ""

cd /home/u909490256/domains/jastiphype.shop

# 1. Backup .env lama
echo "📦 Backup .env..."
cp .env .env.backup.nuclear.$(date +%Y%m%d_%H%M%S) 2>/dev/null || true

# 2. Stash changes
echo "📦 Stashing local changes..."
git stash

# 3. Pull latest
echo "📥 Pulling latest code..."
git pull origin master

# 4. FORCE copy .env.hostinger
echo "📝 FORCE updating .env..."
cp -f .env.hostinger .env

# 5. Verify .env content
echo ""
echo "🔍 Verifying .env database config:"
echo "-----------------------------------"
grep "DB_DATABASE=" .env
grep "DB_USERNAME=" .env
grep "DB_PASSWORD=" .env | cut -c1-20
echo "-----------------------------------"
echo ""

# 6. NUCLEAR: Delete ALL cache
echo "💣 NUCLEAR: Deleting ALL cache files..."
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*
rm -rf storage/framework/sessions/*
rm -rf storage/logs/*.log

# 7. Recreate cache directories
echo "📁 Recreating cache directories..."
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/views
mkdir -p storage/framework/sessions
mkdir -p storage/logs

# 8. Set permissions
echo "🔧 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 777 storage/framework/cache
chmod -R 777 storage/framework/views
chmod -R 777 storage/framework/sessions
chmod -R 777 storage/logs

# 9. Create empty log file
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log

# 10. Clear artisan cache (without database)
echo "🧹 Clearing artisan cache..."
php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# 11. Rebuild config
echo "💾 Rebuilding config cache..."
php artisan config:cache

# 12. Verify config
echo ""
echo "🔍 Verifying cached config:"
echo "-----------------------------------"
php artisan tinker --execute="echo 'DB_DATABASE: ' . config('database.connections.mysql.database') . PHP_EOL; echo 'DB_USERNAME: ' . config('database.connections.mysql.username') . PHP_EOL;" 2>/dev/null || echo "Tinker check skipped"
echo "-----------------------------------"

# 13. Test database
echo ""
echo "🧪 Testing database connection..."
php artisan tinker --execute="try { \$pdo = DB::connection()->getPdo(); echo '✅ Database connected successfully!' . PHP_EOL; } catch (Exception \$e) { echo '❌ Database error: ' . \$e->getMessage() . PHP_EOL; }" 2>/dev/null || echo "Database test skipped"

echo ""
echo "✅ NUCLEAR FIX COMPLETE!"
echo ""
echo "📝 Next steps:"
echo "1. Refresh browser: https://jastiphype.shop"
echo "2. If still error, send output of: cat .env | grep DB_"
echo ""
