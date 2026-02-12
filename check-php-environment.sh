#!/bin/bash

echo "=========================================="
echo "🔍 CHECK PHP ENVIRONMENT MISMATCH"
echo "=========================================="
echo ""

cd /home/u909490256/domains/jastiphype.shop

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo "1️⃣  Checking PHP CLI version and mbstring..."
echo "-------------------------------------------"
echo "PHP CLI Version:"
php -v | head -1
echo ""
echo "PHP CLI mbstring status:"
if php -m | grep -q "mbstring"; then
    echo -e "${GREEN}✅ mbstring is loaded in CLI${NC}"
else
    echo -e "${RED}❌ mbstring is NOT loaded in CLI${NC}"
fi
echo ""
echo "PHP CLI mb_split() test:"
php -r "if (function_exists('mb_split')) { echo '✅ mb_split() is available in CLI\n'; } else { echo '❌ mb_split() is NOT available in CLI\n'; }"
echo ""

echo "2️⃣  Checking which PHP binary is used..."
echo "-------------------------------------------"
which php
php -i | grep "Loaded Configuration File"
echo ""

echo "3️⃣  Checking web PHP version (via phpinfo test)..."
echo "-------------------------------------------"
# Create temporary phpinfo file
cat > public/phpinfo-test-temp.php << 'EOF'
<?php
echo "PHP Version: " . phpversion() . "\n";
echo "mbstring loaded: " . (extension_loaded('mbstring') ? 'YES' : 'NO') . "\n";
echo "mb_split exists: " . (function_exists('mb_split') ? 'YES' : 'NO') . "\n";
echo "Loaded extensions: " . implode(', ', get_loaded_extensions()) . "\n";
EOF

echo "Created test file: public/phpinfo-test-temp.php"
echo "Access via: https://jastiphype.shop/phpinfo-test-temp.php"
echo ""
echo -e "${YELLOW}⚠️  IMPORTANT: Delete this file after checking!${NC}"
echo "   Run: rm public/phpinfo-test-temp.php"
echo ""

echo "4️⃣  Extracting full error from Laravel log..."
echo "-------------------------------------------"
echo "Last mb_split error with full path:"
grep -A 2 "mb_split" storage/logs/laravel.log | tail -10
echo ""

echo "5️⃣  Checking .htaccess PHP handler..."
echo "-------------------------------------------"
if [ -f "public/.htaccess" ]; then
    echo "Checking for PHP handler directives in public/.htaccess:"
    grep -i "addhandler\|addtype\|php" public/.htaccess || echo "No PHP handler directives found"
else
    echo "public/.htaccess not found"
fi
echo ""

if [ -f ".htaccess" ]; then
    echo "Checking for PHP handler directives in root .htaccess:"
    grep -i "addhandler\|addtype\|php" .htaccess || echo "No PHP handler directives found"
else
    echo "Root .htaccess not found"
fi
echo ""

echo "6️⃣  Checking for multiple PHP versions..."
echo "-------------------------------------------"
echo "Available PHP binaries:"
ls -la /usr/bin/php* 2>/dev/null || echo "Cannot list /usr/bin/php*"
echo ""
ls -la /opt/alt/php*/usr/bin/php 2>/dev/null || echo "Cannot list /opt/alt/php*"
echo ""

echo "7️⃣  Testing Laravel with current PHP..."
echo "-------------------------------------------"
echo "Running: php artisan --version"
php artisan --version 2>&1
echo ""

echo "8️⃣  Checking public_html index.php..."
echo "-------------------------------------------"
if [ -f "/home/u909490256/public_html/index.php" ]; then
    echo "public_html/index.php exists"
    echo "First 10 lines:"
    head -10 /home/u909490256/public_html/index.php
else
    echo "public_html/index.php not found"
fi
echo ""

echo "=========================================="
echo "📊 SUMMARY & RECOMMENDATIONS"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Access https://jastiphype.shop/phpinfo-test-temp.php in browser"
echo "2. Check if mbstring and mb_split are available in web environment"
echo "3. Compare with CLI environment above"
echo "4. If mismatch found, we need to:"
echo "   - Force PHP version in .htaccess"
echo "   - Or update public_html/index.php to use correct PHP"
echo "   - Or contact Hostinger to sync PHP versions"
echo ""
echo -e "${RED}⚠️  REMEMBER TO DELETE TEST FILE:${NC}"
echo "   rm public/phpinfo-test-temp.php"
echo ""
