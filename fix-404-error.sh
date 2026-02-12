#!/bin/bash

echo "╔════════════════════════════════════════════════════════════╗"
echo "║           🔧 FIX LARAVEL 404 ERROR ON HOSTINGER           ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

PUBLIC_HTML="/home/u909490256/public_html"
LARAVEL_PATH="/home/u909490256/domains/jastiphype.shop"

echo -e "${BLUE}📍 Public HTML: $PUBLIC_HTML${NC}"
echo -e "${BLUE}📍 Laravel Path: $LARAVEL_PATH${NC}"
echo ""

# Step 0: Verify directories exist
echo -e "${YELLOW}🔍 Step 0: Verifying directories...${NC}"
if [ ! -d "$PUBLIC_HTML" ]; then
    echo -e "${RED}❌ ERROR: $PUBLIC_HTML does not exist!${NC}"
    echo -e "${YELLOW}Creating $PUBLIC_HTML...${NC}"
    mkdir -p "$PUBLIC_HTML"
    chmod 755 "$PUBLIC_HTML"
    echo -e "${GREEN}✅ Created $PUBLIC_HTML${NC}"
fi

if [ ! -d "$LARAVEL_PATH" ]; then
    echo -e "${RED}❌ ERROR: $LARAVEL_PATH does not exist!${NC}"
    echo -e "${RED}Please clone your Laravel project first!${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Both directories exist${NC}"
echo ""

# Step 1: Backup existing files
echo -e "${YELLOW}📦 Step 1: Backing up existing files...${NC}"
cd $PUBLIC_HTML
BACKUP_TIME=$(date +%Y%m%d_%H%M%S)
if [ -f .htaccess ]; then
    cp .htaccess .htaccess.backup.$BACKUP_TIME
    echo -e "${GREEN}✅ .htaccess backed up${NC}"
fi
if [ -f index.php ]; then
    cp index.php index.php.backup.$BACKUP_TIME
    echo -e "${GREEN}✅ index.php backed up${NC}"
fi
echo ""

# Step 2: Create new .htaccess
echo -e "${YELLOW}📝 Step 2: Creating new .htaccess...${NC}"
cat > .htaccess << 'EOF'
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
EOF

chmod 644 .htaccess
echo -e "${GREEN}✅ .htaccess created${NC}"
echo ""

# Step 3: Create/Update index.php
echo -e "${YELLOW}📝 Step 3: Creating index.php with correct paths...${NC}"
cat > index.php << 'PHPEOF'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__.'/../domains/jastiphype.shop/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__.'/../domains/jastiphype.shop/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__.'/../domains/jastiphype.shop/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
PHPEOF

chmod 644 index.php
echo -e "${GREEN}✅ index.php created${NC}"
echo ""

# Step 4: Copy assets from Laravel public to public_html
echo -e "${YELLOW}📋 Step 4: Copying assets from Laravel public folder...${NC}"
if [ -d "$LARAVEL_PATH/public" ]; then
    # Copy CSS, JS, images, etc (but not index.php and .htaccess)
    rsync -av --exclude='index.php' --exclude='.htaccess' "$LARAVEL_PATH/public/" "$PUBLIC_HTML/" 2>/dev/null || \
    cp -rf "$LARAVEL_PATH/public/"* "$PUBLIC_HTML/" 2>/dev/null || true
    
    # Restore our custom index.php and .htaccess
    cat > .htaccess << 'EOF'
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
EOF
    
    cat > index.php << 'PHPEOF'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = __DIR__.'/../domains/jastiphype.shop/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__.'/../domains/jastiphype.shop/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__.'/../domains/jastiphype.shop/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
PHPEOF
    
    echo -e "${GREEN}✅ Assets copied${NC}"
else
    echo -e "${YELLOW}⚠️  Laravel public folder not found${NC}"
fi
echo ""

