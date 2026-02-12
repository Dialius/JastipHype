#!/bin/bash

echo "╔════════════════════════════════════════════════════════════╗"
echo "║     🚀 JASTIPHYPE FRESH DEPLOYMENT TO HOSTINGER 🚀        ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Base path
BASE_PATH="/home/u909490256/domains/jastiphype.shop"

echo -e "${BLUE}📍 Working directory: $BASE_PATH${NC}"
echo ""

# Step 1: Install Dependencies
echo -e "${YELLOW}📦 Step 1: Installing Composer dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Dependencies installed${NC}"
else
    echo -e "${RED}❌ Failed to install dependencies${NC}"
    exit 1
fi
echo ""

# Step 2: Setup Environment
echo -e "${YELLOW}🔐 Step 2: Setting up environment...${NC}"
if [ ! -f .env ]; then
    cp .env.hostinger .env
    echo -e "${GREEN}✅ .env file created${NC}"
else
    echo -e "${BLUE}ℹ️  .env already exists${NC}"
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
    echo -e "${GREEN}✅ APP_KEY generated${NC}"
else
    echo -e "${BLUE}ℹ️  APP_KEY already set${NC}"
fi
echo ""

# Step 3: Create Directories
echo -e "${YELLOW}📁 Step 3: Creating directories...${NC}"
mkdir -p public/uploads/{products,brands,categories,banners}
mkdir -p storage/app/public/{products,brands,categories,banners}
mkdir -p storage/framework/{cache/data,sessions,views}
mkdir -p storage/logs
echo -e "${GREEN}✅ Directories created${NC}"
echo ""

# Step 4: Set Permissions
echo -e "${YELLOW}🔧 Step 4: Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chmod -R 755 public/uploads
find storage -type f -exec chmod 664 {} \; 2>/dev/null || true
find bootstrap/cache -type f -exec chmod 664 {} \; 2>/dev/null || true
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

# Step 5: Database Migration
echo -e "${YELLOW}🗄️  Step 5: Running database migrations...${NC}"
php artisan migrate --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Migrations completed${NC}"
else
    echo -e "${RED}❌ Migration failed${NC}"
    echo -e "${YELLOW}⚠️  Continuing anyway...${NC}"
fi
echo ""

# Step 6: Clear Cache
echo -e "${YELLOW}🧹 Step 6: Clearing cache...${NC}"
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
echo -e "${GREEN}✅ Cache cleared${NC}"
echo ""

# Step 7: Build Cache
echo -e "${YELLOW}💾 Step 7: Building cache...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✅ Cache built${NC}"
echo ""

# Step 8: Copy to public_html
echo -e "${YELLOW}🌐 Step 8: Copying files to public_html...${NC}"
cp -rf public/* public_html/
chmod -R 755 public_html
echo -e "${GREEN}✅ Files copied to public_html${NC}"
echo ""

# Step 9: Verification
echo -e "${YELLOW}✅ Step 9: Verifying installation...${NC}"
echo ""

echo -e "${BLUE}🔍 Checking APP_KEY...${NC}"
php artisan tinker --execute="echo config('app.key') ? '✅ APP_KEY is set' : '❌ APP_KEY not set';" 2>/dev/null || echo "⚠️  Cannot verify APP_KEY"

echo -e "${BLUE}🔍 Checking Database...${NC}"
php artisan tinker --execute="try { DB::connection()->getPdo(); echo '✅ Database connected'; } catch (Exception \$e) { echo '❌ Database error: ' . \$e->getMessage(); }" 2>/dev/null || echo "⚠️  Cannot verify database"

echo -e "${BLUE}🔍 Checking FILESYSTEM_DISK...${NC}"
php artisan tinker --execute="echo 'FILESYSTEM_DISK: ' . config('filesystems.default');" 2>/dev/null || echo "⚠️  Cannot verify FILESYSTEM_DISK"

echo ""
echo -e "${BLUE}📊 Folder Status:${NC}"
echo "  public/uploads/: $(ls -1 public/uploads/ 2>/dev/null | wc -l) items"
echo "  public_html/uploads/: $(ls -1 public_html/uploads/ 2>/dev/null | wc -l) items"
echo "  storage/logs/: $(ls -1 storage/logs/ 2>/dev/null | wc -l) files"

echo ""
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                  ✅ DEPLOYMENT COMPLETE! ✅                ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo -e "${GREEN}🎉 Website should be live at: https://jastiphype.shop${NC}"
echo ""
echo -e "${YELLOW}📝 Next steps:${NC}"
echo "  1. Test website: https://jastiphype.shop"
echo "  2. Test admin login: https://jastiphype.shop/admin/login"
echo "  3. Upload test image via admin panel"
echo "  4. Verify image appears on website"
echo ""
echo -e "${BLUE}💡 If you encounter issues, run:${NC}"
echo "  bash nuclear-fix.sh"
echo ""
