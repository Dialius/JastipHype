# 🚀 Panduan Setup Laravel di Hostinger Web/Cloud Hosting

## 📋 Masalah yang Ditemukan

Setelah investigasi mendalam, ditemukan bahwa:
- Domain `jastiphype.shop` di-point ke `/home/u909490256/domains/jastiphype.shop/public_html`
- BUKAN ke `/home/u909490256/public_html` (folder global)
- Ini adalah konfigurasi default Hostinger untuk addon domains

## ✅ Solusi

Struktur folder yang benar untuk Hostinger:

```
/home/u909490256/domains/jastiphype.shop/
├── app/
├── bootstrap/
├── config/
├── public/                       # Laravel public folder (original)
│   ├── build/                    # Vite compiled assets
│   └── ...
├── public_html/                  # Domain document root (Hostinger)
│   ├── index.php                 # Modified untuk path relatif
│   ├── .htaccess                 # Laravel rewrite rules
│   ├── build -> ../public/build  # Symlink ke Vite assets
│   └── storage -> ../storage/app/public  # Symlink ke storage
├── storage/
└── vendor/
```

**Key Points:**
- Domain point ke `public_html/` di dalam project folder
- `index.php` menggunakan path relatif (`../vendor/autoload.php`)
- Assets di-symlink untuk menghindari duplikasi
- Tidak perlu `.htaccess` redirect karena sudah di root yang benar

## 🔧 Setup Manual

### Step 1: Jalankan Setup Script

```bash
# SSH ke server
ssh -p 65002 u909490256@153.92.9.187

# Upload dan jalankan script
bash fix-domain-root.sh
```

Script akan:
1. ✅ Backup `public_html` yang ada (di dalam project folder)
2. ✅ Clear `public_html`
3. ✅ Copy Laravel `public/` ke `public_html/`
4. ✅ Update `index.php` dengan path relatif yang benar
5. ✅ Set permissions
6. ✅ Buat symbolic links untuk `build/` dan `storage/`
7. ✅ Clear Laravel caches
8. ✅ Test setup

### Step 2: Verifikasi

```bash
# Test HTTP response
curl -I https://jastiphype.shop

# Should return:
# HTTP/2 200
```

## 🔄 Auto Deployment

GitHub Actions workflow sudah diupdate untuk:

1. Build Vite assets
2. Commit ke repository
3. Deploy ke server
4. **Sync `public/` folder ke `public_html/`** (di dalam project folder)
5. Update `index.php` dengan path relatif
6. Recreate symbolic links untuk `build/` dan `storage/`
7. Set permissions
8. Clear caches
9. Health check

### Workflow File

File: `.github/workflows/deploy-hostinger.yml`

Setiap push ke branch `master` akan:
- ✅ Build assets dengan Vite
- ✅ Deploy ke Hostinger
- ✅ Sync public folder
- ✅ Update configuration
- ✅ Test website

## 📝 File Penting

### 1. `/home/u909490256/domains/jastiphype.shop/public_html/index.php`

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

**Fungsi**: Entry point Laravel dengan path relatif (`../`) untuk akses ke folder parent

### 2. `/home/u909490256/domains/jastiphype.shop/public_html/.htaccess`

```apache
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
```

**Fungsi**: Laravel rewrite rules untuk routing (standard Laravel .htaccess)

## 🔍 Troubleshooting

### Problem: 403 Forbidden

**Penyebab**: Permissions salah atau `.htaccess` tidak bekerja

**Solusi**:
```bash
# Set permissions
cd /home/u909490256/domains/jastiphype.shop
chmod -R 755 public_html
chmod 644 public_html/index.php
chmod 644 public_html/.htaccess
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Clear caches
php artisan optimize:clear
```

### Problem: 404 Not Found

**Penyebab**: `.htaccess` tidak aktif atau mod_rewrite disabled

**Solusi**:
```bash
# Check if .htaccess exists
ls -la /home/u909490256/domains/jastiphype.shop/public_html/.htaccess

# Recreate if missing
bash fix-domain-root.sh
```

### Problem: Assets tidak muncul (CSS/JS)

**Penyebab**: Build folder tidak tersync atau symbolic link rusak

**Solusi**:
```bash
# Rebuild assets
cd /home/u909490256/domains/jastiphype.shop
npm run build

# Recreate symlink
cd public_html
rm -rf build
ln -s ../public/build build
```

### Problem: Storage files tidak bisa diakses

**Penyebab**: Storage link tidak ada

**Solusi**:
```bash
cd /home/u909490256/domains/jastiphype.shop
php artisan storage:link

# Atau manual
cd public_html
rm -rf storage
ln -s ../storage/app/public storage
```

## 🧪 Testing

### Test 1: Homepage
```bash
curl -I https://jastiphype.shop
# Expected: HTTP/2 200
```

### Test 2: GDPR Dashboard
```bash
curl -I https://jastiphype.shop/gdpr/dashboard
# Expected: HTTP/2 200 (or 302 if requires auth)
```

### Test 3: Assets
```bash
curl -I https://jastiphype.shop/build/manifest.json
# Expected: HTTP/2 200
```

### Test 4: Storage
```bash
# Upload a test file first, then:
curl -I https://jastiphype.shop/storage/test.jpg
# Expected: HTTP/2 200
```

## 📊 Monitoring

### Check Logs

```bash
# Laravel logs
tail -f /home/u909490256/domains/jastiphype.shop/storage/logs/laravel.log

# Apache/LiteSpeed logs (if accessible)
tail -f /home/u909490256/logs/error.log
```

### Check Disk Space

```bash
df -h /home/u909490256
```

### Check Permissions

```bash
ls -la /home/u909490256/public_html/
ls -la /home/u909490256/public_html/public/
```

## 🎯 Checklist Deployment

Sebelum deploy:
- [ ] Code sudah di-commit ke GitHub
- [ ] Assets sudah di-build (`npm run build`)
- [ ] Database migrations sudah siap
- [ ] Environment variables sudah di-set di `.env`

Setelah deploy:
- [ ] Website bisa diakses (HTTP 200)
- [ ] Assets muncul (CSS/JS)
- [ ] Database connection OK
- [ ] Storage files bisa diakses
- [ ] GDPR dashboard berfungsi

## 🔐 Security Checklist

- [ ] `.env` tidak bisa diakses dari web
- [ ] `storage/` tidak bisa diakses langsung
- [ ] `vendor/` tidak bisa diakses langsung
- [ ] Debug mode OFF di production (`APP_DEBUG=false`)
- [ ] HTTPS aktif
- [ ] File permissions benar (755/644)

## 📞 Support

Jika masih ada masalah:

1. **Check logs**: `storage/logs/laravel.log`
2. **Run setup script**: `bash setup-hostinger-laravel.sh`
3. **Contact Hostinger Support** dengan info:
   - Account: u909490256
   - Domain: jastiphype.shop
   - Issue: Laravel setup dengan public_html

## 🎉 Success Indicators

Website berhasil jika:
- ✅ `https://jastiphype.shop` returns HTTP 200
- ✅ Homepage muncul dengan benar
- ✅ CSS/JS assets loaded
- ✅ Images muncul
- ✅ GDPR dashboard accessible
- ✅ No 403/404/500 errors

---

**Last Updated**: 2026-02-14
**Status**: ✅ Ready for deployment
