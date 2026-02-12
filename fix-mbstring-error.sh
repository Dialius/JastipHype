#!/bin/bash

echo "=========================================="
echo "🔧 FIX MBSTRING ERROR - mb_split()"
echo "=========================================="
echo ""

cd /home/u909490256/domains/jastiphype.shop

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Check PHP version
echo "1️⃣  Checking PHP version..."
PHP_VERSION=$(php -v | head -1)
echo "$PHP_VERSION"
echo ""

# 2. Check if mbstring is loaded
echo "2️⃣  Checking mbstring extension..."
if php -m | grep -q "mbstring"; then
    echo -e "${GREEN}✅ mbstring is loaded${NC}"
else
    echo -e "${RED}❌ mbstring is NOT loaded${NC}"
    echo "   Contact Hostinger support to enable mbstring"
fi
echo ""

# 3. Check specific mb_split function
echo "3️⃣  Testing mb_split() function..."
php -r "if (function_exists('mb_split')) { echo 'mb_split() is available'; } else { echo 'mb_split() is NOT available'; }" 2>&1
echo ""
echo ""

# 4. Check mbstring.func_overload (deprecated but might cause issues)
echo "4️⃣  Checking mbstring configuration..."
php -i | grep -E "mbstring|mb_split" | head -10
echo ""

# 5. Try to enable mbstring via .user.ini
echo "5️⃣  Creating .user.ini to ensure mbstring is enabled..."
cat > .user.ini << 'EOF'
; Force enable mbstring
extension=mbstring.so

; PHP Configuration for JastipHype Production
display_errors = Off
log_errors = On
error_reporting = E_ALL
date.timezone = Asia/Jakarta
EOF

chmod 644 .user.ini
echo -e "${GREEN}✅ .user.ini created${NC}"
cat .user.ini
echo ""

# 6. Clear all caches
echo "6️⃣  Clearing all caches..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
echo -e "${GREEN}✅ Caches cleared${NC}"
echo ""

# 7. Test Laravel
echo "7️⃣  Testing Laravel..."
php artisan --version 2>&1
echo ""

# 8. Check if error persists
echo "8️⃣  Checking for mb_split error in log..."
if grep -q "mb_split" storage/logs/laravel.log 2>/dev/null; then
    echo -e "${YELLOW}⚠️  mb_split error still in log (might be old)${NC}"
    echo "   Last occurrence:"
    grep "mb_split" storage/logs/laravel.log | tail -1
else
    echo -e "${GREEN}✅ No mb_split error in current log${NC}"
fi
echo ""

echo "=========================================="
echo "📊 SUMMARY"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Wait 5-10 minutes for .user.ini to take effect"
echo "2. Test website: https://jastiphype.shop"
echo "3. If still error, contact Hostinger support:"
echo "   'mbstring extension is enabled but mb_split() function is not available'"
echo ""
echo "Alternative fix (if .user.ini doesn't work):"
echo "1. Login to hPanel"
echo "2. Go to Advanced → PHP Configuration"
echo "3. Find 'mbstring' in extensions list"
echo "4. Make sure it's checked/enabled"
echo "5. Click 'Save'"
echo "6. Wait 2-3 minutes"
echo ""
