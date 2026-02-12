#!/bin/bash

echo "=========================================="
echo "🔍 JASTIPHYPE.SHOP - ERROR 500 DIAGNOSIS"
echo "=========================================="
echo ""

cd /home/u909490256/domains/jastiphype.shop

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to check status
check_status() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ PASS${NC}"
    else
        echo -e "${RED}❌ FAIL${NC}"
    fi
}

# 1. CHECK PHP VERSION
echo "1️⃣  Checking PHP Version..."
php -v | head -1
PHP_VERSION=$(php -v | head -1 | awk '{print $2}' | cut -d. -f1,2)
if (( $(echo "$PHP_VERSION >= 8.2" | bc -l) )); then
    echo -e "${GREEN}✅ PHP version OK ($PHP_VERSION)${NC}"
else
    echo -e "${RED}❌ PHP version too old ($PHP_VERSION). Need 8.2+${NC}"
fi
echo ""

# 2. CHECK VENDOR DIRECTORY
echo "2️⃣  Checking Vendor Directory..."
if [ -d "vendor" ] && [ -f "vendor/autoload.php" ]; then
    echo -e "${GREEN}✅ Vendor directory exists${NC}"
else
    echo -e "${RED}❌ Vendor directory missing or incomplete${NC}"
    echo "   Run: composer install --no-dev --optimize-autoloader"
fi
echo ""

# 3. CHECK .ENV FILE
echo "3️⃣  Checking .env File..."
if [ -f ".env" ]; then
    echo -e "${GREEN}✅ .env file exists${NC}"
    echo ""
    echo "   Database Configuration:"
    grep "DB_DATABASE=" .env
    grep "DB_USERNAME=" .env
    echo "   DB_PASSWORD=***hidden***"
    echo ""
    echo "   APP Configuration:"
    grep "APP_KEY=" .env | cut -c1-30
    grep "APP_URL=" .env
    grep "FILESYSTEM_DISK=" .env
else
    echo -e "${RED}❌ .env file missing${NC}"
    echo "   Run: cp .env.hostinger .env"
fi
echo ""

# 4. CHECK APP_KEY
echo "4️⃣  Checking APP_KEY..."
APP_KEY=$(grep "APP_KEY=" .env | cut -d= -f2)
if [ -n "$APP_KEY" ] && [ "$APP_KEY" != "base64:" ]; then
    echo -e "${GREEN}✅ APP_KEY is set${NC}"
else
    echo -e "${RED}❌ APP_KEY is empty or invalid${NC}"
    echo "   Run: php artisan key:generate --force"
fi
echo ""

# 5. CHECK STORAGE PERMISSIONS
echo "5️⃣  Checking Storage Permissions..."
STORAGE_PERM=$(stat -c "%a" storage 2>/dev/null || stat -f "%Lp" storage 2>/dev/null)
if [ "$STORAGE_PERM" = "775" ] || [ "$STORAGE_PERM" = "777" ]; then
    echo -e "${GREEN}✅ Storage permissions OK ($STORAGE_PERM)${NC}"
else
    echo -e "${YELLOW}⚠️  Storage permissions: $STORAGE_PERM (should be 775)${NC}"
    echo "   Run: chmod -R 775 storage"
fi

CACHE_PERM=$(stat -c "%a" bootstrap/cache 2>/dev/null || stat -f "%Lp" bootstrap/cache 2>/dev/null)
if [ "$CACHE_PERM" = "775" ] || [ "$CACHE_PERM" = "777" ]; then
    echo -e "${GREEN}✅ Bootstrap/cache permissions OK ($CACHE_PERM)${NC}"
else
    echo -e "${YELLOW}⚠️  Bootstrap/cache permissions: $CACHE_PERM (should be 775)${NC}"
    echo "   Run: chmod -R 775 bootstrap/cache"
fi
echo ""

