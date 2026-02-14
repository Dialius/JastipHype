#!/bin/bash

# ============================================
# Fix Domain Root untuk jastiphype.shop
# ============================================

set -e

echo "🚀 Fixing domain root for jastiphype.shop..."
echo ""

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Paths
LARAVEL_ROOT="/home/u909490256/domains/jastiphype.shop"
DOMAIN_PUBLIC_HTML="$LARAVEL_ROOT/public_html"
LARAVEL_PUBLIC="$LARAVEL_ROOT/public"

echo "📂 Paths:"
echo "   Laravel Root: $LARAVEL_ROOT"
echo "   Domain public_html: $DOMAIN_PUBLIC_HTML"
echo "   Laravel Public: $LARAVEL_PUBLIC"
echo ""

# Step 1: Backup domain public_html jika ada isinya
if [ -d "$DOMAIN_PUBLIC_HTML" ] && [ "$(ls -A $DOMAIN_PUBLIC_HTML)" ]; then
    echo "💾 Backing up current domain public_html..."
    BACKUP_DIR="$LARAVEL_ROOT/public_html_backup_$(date +%Y%m%d_%H%M%S)"
    cp -r "$DOMAIN_PUBLIC_HTML" "$BACKUP_DIR"
    echo -e "${GREEN}✅ Backup created at: $BACKUP_DIR${NC}"
    echo ""
fi

# Step 2: Clear domain public_html
echo "🧹 Clearing domain public_html..."
rm -rf "$DOMAIN_PUBLIC_HTML"/*
rm -rf "$DOMAIN_PUBLIC_HTML"/.* 2>/dev/null || true
echo -e "${GREEN}✅ Domain public_html cleared${NC}"
echo ""

# Step 3: Copy semua file dari Laravel public ke domain public_html
echo "📦 Copying Laravel public folder to domain public_html..."
cp -r "$LARAVEL_PUBLIC"/* "$DOMAIN_PUBLIC_HTML/"
cp "$LARAVEL_PUBLIC"/.htaccess "$DOMAIN_PUBLIC_HTML/" 2>/dev/null || true
echo -e "${GREEN}✅ Files copied${NC}"
echo ""

# Step 4: Update index.php untuk path yang benar
echo "🔧 Updating index.php paths..."
cat > "$DOMAIN_PUBLIC_HTML/index.php" << 'EOF'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
EOF
echo -e "${GREEN}✅ index.php updated with correct paths${NC}"
echo ""

# Step 5: Set permissions
echo "🔐 Setting permissions..."
chmod 755 "$DOMAIN_PUBLIC_HTML"
chmod 644 "$DOMAIN_PUBLIC_HTML/index.php"
chmod 644 "$DOMAIN_PUBLIC_HTML/.htaccess" 2>/dev/null || true
chmod -R 755 "$DOMAIN_PUBLIC_HTML"

# Laravel permissions
chmod -R 775 "$LARAVEL_ROOT/storage"
chmod -R 775 "$LARAVEL_ROOT/bootstrap/cache"
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

# Step 6: Create symbolic link untuk build folder (Vite assets)
echo "🔗 Creating symbolic link for build folder..."
cd "$DOMAIN_PUBLIC_HTML"

if [ -d "$LARAVEL_PUBLIC/build" ]; then
    rm -rf build
    ln -s "$LARAVEL_PUBLIC/build" build
    echo "   ✓ Linked build folder"
fi

# Link storage folder
if [ -d "$LARAVEL_ROOT/storage/app/public" ]; then
    rm -rf storage
    ln -s "$LARAVEL_ROOT/storage/app/public" storage
    echo "   ✓ Linked storage folder"
fi

echo -e "${GREEN}✅ Symbolic links created${NC}"
echo ""

# Step 7: Clear Laravel caches
echo "🧹 Clearing Laravel caches..."
cd "$LARAVEL_ROOT"
php artisan optimize:clear 2>/dev/null || echo "   ⚠️ Some caches couldn't be cleared"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✅ Caches cleared and rebuilt${NC}"
echo ""

# Step 8: Test setup
echo "🏥 Testing setup..."
echo ""

# Test 1: Check if files exist
if [ -f "$DOMAIN_PUBLIC_HTML/index.php" ]; then
    echo -e "${GREEN}✓${NC} index.php exists"
else
    echo -e "${RED}✗${NC} index.php missing"
fi

if [ -f "$DOMAIN_PUBLIC_HTML/.htaccess" ]; then
    echo -e "${GREEN}✓${NC} .htaccess exists"
else
    echo -e "${RED}✗${NC} .htaccess missing"
fi

echo ""
echo "📊 Directory structure:"
echo "   $DOMAIN_PUBLIC_HTML/"
echo "   ├── index.php (Laravel entry point)"
echo "   ├── .htaccess (Laravel rewrite rules)"
echo "   ├── build/ (symlink to Laravel public/build)"
echo "   └── storage/ (symlink to Laravel storage/app/public)"
echo ""

# Test 2: HTTP test
echo "🌐 Testing HTTP response..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://jastiphype.shop 2>/dev/null || echo "000")

if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✅ SUCCESS! Website is responding with HTTP 200${NC}"
elif [ "$HTTP_CODE" = "000" ]; then
    echo -e "${YELLOW}⚠️  Could not test (curl might not be available)${NC}"
else
    echo -e "${YELLOW}⚠️  Website returned HTTP $HTTP_CODE${NC}"
    echo "   This might be normal if Laravel needs configuration"
fi

echo ""
echo "============================================"
echo -e "${GREEN}🎉 Setup Complete!${NC}"
echo "============================================"
echo ""
echo "Next steps:"
echo "1. Visit: https://jastiphype.shop"
echo "2. If you see errors, check Laravel logs:"
echo "   tail -f $LARAVEL_ROOT/storage/logs/laravel.log"
echo ""
echo "3. If assets are missing, run:"
echo "   cd $LARAVEL_ROOT && npm run build"
echo ""
