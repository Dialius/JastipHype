#!/bin/bash

# Script untuk fix error 500 di jastiphype.shop
# Jalankan di SSH Hostinger

echo "=== CHECKING ERROR LOG (FULL) ==="
tail -100 storage/logs/laravel.log | grep -A 5 "production.ERROR"

echo -e "\n=== CHECKING .ENV FILE ==="
if [ -f .env ]; then
    echo "✓ .env exists"
    echo "APP_KEY: $(grep APP_KEY .env | cut -d'=' -f2 | head -c 20)..."
    echo "DB_DATABASE: $(grep DB_DATABASE .env | cut -d'=' -f2)"
    echo "FILESYSTEM_DISK: $(grep FILESYSTEM_DISK .env | cut -d'=' -f2)"
else
    echo "✗ .env NOT FOUND!"
    echo "Copying from .env.hostinger..."
    cp .env.hostinger .env
fi

echo -e "\n=== CHECKING STORAGE PERMISSIONS ==="
ls -ld storage
ls -ld storage/logs
ls -ld storage/framework
ls -ld bootstrap/cache

echo -e "\n=== FIXING PERMISSIONS ==="
chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;
echo "✓ Permissions fixed"

echo -e "\n=== CLEARING ALL CACHE ==="
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "✓ Cache cleared"

echo -e "\n=== TESTING DATABASE CONNECTION ==="
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database: OK'; } catch (Exception \$e) { echo 'Database Error: ' . \$e->getMessage(); }"

echo -e "\n=== REBUILDING CACHE ==="
php artisan config:cache
echo "✓ Config cached"

echo -e "\n=== CHECKING PUBLIC_HTML ==="
ls -la public_html/index.php
echo "Files in public_html:"
ls -1 public_html/ | head -10

echo -e "\n=== TESTING ARTISAN ==="
php artisan --version

echo -e "\n=== DONE! ==="
echo "Now try accessing: https://jastiphype.shop"
echo "If still error, run: tail -50 storage/logs/laravel.log"
