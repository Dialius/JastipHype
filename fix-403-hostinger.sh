#!/bin/bash

# ============================================================================
# 403 Forbidden Error Troubleshooting Script for Hostinger
# Based on official Hostinger support documentation
# ============================================================================

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Base paths
LARAVEL_PATH="/home/u909490256/domains/jastiphype.shop"
PUBLIC_HTML="/home/u909490256/public_html"

echo -e "${BLUE}============================================${NC}"
echo -e "${BLUE}  403 Forbidden Error Troubleshooting${NC}"
echo -e "${BLUE}============================================${NC}"
echo ""

# Function to check status
check_status() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ $1${NC}"
    else
        echo -e "${RED}✗ $1${NC}"
    fi
}

# ============================================================================
# Step 1: Check current file permissions
# ============================================================================
echo -e "${YELLOW}Step 1: Checking file permissions...${NC}"
echo "Public HTML permissions:"
ls -ld $PUBLIC_HTML
echo ""
echo "Index.php permissions:"
ls -l $PUBLIC_HTML/index.php
echo ""
echo ".htaccess permissions:"
ls -l $PUBLIC_HTML/.htaccess
echo ""

# ============================================================================
# Step 2: Fix file permissions (Method from Hostinger docs)
# ============================================================================
echo -e "${YELLOW}Step 2: Fixing file permissions...${NC}"

# Set directory permissions to 755
echo "Setting directory permissions to 755..."
find $PUBLIC_HTML -type d -exec chmod 755 {} \;
check_status "Directory permissions set"

# Set file permissions to 644
echo "Setting file permissions to 644..."
find $PUBLIC_HTML -type f -exec chmod 644 {} \;
check_status "File permissions set"

# Special permissions for Laravel
chmod 775 $LARAVEL_PATH/storage -R
chmod 775 $LARAVEL_PATH/bootstrap/cache -R
check_status "Laravel storage permissions set"

echo ""

# ============================================================================
# Step 3: Check and fix file ownership
# ============================================================================
echo -e "${YELLOW}Step 3: Checking file ownership...${NC}"
echo "Current ownership:"
ls -l $PUBLIC_HTML/index.php | awk '{print "Owner: "$3", Group: "$4}'
echo ""

# Fix ownership (should be u909490256:o1008729359 or u909490256:apache)
echo "Fixing file ownership..."
chown -R u909490256:o1008729359 $PUBLIC_HTML
check_status "File ownership fixed"
echo ""

# ============================================================================
# Step 4: Verify index page exists
# ============================================================================
echo -e "${YELLOW}Step 4: Verifying index page...${NC}"
if [ -f "$PUBLIC_HTML/index.php" ]; then
    echo -e "${GREEN}✓ index.php exists${NC}"
    echo "File size: $(stat -f%z "$PUBLIC_HTML/index.php" 2>/dev/null || stat -c%s "$PUBLIC_HTML/index.php" 2>/dev/null) bytes"
else
    echo -e "${RED}✗ index.php is missing!${NC}"
    echo "Creating index.php..."
    cat > $PUBLIC_HTML/index.php << 'ENDOFFILE'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../domains/jastiphype.shop/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../domains/jastiphype.shop/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../domains/jastiphype.shop/bootstrap/app.php';

$app->handleRequest(Request::capture());
ENDOFFILE
    check_status "index.php created"
fi
echo ""

# ============================================================================
# Step 5: Check and restore .htaccess file
# ============================================================================
echo -e "${YELLOW}Step 5: Checking .htaccess file...${NC}"
if [ -f "$PUBLIC_HTML/.htaccess" ]; then
    echo -e "${GREEN}✓ .htaccess exists${NC}"
    echo "Backing up current .htaccess..."
    cp $PUBLIC_HTML/.htaccess $PUBLIC_HTML/.htaccess.backup.$(date +%Y%m%d_%H%M%S)
    check_status ".htaccess backed up"
else
    echo -e "${RED}✗ .htaccess is missing!${NC}"
fi

echo "Creating fresh .htaccess file..."
cat > $PUBLIC_HTML/.htaccess << 'ENDOFFILE'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
ENDOFFILE
check_status ".htaccess created"
chmod 644 $PUBLIC_HTML/.htaccess
echo ""

# ============================================================================
# Step 6: Clear Laravel caches
# ============================================================================
echo -e "${YELLOW}Step 6: Clearing Laravel caches...${NC}"
cd $LARAVEL_PATH
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
check_status "Laravel caches cleared"
echo ""

