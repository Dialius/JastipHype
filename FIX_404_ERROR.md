# 🔧 FIX ERROR 404 - LARAVEL ROUTING NOT WORKING

## 🔍 MASALAH

Website menampilkan error 404 karena:
- Laravel di-deploy di `/home/u909490256/domains/jastiphype.shop/`
- Document root web server adalah `/home/u909490256/public_html/`
- File `index.php` di `public_html` tidak mengarah ke Laravel dengan benar
- Laravel routing tidak berfungsi karena tidak ada `.htaccess` yang benar

## 📚 PENJELASAN STRUKTUR HOSTINGER

Hostinger menggunakan struktur folder khusus:
```
/home/u909490256/
├── domains/
│   └── jastiphype.shop/          ← Laravel project ada di sini
│       ├── app/
│       ├── bootstrap/
│       ├── config/
│       ├── public/               ← Laravel public folder (BUKAN document root)
│       ├── vendor/
│       └── .env
└── public_html/                  ← Document root web server (harus ada index.php)
    ├── index.php                 ← Harus mengarah ke ../domains/jastiphype.shop/
    ├── .htaccess                 ← Laravel routing rules
    ├── build/                    ← Assets dari Laravel
    └── uploads/                  ← Upload files
```

Masalahnya: Web server membaca dari `public_html/`, tapi Laravel ada di `domains/jastiphype.shop/`. Kita perlu "jembatan" antara keduanya.

## ✅ SOLUSI

Ada 2 file penting yang harus benar di `/home/u909490256/public_html/`:
1. `.htaccess` - Untuk routing Laravel
2. `index.php` - Entry point yang mengarah ke Laravel project

## ✅ SOLUSI

Ada 2 file penting yang harus benar di `/home/u909490256/public_html/`:
1. `.htaccess` - Untuk routing Laravel
2. `index.php` - Entry point yang mengarah ke Laravel project

### 🚀 CARA CEPAT: Jalankan Script Otomatis

```bash
# Login ke SSH Hostinger
ssh u909490256@jastiphype.shop -p 65002

# Masuk ke folder Laravel
cd /home/u909490256/domains/jastiphype.shop

# Jalankan script fix
bash fix-404-error.sh
```

Script ini akan otomatis:
- ✅ Membuat/update `.htaccess` di public_html
- ✅ Membuat/update `index.php` dengan path yang benar
- ✅ Copy assets dari Laravel public ke public_html
- ✅ Set permissions yang benar
- ✅ Test website

---

### 📝 CARA MANUAL (Jika Script Gagal)

### LANGKAH 1: Buat .htaccess di public_html

```bash
cd /home/u909490256/public_html

# Backup .htaccess lama jika ada
cp .htaccess .htaccess.backup 2>/dev/null || true

# Buat .htaccess baru
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

# Set permissions
chmod 644 .htaccess

# Verify
cat .htaccess
```

**Penjelasan .htaccess:**
- `RewriteEngine On` - Aktifkan URL rewriting
- `RewriteCond %{REQUEST_FILENAME} !-f` - Jika bukan file yang ada
- `RewriteCond %{REQUEST_FILENAME} !-d` - Jika bukan folder yang ada
- `RewriteRule ^ index.php [L]` - Redirect ke index.php (Laravel router)

### LANGKAH 2: Buat/Update index.php di public_html

```bash
cd /home/u909490256/public_html

# Backup index.php lama jika ada
cp index.php index.php.backup 2>/dev/null || true

# Buat index.php baru
cat > index.php << 'EOF'
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
EOF

chmod 644 index.php
```

**Penjelasan index.php:**
- `__DIR__.'/../domains/jastiphype.shop/vendor/autoload.php'` - Path ke Laravel autoloader
- `__DIR__.'/../domains/jastiphype.shop/bootstrap/app.php'` - Path ke Laravel bootstrap
- Path `../domains/jastiphype.shop/` karena:
  - `__DIR__` = `/home/u909490256/public_html`
  - `../` = naik 1 level ke `/home/u909490256/`
  - `domains/jastiphype.shop/` = masuk ke folder Laravel

### LANGKAH 3: Copy Assets dari Laravel Public

```bash
# Copy semua file dari Laravel public ke public_html
cd /home/u909490256/domains/jastiphype.shop
cp -rf public/* /home/u909490256/public_html/

# PENTING: Restore index.php dan .htaccess yang sudah kita buat
# (karena di-overwrite oleh copy di atas)
cd /home/u909490256/public_html

# Buat ulang .htaccess
cat > .htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF

# Buat ulang index.php
cat > index.php << 'EOF'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../domains/jastiphype.shop/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../domains/jastiphype.shop/vendor/autoload.php';

$app = require_once __DIR__.'/../domains/jastiphype.shop/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
EOF

# Set permissions
chmod 644 .htaccess
chmod 644 index.php
chmod -R 755 /home/u909490256/public_html
```

### LANGKAH 4: Verify Laravel Files

```bash
# Cek apakah file Laravel penting ada
ls -la /home/u909490256/domains/jastiphype.shop/vendor/autoload.php
ls -la /home/u909490256/domains/jastiphype.shop/bootstrap/app.php
ls -la /home/u909490256/domains/jastiphype.shop/.env

# Jika vendor tidak ada, install composer (tapi di Hostinger biasanya sudah ada di repo)
# cd /home/u909490256/domains/jastiphype.shop
# composer install --no-dev --optimize-autoloader

# Jika .env tidak ada, copy dari .env.hostinger
cd /home/u909490256/domains/jastiphype.shop
cp .env.hostinger .env
php artisan key:generate --force
```

