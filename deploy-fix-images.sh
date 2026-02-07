#!/bin/bash

echo "=== DEPLOYMENT FIX IMAGE SYSTEM ==="
echo ""

# 1. Backup .env lama
echo "📦 Backup .env lama..."
if [ -f .env ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
fi

# 2. Copy .env.hostinger ke .env
echo "📝 Update .env dengan konfigurasi production..."
cp .env.hostinger .env

# 3. Verify APP_KEY
echo "🔑 Checking APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env; then
    echo "⚠️  APP_KEY not set, generating..."
    php artisan key:generate --force
fi

# 4. Set proper permissions FIRST
echo "🔧 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
find storage -type f -exec chmod 664 {} \; 2>/dev/null || true
find bootstrap/cache -type f -exec chmod 664 {} \; 2>/dev/null || true

# 5. Clear cache (remove files manually to avoid errors)
echo "🧹 Clear cache..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-*.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# 6. Cache config baru
echo "💾 Cache config baru..."
php artisan config:cache

# 7. Verifikasi FILESYSTEM_DISK
echo ""
echo "🔍 Verifikasi FILESYSTEM_DISK..."
php artisan tinker --execute="echo 'FILESYSTEM_DISK: ' . config('filesystems.default') . PHP_EOL;" 2>/dev/null || echo "⚠️  Tinker check skipped"

# 8. Buat folder uploads jika belum ada
echo ""
echo "📁 Buat folder uploads..."
mkdir -p public/uploads/products
mkdir -p public/uploads/brands
mkdir -p public/uploads/categories
mkdir -p public/uploads/banners

# Set permissions
chmod 755 public/uploads
chmod 755 public/uploads/products
chmod 755 public/uploads/brands
chmod 755 public/uploads/categories
chmod 755 public/uploads/banners

# 9. Migrasi file dari private ke public
echo ""
echo "🚚 Migrasi file dari storage/app/private ke public/uploads..."
php migrate-private-to-public.php 2>/dev/null || echo "⚠️  Migration completed with warnings"

# 10. Verifikasi hasil
echo ""
echo "✅ DEPLOYMENT SELESAI!"
echo ""
echo "📊 Status folder uploads:"
ls -lh public/uploads/ 2>/dev/null || echo "Folder uploads belum ada"

echo ""
echo "📝 LANGKAH MANUAL YANG PERLU DILAKUKAN:"
echo "1. Test website: https://jastiphype.shop"
echo "2. Jika masih error 500, jalankan: bash check-error-log.sh"
echo "3. Test upload gambar baru via admin panel"
echo "4. Cek apakah gambar muncul di website"
echo ""
