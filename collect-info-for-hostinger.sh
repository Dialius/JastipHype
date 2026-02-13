#!/bin/bash

echo "=========================================="
echo "📋 COLLECT INFO FOR HOSTINGER SUPPORT"
echo "=========================================="
echo ""

cd /home/u909490256/domains/jastiphype.shop

echo "1️⃣  CLI PHP Information"
echo "=========================================="
echo ""

echo "which php:"
which php
echo ""

echo "php -v:"
php -v
echo ""

echo "Loaded Configuration File:"
php -i | grep "Loaded Configuration File"
echo ""

echo "mbstring in CLI:"
php -m | grep mbstring || echo "(mbstring NOT loaded)"
echo ""

echo "2️⃣  Where Error Happens"
echo "=========================================="
echo ""

echo "Error occurs in: WEB REQUEST (Browser)"
echo "URL: https://jastiphype.shop"
echo "Result: HTTP 500 Internal Server Error"
echo ""

echo "Last error from log:"
grep "mb_split" storage/logs/laravel.log | tail -1
echo ""

echo "3️⃣  Test Web PHP (via test file)"
echo "=========================================="
echo ""

# Create test file
cat > public/test-web-php.php << 'EOF'
<?php
echo "PHP Version: " . phpversion() . "\n";
echo "mbstring loaded: " . (extension_loaded('mbstring') ? 'YES' : 'NO') . "\n";
echo "mb_split exists: " . (function_exists('mb_split') ? 'YES' : 'NO') . "\n";
echo "\nLoaded extensions:\n";
$extensions = get_loaded_extensions();
foreach ($extensions as $ext) {
    if (stripos($ext, 'mb') !== false || stripos($ext, 'string') !== false) {
        echo "- $ext\n";
    }
}
EOF

echo "✅ Created test file: public/test-web-php.php"
echo ""
echo "Access via browser: https://jastiphype.shop/test-web-php.php"
echo ""
echo "⚠️  IMPORTANT: Check the output in browser, then delete:"
echo "   rm public/test-web-php.php"
echo ""

echo "4️⃣  Current .htaccess Configuration"
echo "=========================================="
echo ""

if [ -f "public/.htaccess" ]; then
    echo "public/.htaccess (first 20 lines):"
    head -20 public/.htaccess
else
    echo "public/.htaccess not found"
fi
echo ""

if [ -f "/home/u909490256/public_html/.htaccess" ]; then
    echo "public_html/.htaccess (first 20 lines):"
    head -20 /home/u909490256/public_html/.htaccess
else
    echo "public_html/.htaccess not found"
fi
echo ""

echo "5️⃣  .user.ini Configuration"
echo "=========================================="
echo ""

if [ -f ".user.ini" ]; then
    echo ".user.ini content:"
    cat .user.ini
else
    echo ".user.ini not found"
fi
echo ""

echo "=========================================="
echo "📊 SUMMARY FOR HOSTINGER"
echo "=========================================="
echo ""
echo "CLI PHP:"
echo "  - Binary: $(which php)"
echo "  - Version: $(php -v | head -1)"
echo "  - mbstring: $(php -m | grep -q mbstring && echo 'Loaded' || echo 'NOT loaded')"
echo ""
echo "Error Location:"
echo "  - WEB REQUEST (Browser) ✅"
echo "  - URL: https://jastiphype.shop"
echo "  - Error: Call to undefined function mb_split()"
echo ""
echo "Next Steps:"
echo "  1. Access https://jastiphype.shop/test-web-php.php in browser"
echo "  2. Check if mbstring is loaded in web environment"
echo "  3. Copy output above and send to Hostinger support"
echo "  4. Delete test file: rm public/test-web-php.php"
echo ""
