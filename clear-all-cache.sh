#!/bin/bash

# ============================================
# Clear All Cache - Hostinger & Laravel
# ============================================

set -e

echo "🧹 Clearing all caches..."
echo ""

cd /home/u909490256/domains/jastiphype.shop

# Clear Laravel caches
echo "1️⃣ Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
echo "✅ Laravel caches cleared"
echo ""

# Clear LiteSpeed cache (if exists)
echo "2️⃣ Clearing LiteSpeed cache..."
if [ -d "public_html/.litespeed_cache" ]; then
    rm -rf public_html/.litespeed_cache
    echo "✅ LiteSpeed cache cleared"
else
    echo "ℹ️  No LiteSpeed cache found"
fi
echo ""

# Clear opcache (PHP)
echo "3️⃣ Clearing PHP opcache..."
php -r "if(function_exists('opcache_reset')) { opcache_reset(); echo '✅ PHP opcache cleared'; } else { echo 'ℹ️  Opcache not available'; }"
echo ""

# Touch files to update modification time
echo "4️⃣ Updating file modification times..."
touch public_html/index.php
touch public_html/.htaccess
echo "✅ Files touched"
echo ""

# Rebuild Laravel caches
echo "5️⃣ Rebuilding Laravel caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Laravel caches rebuilt"
echo ""

echo "============================================"
echo "✅ All caches cleared!"
echo "============================================"
echo ""
echo "Next steps:"
echo "1. Clear browser cache (Ctrl+Shift+Delete)"
echo "2. Try incognito/private browsing mode"
echo "3. Clear CDN cache di hPanel (jika ada)"
echo "4. Wait 1-2 minutes for propagation"
echo ""
echo "Test website:"
echo "curl -I https://jastiphype.shop"
echo ""
