# Storage Upload Fix Guide

## Masalah
Error saat upload file (banner, product, brand, category images):
```
Failed to update category images: Unable to create a directory at /var/task/user/storage/app/public.
```

## Penyebab
Di environment serverless seperti Vercel, filesystem bersifat read-only kecuali di directory `/tmp`. Laravel mencoba membuat directory di `storage/app/public` yang tidak memiliki write permission.

## Solusi yang Diterapkan

### 1. FileUploadService
Dibuat service baru `app/Services/FileUploadService.php` yang menangani:
- Upload file dengan error handling yang lebih baik
- Automatic directory creation dengan fallback
- Proper file deletion
- Support untuk serverless environments

### 2. Update Filesystem Config
File `config/filesystems.php` diupdate untuk menggunakan `/tmp/storage` di environment Vercel:
```php
'public' => [
    'driver' => 'local',
    'root' => env('VERCEL_ENV') ? '/tmp/storage' : storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

### 3. Update Semua Services
Semua service yang menggunakan file upload diupdate untuk menggunakan `FileUploadService`:
- `BannerService`
- `BrandService`
- `ProductService`

### 4. Update Semua Controllers
Semua controller yang menangani upload diupdate:
- `BannerController`
- `BrandController`
- `ProductController`
- `CategoryImageController`

### 5. Middleware EnsureStorageDirectories
Dibuat middleware `app/Http/Middleware/EnsureStorageDirectories.php` yang:
- Memastikan semua directory storage ada sebelum request diproses
- Mencoba membuat directory jika belum ada
- Log error tapi tidak throw exception

### 6. Storage Setup Script
Dibuat script `scripts/ensure-storage.php` yang:
- Membuat semua directory yang diperlukan
- Membuat .gitkeep files
- Membuat storage symlink untuk local development
- Dijalankan saat build di Vercel

## Testing

### Local Development
```bash
# Ensure storage directories exist
php scripts/ensure-storage.php

# Create storage link
php artisan storage:link

# Test upload
# Go to admin panel and try uploading banner/product/brand images
```

### Production (Vercel)
1. Deploy ke Vercel
2. Check build logs untuk memastikan script berjalan
3. Test upload di admin panel

## Catatan Penting

### Untuk Production dengan Traffic Tinggi
Untuk production dengan traffic tinggi, disarankan menggunakan cloud storage seperti:
- AWS S3
- Google Cloud Storage
- Cloudinary
- DigitalOcean Spaces

### Setup S3 (Opsional)
1. Install AWS SDK:
```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
```

2. Update `.env`:
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
AWS_URL=https://your-bucket.s3.amazonaws.com
```

3. Update `config/filesystems.php` default disk:
```php
'default' => env('FILESYSTEM_DISK', 's3'),
```

## Files yang Dimodifikasi

1. `config/filesystems.php` - Update public disk path
2. `app/Services/FileUploadService.php` - NEW: Service untuk handle upload
3. `app/Services/BannerService.php` - Update untuk gunakan FileUploadService
4. `app/Services/BrandService.php` - Update untuk gunakan FileUploadService
5. `app/Services/ProductService.php` - Update untuk gunakan FileUploadService
6. `app/Http/Controllers/Admin/BannerController.php` - Update untuk gunakan FileUploadService
7. `app/Http/Controllers/Admin/BrandController.php` - Update untuk gunakan FileUploadService
8. `app/Http/Controllers/Admin/ProductController.php` - Update untuk gunakan FileUploadService
9. `app/Http/Controllers/Admin/CategoryImageController.php` - Update untuk gunakan FileUploadService
10. `app/Http/Middleware/EnsureStorageDirectories.php` - NEW: Middleware untuk ensure directories
11. `bootstrap/app.php` - Register middleware
12. `scripts/ensure-storage.php` - NEW: Script untuk setup storage
13. `vercel.json` - Add build command

## Troubleshooting

### Error: Directory not writable
- Check permissions: `chmod -R 755 storage`
- Run: `php scripts/ensure-storage.php`

### Error: Symlink not created
- Windows: Run as Administrator
- Linux/Mac: Check permissions

### Images not showing after upload
- Check storage link: `ls -la public/storage`
- Recreate link: `php artisan storage:link`
- Check file permissions

### Vercel deployment fails
- Check build logs
- Ensure all dependencies installed
- Check environment variables

## Rekomendasi

1. **Local Development**: Gunakan local storage (sudah disetup)
2. **Staging/Testing**: Gunakan local storage atau S3
3. **Production**: Gunakan S3 atau cloud storage lainnya untuk reliability dan scalability

## Support

Jika masih ada masalah:
1. Check logs: `storage/logs/laravel.log`
2. Check Vercel logs di dashboard
3. Enable debug mode: `APP_DEBUG=true`
4. Test upload dengan file kecil dulu (< 1MB)
