#!/bin/bash

echo "=== DEPLOYMENT FIX IMAGE SYSTEM ==="
echo ""

# 1. Backup .env lama
echo "📦 Backup .env lama..."
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# 2. Copy .env.hostinger ke .env
echo "📝 Update .env dengan konfigurasi production..."
cp .env.hostinger .env

# 3. Clear cache
echo "🧹 Clear cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Cache config baru
echo "💾 Cache config baru..."
php artisan config:cache

# 5. Verifikasi FILESYSTEM_DISK
echo ""
echo "🔍 Verifikasi FILESYSTEM_DISK..."
php artisan tinker --execute="echo 'FILESYSTEM_DISK: ' . config('filesystems.default') . PHP_EOL;"

# 6. Buat folder uploads jika belum ada
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

# 7. Migrasi file dari private ke public
echo ""
echo "🚚 Migrasi file dari storage/app/private ke public/uploads..."
php migrate-private-to-public.php

# 8. Verifikasi hasil
echo ""
echo "✅ DEPLOYMENT SELESAI!"
echo ""
echo "📊 Status folder uploads:"
ls -lh public/uploads/

echo ""
echo "📝 LANGKAH MANUAL YANG PERLU DILAKUKAN:"
echo "1. Test upload gambar baru via admin panel"
echo "2. Cek apakah gambar muncul di website"
echo "3. Cek apakah file masuk ke public/uploads/ (bukan storage/app/private/)"
echo ""