# Step 5: Set permissions
echo -e "${YELLOW}🔧 Step 5: Setting permissions...${NC}"
chmod 644 .htaccess
chmod 644 index.php
chmod 755 $PUBLIC_HTML
chmod -R 755 $PUBLIC_HTML/build 2>/dev/null || true
chmod -R 755 $PUBLIC_HTML/uploads 2>/dev/null || true
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

# Step 6: Verify Laravel files
echo -e "${YELLOW}🔍 Step 6: Verifying Laravel installation...${NC}"
if [ -f "$LARAVEL_PATH/vendor/autoload.php" ]; then
    echo -e "${GREEN}✅ vendor/autoload.php exists${NC}"
else
    echo -e "${RED}❌ vendor/autoload.php NOT FOUND!${NC}"
    echo -e "${YELLOW}Run: cd $LARAVEL_PATH && composer install${NC}"
fi

if [ -f "$LARAVEL_PATH/bootstrap/app.php" ]; then
    echo -e "${GREEN}✅ bootstrap/app.php exists${NC}"
else
    echo -e "${RED}❌ bootstrap/app.php NOT FOUND!${NC}"
fi

if [ -f "$LARAVEL_PATH/.env" ]; then
    echo -e "${GREEN}✅ .env exists${NC}"
else
    echo -e "${RED}❌ .env NOT FOUND!${NC}"
    echo -e "${YELLOW}Run: cd $LARAVEL_PATH && cp .env.hostinger .env${NC}"
fi
echo ""

# Step 7: Display file contents
echo -e "${YELLOW}📋 Step 7: Verifying file contents...${NC}"
echo ""
echo -e "${BLUE}=== .htaccess (first 10 lines) ===${NC}"
head -10 .htaccess
echo ""
echo -e "${BLUE}=== index.php (first 20 lines) ===${NC}"
head -20 index.php
echo ""

# Step 8: Test website
echo -e "${YELLOW}🧪 Step 8: Testing website...${NC}"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://jastiphype.shop 2>/dev/null || echo "000")
if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✅ Website is responding (HTTP $HTTP_CODE)${NC}"
elif [ "$HTTP_CODE" = "302" ] || [ "$HTTP_CODE" = "301" ]; then
    echo -e "${GREEN}✅ Website is redirecting (HTTP $HTTP_CODE)${NC}"
elif [ "$HTTP_CODE" = "000" ]; then
    echo -e "${YELLOW}⚠️  Cannot test (curl not available or network issue)${NC}"
else
    echo -e "${RED}⚠️  Website returned HTTP $HTTP_CODE${NC}"
fi
echo ""

# Step 9: List public_html contents
echo -e "${YELLOW}📂 Step 9: Public HTML contents:${NC}"
ls -lah $PUBLIC_HTML | head -20
echo ""

echo "╔════════════════════════════════════════════════════════════╗"
echo "║                    ✅ FIX COMPLETE! ✅                     ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo -e "${GREEN}🌐 Test your website: https://jastiphype.shop${NC}"
echo ""
echo -e "${YELLOW}📝 Test these URLs:${NC}"
echo "  • Homepage: https://jastiphype.shop"
echo "  • Login: https://jastiphype.shop/login"
echo "  • Admin: https://jastiphype.shop/admin/login"
echo ""
echo -e "${YELLOW}🔧 If still showing 404:${NC}"
echo "  1. Clear browser cache (Ctrl+Shift+Delete)"
echo "  2. Try incognito/private mode"
echo "  3. Wait 1-2 minutes for server cache to clear"
echo "  4. Check Laravel logs:"
echo "     tail -50 $LARAVEL_PATH/storage/logs/laravel.log"
echo ""
echo -e "${YELLOW}📊 Check permissions:${NC}"
echo "  chmod -R 775 $LARAVEL_PATH/storage"
echo "  chmod -R 775 $LARAVEL_PATH/bootstrap/cache"
echo ""
