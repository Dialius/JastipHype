#!/bin/bash

# Script untuk cek gambar di production via SSH
# Run: bash check-production-images.sh

echo "=========================================="
echo "CHECKING PRODUCTION IMAGES - JastipHype"
echo "=========================================="
echo ""

# SSH credentials
SSH_USER="u909490256"
SSH_HOST="srv1001.hstgr.io"
SSH_PORT="65002"
BASE_PATH="/home/u909490256/domains/jastiphype.shop"

echo "Connecting to: $SSH_USER@$SSH_HOST:$SSH_PORT"
echo ""

# Check 1: Folder structure
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "CHECK 1: FOLDER STRUCTURE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
ssh -p $SSH_PORT $SSH_USER@$SSH_HOST << 'ENDSSH'
cd /home/u909490256/domains/jastiphype.shop

echo ""
echo "Checking public_html/uploads folder..."
if [ -d "public_html/uploads" ]; then
    echo "✅ public_html/uploads EXISTS"
    ls -la public_html/uploads/
else
    echo "❌ public_html/uploads NOT FOUND!"
fi

echo ""
echo "Checking subdirectories..."
for folder in products categories brands banners; do
    if [ -d "public_html/uploads/$folder" ]; then
        count=$(find public_html/uploads/$folder -type f | wc -l)
        echo "✅ $folder: $count files"
        ls -lh public_html/uploads/$folder/ | head -5
    else
        echo "❌ $folder: NOT FOUND"
    fi
    echo ""
done

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "CHECK 2: STORAGE FOLDER (OLD LOCATION)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
if [ -d "storage/app/public" ]; then
    echo "✅ storage/app/public EXISTS"
    for folder in products categories brands banners; do
        if [ -d "storage/app/public/$folder" ]; then
            count=$(find storage/app/public/$folder -type f | wc -l)
            echo "   $folder: $count files"
        fi
    done
else
    echo "❌ storage/app/public NOT FOUND"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "CHECK 3: CONFIGURATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "Checking config/filesystems.php..."
grep -A 5 "'public' =>" config/filesystems.php | head -10

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "CHECK 4: MIGRATION SCRIPT"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
if [ -f "migrate-images-to-public.php" ]; then
    echo "✅ migrate-images-to-public.php EXISTS"
else
    echo "❌ migrate-images-to-public.php NOT FOUND"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "CHECK 5: PERMISSIONS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
if [ -d "public_html/uploads" ]; then
    ls -ld public_html/uploads
    ls -ld public_html/uploads/products 2>/dev/null || echo "products folder not found"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "CHECK 6: SAMPLE DATABASE QUERY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "Running: php artisan tinker --execute=\"DB::table('product_images')->limit(3)->get(['id','image_path'])\""
php artisan tinker --execute="DB::table('product_images')->limit(3)->get(['id','image_path'])" 2>/dev/null || echo "Could not query database"

echo ""
echo "=========================================="
echo "CHECKS COMPLETED"
echo "=========================================="

ENDSSH

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "NEXT STEPS:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "If files are in storage/app/public but NOT in public_html/uploads:"
echo "  → Run: ssh -p $SSH_PORT $SSH_USER@$SSH_HOST"
echo "  → Then: cd $BASE_PATH"
echo "  → Then: php migrate-images-to-public.php"
echo ""
echo "If permissions are wrong:"
echo "  → Run: chmod -R 755 public_html/uploads/"
echo ""
echo "If database paths are wrong:"
echo "  → Check database manually"
echo "  → Paths should be relative: 'products/image.jpg'"
echo ""