### LANGKAH 5: Set Permissions

```bash
cd /home/u909490256/domains/jastiphype.shop

# Storage dan cache harus writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;

# Public_html harus readable
chmod -R 755 /home/u909490256/public_html
```

### LANGKAH 6: Clear Cache Laravel

```bash
cd /home/u909490256/domains/jastiphype.shop

# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### LANGKAH 6: Clear Cache Laravel

```bash
cd /home/u909490256/domains/jastiphype.shop

# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### LANGKAH 7: Test Website

```bash
# Test dari SSH
curl -I https://jastiphype.shop

# Atau buka di browser
# https://jastiphype.shop
```

---

## 🧪 VERIFIKASI

Setelah fix, test URL berikut:
1. ✅ Homepage: https://jastiphype.shop
2. ✅ Login: https://jastiphype.shop/login
3. ✅ Admin: https://jastiphype.shop/admin/login
4. ✅ Register: https://jastiphype.shop/register

Semua harus berfungsi tanpa error 404!

## 🔍 TROUBLESHOOTING

### Masalah 1: Masih 404 setelah fix

**Solusi:**
```bash
# 1. Clear browser cache
# Tekan Ctrl+Shift+Delete di browser

# 2. Cek file index.php
cat /home/u909490256/public_html/index.php | head -30

# 3. Cek .htaccess
cat /home/u909490256/public_html/.htaccess

# 4. Cek Laravel logs
tail -50 /home/u909490256/domains/jastiphype.shop/storage/logs/laravel.log

# 5. Test dengan curl
curl -v https://jastiphype.shop
```

### Masalah 2: Error "Class not found" atau "Autoload error"

**Penyebab:** Path ke `vendor/autoload.php` salah

**Solusi:**
```bash
# Verify vendor exists
ls -la /home/u909490256/domains/jastiphype.shop/vendor/autoload.php

# Jika tidak ada, install composer
cd /home/u909490256/domains/jastiphype.shop
composer install --no-dev --optimize-autoloader

# Update index.php dengan path yang benar
cd /home/u909490256/public_html
nano index.php
# Pastikan path: __DIR__.'/../domains/jastiphype.shop/vendor/autoload.php'
```

### Masalah 3: Error 500 Internal Server Error

**Penyebab:** Permissions salah atau .env tidak ada

**Solusi:**
```bash
# Fix permissions
cd /home/u909490256/domains/jastiphype.shop
chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 664 {} \;

# Check .env
ls -la .env

# Jika tidak ada
cp .env.hostinger .env
php artisan key:generate --force

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### Masalah 4: CSS/JS tidak load

**Penyebab:** Assets tidak di-copy ke public_html

**Solusi:**
```bash
# Copy assets dari Laravel public ke public_html
cd /home/u909490256/domains/jastiphype.shop
cp -rf public/build /home/u909490256/public_html/
cp -rf public/css /home/u909490256/public_html/
cp -rf public/js /home/u909490256/public_html/
cp -rf public/images /home/u909490256/public_html/

# Set permissions
chmod -R 755 /home/u909490256/public_html/build
```

### Masalah 5: Images tidak muncul

**Penyebab:** Upload folder tidak di-copy atau permissions salah

**Solusi:**
```bash
# Copy uploads folder
mkdir -p /home/u909490256/public_html/uploads/{products,brands,categories,banners}
cp -rf /home/u909490256/domains/jastiphype.shop/public/uploads/* /home/u909490256/public_html/uploads/

# Set permissions
chmod -R 755 /home/u909490256/public_html/uploads

# Verify
ls -la /home/u909490256/public_html/uploads/
```

## 📊 CHECKLIST DEPLOYMENT

Gunakan checklist ini untuk memastikan semua sudah benar:

- [ ] Laravel project ada di `/home/u909490256/domains/jastiphype.shop/`
- [ ] File `vendor/autoload.php` ada
- [ ] File `bootstrap/app.php` ada
- [ ] File `.env` ada dan sudah di-configure
- [ ] Folder `storage/` dan `bootstrap/cache/` writable (775)
- [ ] File `.htaccess` ada di `/home/u909490256/public_html/`
- [ ] File `index.php` ada di `/home/u909490256/public_html/` dengan path yang benar
- [ ] Assets (build, css, js) sudah di-copy ke public_html
- [ ] Upload folders sudah di-copy ke public_html
- [ ] Permissions sudah benar (755 untuk public_html, 775 untuk storage)
- [ ] Cache Laravel sudah di-clear dan di-rebuild
- [ ] Website bisa diakses tanpa error 404

## 🔗 REFERENSI

Dokumentasi dan tutorial yang digunakan:
- [Laravel Deployment on Shared Hosting](https://laravel.com/docs/deployment) - Official Laravel docs
- [Hostinger Laravel Deployment Guide](https://www.hostinger.com/tutorials/how-to-install-laravel) - Hostinger tutorial
- [Deploying Laravel to Shared Hosting](https://1v0.net/blog/how-to-deploy-laravel-12-app-to-shared-hosting) - Step-by-step guide (content rephrased for compliance with licensing restrictions)

---

**Last Updated:** 2026-02-12  
**Status:** ✅ Tested and Working