# 6. CHECK UPLOADS FOLDER
echo "6️⃣  Checking Uploads Folder..."
if [ -d "public/uploads" ]; then
    echo -e "${GREEN}✅ public/uploads exists${NC}"
    echo "   Subfolders:"
    ls -d public/uploads/*/ 2>/dev/null | sed 's/^/   - /' || echo "   (no subfolders)"
else
    echo -e "${RED}❌ public/uploads missing${NC}"
    echo "   Run: mkdir -p public/uploads/{products,brands,categories,banners}"
fi
echo ""

# 7. CHECK DATABASE CONNECTION
echo "7️⃣  Testing Database Connection..."
DB_TEST=$(php artisan tinker --execute="try { \$pdo = DB::connection()->getPdo(); echo 'OK'; } catch (Exception \$e) { echo 'ERROR: ' . \$e->getMessage(); }" 2>&1)
if [[ "$DB_TEST" == *"OK"* ]]; then
    echo -e "${GREEN}✅ Database connection successful${NC}"
else
    echo -e "${RED}❌ Database connection failed${NC}"
    echo "   Error: $DB_TEST"
fi
echo ""

# 8. CHECK CACHED CONFIG
echo "8️⃣  Checking Cached Configuration..."
if [ -f "bootstrap/cache/config.php" ]; then
    echo -e "${YELLOW}⚠️  Config cache exists${NC}"
    echo "   Cached DB: $(php artisan tinker --execute="echo config('database.connections.mysql.database');" 2>/dev/null)"
    echo "   Actual .env DB: $(grep DB_DATABASE= .env | cut -d= -f2)"
    
    CACHED_DB=$(php artisan tinker --execute="echo config('database.connections.mysql.database');" 2>/dev/null)
    ACTUAL_DB=$(grep DB_DATABASE= .env | cut -d= -f2)
    
    if [ "$CACHED_DB" = "$ACTUAL_DB" ]; then
        echo -e "${GREEN}✅ Cache matches .env${NC}"
    else
        echo -e "${RED}❌ Cache MISMATCH! Need to clear cache${NC}"
        echo "   Run: php artisan config:clear && php artisan config:cache"
    fi
else
    echo -e "${YELLOW}⚠️  No config cache (will be slower)${NC}"
    echo "   Run: php artisan config:cache"
fi
echo ""

# 9. CHECK LARAVEL LOG
echo "9️⃣  Checking Laravel Error Log..."
if [ -f "storage/logs/laravel.log" ]; then
    LOG_SIZE=$(du -h storage/logs/laravel.log | cut -f1)
    echo -e "${GREEN}✅ Log file exists (size: $LOG_SIZE)${NC}"
    echo ""
    echo "   Last 20 lines of error log:"
    echo "   =========================================="
    tail -20 storage/logs/laravel.log | sed 's/^/   /'
    echo "   =========================================="
else
    echo -e "${YELLOW}⚠️  No log file found${NC}"
    echo "   This might mean Laravel hasn't run yet, or permissions issue"
fi
echo ""

# 10. CHECK PUBLIC_HTML
echo "🔟 Checking public_html Structure..."
if [ -d "/home/u909490256/public_html" ]; then
    echo -e "${GREEN}✅ public_html exists${NC}"
    echo "   Contents:"
    ls -lh /home/u909490256/public_html/ | head -10 | sed 's/^/   /'
    
    if [ -f "/home/u909490256/public_html/index.php" ]; then
        echo -e "${GREEN}✅ index.php exists in public_html${NC}"
    else
        echo -e "${RED}❌ index.php missing in public_html${NC}"
        echo "   Run: cp -rf public/* /home/u909490256/public_html/"
    fi
else
    echo -e "${RED}❌ public_html not found${NC}"
fi
echo ""

# SUMMARY
echo "=========================================="
echo "📊 DIAGNOSIS SUMMARY"
echo "=========================================="
echo ""

# Count issues
ISSUES=0

# Check each critical item
[ ! -d "vendor" ] && ((ISSUES++)) && echo -e "${RED}❌ Missing vendor directory${NC}"
[ ! -f ".env" ] && ((ISSUES++)) && echo -e "${RED}❌ Missing .env file${NC}"
[ -z "$APP_KEY" ] && ((ISSUES++)) && echo -e "${RED}❌ APP_KEY not set${NC}"
[[ "$DB_TEST" != *"OK"* ]] && ((ISSUES++)) && echo -e "${RED}❌ Database connection failed${NC}"
[ ! -d "public/uploads" ] && ((ISSUES++)) && echo -e "${RED}❌ Missing uploads folder${NC}"

if [ $ISSUES -eq 0 ]; then
    echo -e "${GREEN}✅ No critical issues found!${NC}"
    echo ""
    echo "If website still shows error 500:"
    echo "1. Check PHP error log in Hostinger hPanel"
    echo "2. Clear browser cache (Ctrl+Shift+Delete)"
    echo "3. Run: bash nuclear-fix.sh"
else
    echo -e "${RED}Found $ISSUES critical issue(s)${NC}"
    echo ""
    echo "Recommended actions:"
    echo "1. Fix issues listed above"
    echo "2. Run: bash nuclear-fix.sh"
    echo "3. Check Laravel log: tail -50 storage/logs/laravel.log"
fi

echo ""
echo "=========================================="
echo "🔧 QUICK FIX COMMANDS"
echo "=========================================="
echo ""
echo "# Fix permissions:"
echo "chmod -R 775 storage bootstrap/cache"
echo ""
echo "# Reinstall dependencies:"
echo "composer install --no-dev --optimize-autoloader"
echo ""
echo "# Fix .env:"
echo "cp .env.hostinger .env && php artisan key:generate --force"
echo ""
echo "# Clear & rebuild cache:"
echo "php artisan config:clear && php artisan config:cache"
echo ""
echo "# Create uploads folder:"
echo "mkdir -p public/uploads/{products,brands,categories,banners}"
echo ""
echo "# Nuclear fix (all-in-one):"
echo "bash nuclear-fix.sh"
echo ""
