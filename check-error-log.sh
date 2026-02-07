#!/bin/bash

echo "=== CHECKING ERROR LOGS ==="
echo ""

# 1. Check Laravel log
echo "📋 Laravel Error Log (last 50 lines):"
echo "======================================="
tail -50 storage/logs/laravel.log 2>/dev/null || echo "No Laravel log found"

echo ""
echo ""

# 2. Check PHP error log
echo "📋 PHP Error Log (last 30 lines):"
echo "=================================="
tail -30 storage/logs/php_errors.log 2>/dev/null || echo "No PHP error log found"

echo ""
echo ""

# 3. Check permissions
echo "📋 Checking Permissions:"
echo "========================"
ls -la storage/logs/
ls -la bootstrap/cache/

echo ""
echo ""

# 4. Check .env
echo "📋 Checking .env file:"
echo "======================"
if [ -f .env ]; then
    echo "✅ .env exists"
    echo "APP_ENV: $(grep APP_ENV .env)"
    echo "APP_DEBUG: $(grep APP_DEBUG .env)"
    echo "APP_KEY: $(grep APP_KEY .env | cut -c1-20)..."
    echo "FILESYSTEM_DISK: $(grep FILESYSTEM_DISK .env)"
else
    echo "❌ .env NOT FOUND!"
fi

echo ""
echo ""

# 5. Check config cache
echo "📋 Checking Config Cache:"
echo "=========================="
if [ -f bootstrap/cache/config.php ]; then
    echo "✅ Config cache exists"
    echo "Size: $(ls -lh bootstrap/cache/config.php | awk '{print $5}')"
else
    echo "⚠️  No config cache"
fi

echo ""
echo ""

# 6. Test artisan
echo "📋 Testing Artisan:"
echo "==================="
php artisan --version 2>&1 || echo "❌ Artisan failed"

echo ""
echo "✅ Check complete!"
