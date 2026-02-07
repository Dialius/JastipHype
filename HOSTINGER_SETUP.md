# Setup Laravel di Hostinger - Solusi Error 403

## Masalah
Website menampilkan **403 Forbidden** karena struktur deployment tidak sesuai dengan konfigurasi Hostinger.

## Penyebab
Hostinger mengharapkan:
- File aplikasi Laravel (app, bootstrap, config, dll) → di **root directory** atau **di luar public_html**
- Isi folder `public` Laravel → di dalam **public_html** (document root)

## Solusi 1: Redirect dengan .htaccess (TERCEPAT)

Upload file `.htaccess` di root directory dengan isi:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

File ini akan redirect semua request ke folder `public`.

## Solusi 2: Pindahkan File Manual (RECOMMENDED)

### Langkah-langkah via File Manager Hostinger:

1. **Backup dulu** - Download semua file sebagai backup

2. **Pindahkan isi folder `public` ke `public_html`**:
   - Masuk ke folder `public`
   - Select All files di dalam folder `public`
   - Cut/Move semua file ke folder `public_html`
   - File yang dipindah: index.php, .htaccess, css/, js/, images/, dll

3. **Edit file `public_html/index.php`**:
   
   Ubah baris ini:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   ```
   Menjadi:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   ```
   
   Dan ubah:
   ```php
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```
   Menjadi:
   ```php
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```
   
   (Pastikan path mengarah ke parent directory yang benar)

4. **Hapus folder `public` yang sudah kosong** (opsional)

5. **Set Permission yang benar**:
   - Folders: 755
   - Files: 644
   - `storage/` dan `bootstrap/cache/`: 775

## Solusi 3: Update Git Deployment Script

Jika menggunakan auto-deployment dari Git, buat script deployment:

```bash
#!/bin/bash

# Deploy script untuk Hostinger
cd /home/u909490256/domains/jastiphype.shop

# Pull latest code
git pull origin master

# Install dependencies
composer install --no-dev --optimize-autoloader

# Copy public files to public_html
cp -r public/* public_html/

# Update index.php paths
sed -i "s|__DIR__.'/../|__DIR__.'/../|g" public_html/index.php

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 644 public_html/*
chmod 755 public_html

# Clear cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed!"
```

## Verifikasi Setup

Setelah setup, cek:

1. **File Structure**:
   ```
   /home/u909490256/domains/jastiphype.shop/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── routes/
   ├── storage/
   ├── vendor/
   ├── .env
   ├── artisan
   └── public_html/          ← Document root
       ├── index.php         ← Entry point
       ├── .htaccess
       ├── css/
       ├── js/
       └── images/
   ```

2. **Test URL**: https://jastiphype.shop
   - Harus menampilkan homepage Laravel
   - Tidak ada error 403

3. **Check Logs**:
   - `storage/logs/laravel.log`
   - Hostinger error logs di cPanel

## Troubleshooting

### Masih 403?
- Cek permission: `chmod -R 755 public_html`
- Cek ownership: file harus owned by user hosting Anda
- Cek .htaccess: pastikan `RewriteEngine On` aktif

### Error 500?
- Cek `storage/logs/laravel.log`
- Pastikan `.env` sudah benar
- Run: `php artisan config:clear`

### Assets tidak load?
- Cek path di `public_html/index.php`
- Run: `php artisan storage:link`
- Cek permission folder `storage`

## Database Setup

Jangan lupa update `.env` dengan kredensial database Hostinger:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u909490256_jastiphype
DB_USERNAME=u909490256_user
DB_PASSWORD=your_actual_password
```

Kemudian run migration:
```bash
php artisan migrate --force
php artisan db:seed --force
```

## Catatan Penting

- **Jangan commit `.env`** ke Git
- **Backup database** sebelum migration
- **Test di staging** dulu jika memungkinkan
- **Monitor logs** setelah deployment
