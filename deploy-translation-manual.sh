#!/bin/bash

# Manual Translation Deployment Script
# This script will copy translated files to the server

SERVER="u909490256@153.92.9.187"
PORT="65002"
REMOTE_PATH="/home/u909490256/domains/jastiphype.shop"

echo "========================================="
echo "  Manual Translation Deployment"
echo "========================================="
echo ""

# Files to upload
FILES=(
    "resources/views/gdpr/privacy-policy.blade.php"
    "resources/views/gdpr/cookie-policy.blade.php"
    "resources/views/components/cookie-consent.blade.php"
    "resources/views/admin/categories/images.blade.php"
    "resources/views/admin/products/create.blade.php"
    "resources/views/admin/banners/create.blade.php"
    "resources/views/admin-bootstrap-backup/categories/images.blade.php"
    "resources/views/admin-bootstrap-backup/products/create.blade.php"
    "resources/views/admin-bootstrap-backup/banners/create.blade.php"
    "resources/views/admin-bootstrap-backup/banners/edit.blade.php"
)

echo "Uploading translated files..."
echo ""

for file in "${FILES[@]}"; do
    echo "Uploading: $file"
    scp -P $PORT "$file" "$SERVER:$REMOTE_PATH/$file"
    
    if [ $? -eq 0 ]; then
        echo "✓ Success"
    else
        echo "✗ Failed"
    fi
    echo ""
done

echo "========================================="
echo "  Clearing Laravel Caches"
echo "========================================="
echo ""

ssh -p $PORT $SERVER "cd $REMOTE_PATH && php artisan cache:clear && php artisan view:clear && php artisan config:clear && php artisan route:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache"

echo ""
echo "========================================="
echo "  Deployment Complete!"
echo "========================================="
echo ""
echo "Test the website:"
echo "- https://jastiphype.shop/gdpr/privacy-policy"
echo "- https://jastiphype.shop/gdpr/cookie-policy"
echo ""