# ============================================================================
# Step 7: Check for empty directories
# ============================================================================
echo -e "${YELLOW}Step 7: Checking for empty directories...${NC}"
if [ -z "$(ls -A $PUBLIC_HTML)" ]; then
    echo -e "${RED}✗ public_html is empty!${NC}"
else
    echo -e "${GREEN}✓ public_html contains files${NC}"
    echo "File count: $(ls -1 $PUBLIC_HTML | wc -l)"
fi
echo ""

# ============================================================================
# Step 8: Test PHP execution
# ============================================================================
echo -e "${YELLOW}Step 8: Testing PHP execution...${NC}"
echo "<?php echo 'PHP is working!'; ?>" > $PUBLIC_HTML/test-php-exec.php
chmod 644 $PUBLIC_HTML/test-php-exec.php

if php $PUBLIC_HTML/test-php-exec.php > /dev/null 2>&1; then
    echo -e "${GREEN}✓ PHP execution works${NC}"
else
    echo -e "${RED}✗ PHP execution failed${NC}"
fi
echo ""

# ============================================================================
# Step 9: Check .htaccess syntax
# ============================================================================
echo -e "${YELLOW}Step 9: Checking .htaccess syntax...${NC}"
if apachectl -t 2>&1 | grep -q "Syntax OK"; then
    echo -e "${GREEN}✓ Apache configuration is valid${NC}"
else
    echo -e "${YELLOW}⚠ Cannot verify Apache config (may need root access)${NC}"
fi
echo ""

# ============================================================================
# Step 10: Test website access
# ============================================================================
echo -e "${YELLOW}Step 10: Testing website access...${NC}"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://jastiphype.shop)
echo "HTTP Status Code: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✓ Website is accessible!${NC}"
elif [ "$HTTP_CODE" = "403" ]; then
    echo -e "${RED}✗ Still getting 403 Forbidden${NC}"
    echo ""
    echo -e "${YELLOW}Additional troubleshooting needed:${NC}"
    echo "1. Check if ModSecurity is blocking requests (contact Hostinger support)"
    echo "2. Verify domain DNS A record points to correct IP"
    echo "3. Check for IP blocking in hPanel security settings"
    echo "4. Review error logs in hPanel"
else
    echo -e "${YELLOW}⚠ Unexpected status code: $HTTP_CODE${NC}"
fi
echo ""

# ============================================================================
# Step 11: Check Laravel logs for errors
# ============================================================================
echo -e "${YELLOW}Step 11: Checking Laravel error logs...${NC}"
if [ -f "$LARAVEL_PATH/storage/logs/laravel.log" ]; then
    echo "Last 10 errors:"
    grep -i "error\|exception\|fatal" $LARAVEL_PATH/storage/logs/laravel.log | tail -10
else
    echo -e "${YELLOW}⚠ No Laravel log file found${NC}"
fi
echo ""

# ============================================================================
# Summary
# ============================================================================
echo -e "${BLUE}============================================${NC}"
echo -e "${BLUE}  Troubleshooting Summary${NC}"
echo -e "${BLUE}============================================${NC}"
echo ""
echo "Actions taken:"
echo "✓ Fixed file permissions (755 for directories, 644 for files)"
echo "✓ Fixed file ownership"
echo "✓ Verified/created index.php"
echo "✓ Restored .htaccess file"
echo "✓ Cleared Laravel caches"
echo "✓ Tested PHP execution"
echo ""
echo "Current status: HTTP $HTTP_CODE"
echo ""

if [ "$HTTP_CODE" != "200" ]; then
    echo -e "${YELLOW}If the issue persists, try these additional steps:${NC}"
    echo ""
    echo "1. Contact Hostinger Support:"
    echo "   - Ask them to check ModSecurity logs"
    echo "   - Request to whitelist your domain"
    echo "   - Verify server configuration"
    echo ""
    echo "2. Check hPanel Settings:"
    echo "   - Go to Security → Check for blocked IPs"
    echo "   - Review Firewall settings"
    echo "   - Check Error Logs for detailed errors"
    echo ""
    echo "3. Verify DNS Settings:"
    echo "   - Ensure A record points to correct IP"
    echo "   - Check nameservers are correct"
fi

echo ""
echo -e "${GREEN}Script completed!${NC}"
