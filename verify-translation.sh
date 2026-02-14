#!/bin/bash

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}   Translation Verification Script${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Navigate to Laravel directory
cd /home/u909490256/domains/jastiphype.shop

echo -e "${YELLOW}1. Checking current Git status...${NC}"
git log -1 --oneline
echo ""

echo -e "${YELLOW}2. Pulling latest changes...${NC}"
git pull origin master
echo ""

echo -e "${YELLOW}3. Clearing Laravel caches...${NC}"
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
echo ""

echo -e "${YELLOW}4. Optimizing for production...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo ""

echo -e "${YELLOW}5. Checking translated files...${NC}"
echo -e "${BLUE}Checking Privacy Policy:${NC}"
grep -n "Last updated" resources/views/gdpr/privacy-policy.blade.php | head -1
echo ""

echo -e "${BLUE}Checking Cookie Policy:${NC}"
grep -n "Cookie Details" resources/views/gdpr/cookie-policy.blade.php | head -1
echo ""

echo -e "${BLUE}Checking Cookie Consent:${NC}"
grep -n "Accept All" resources/views/components/cookie-consent.blade.php | head -1
echo ""

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}   Verification Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Visit: https://jastiphype.shop/privacy-policy"
echo "2. Visit: https://jastiphype.shop/cookie-policy"
echo "3. Check admin pages (login required)"
echo ""
echo -e "${BLUE}If you see English text, the translation is successful!${NC}"
