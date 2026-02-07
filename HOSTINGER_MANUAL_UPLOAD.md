# Upload Manual Laravel ke Hostinger

## Masalah
Git deployment tidak meng-copy file Laravel ke server. Root directory kosong.

## Solusi: Upload Manual

### Persiapan File untuk Upload

1. **Buat ZIP file** dari project Laravel Anda (TANPA folder berikut):
   - `node_modules/` (terlalu besar, akan di-install ulang)
   - `.git/` (tidak perlu)
   - `storage/logs/*.log` (file log lama)
   - `public/storage` (symlink, akan dibuat ulang)

2. **File yang HARUS di-upload**:
   ```
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── public/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/          ← PENTING! Jika tidak ada, run composer install dulu
   ├── .env.hostinger   ← Rename jadi .env setelah upload
   ├── artisan
   ├── composer.json
   └── composer.lock
   ```

### Langkah Upload via File Manager Hostinger

1. **Login ke Hostinger File Manager**
   - Buka: https://hpanel.hostinger.com
   - Pilih website: jastiphype.shop
   - Klik "File Manager"

2. **Navigasi ke Root Directory**
   - Klik icon "Home" untuk ke root
   - Path: `/home/u909490256/domains/jastiphype.shop/`

3. **Upload ZIP File**
   - Klik tombol "Upload" (icon panah ke atas)
   - Pilih file ZIP project Laravel Anda
   - Tunggu sampai selesai upload

4. **Extract ZIP**
   - Klik kanan pada file ZIP
   - Pilih "Extract"
   - Extract ke current directory
   - Hapus file ZIP setelah selesai

5. **Setup Folder public_html**
   - Masuk ke folder `public`
   - Select All files di dalam folder `public`
   - Cut (Ctrl+X)
   - Keluar ke parent directory
   - Masuk ke folder `public_html`
   - Paste (Ctrl+V)

6. **Edit public_html/index.php**
   - Buka file `public_html/index.php`
   - Cari baris:
     ```php
     require __DIR__.'/../vendor/autoload.php';
     ```
   - Pastikan path `/../` mengarah ke parent directory yang benar
   - Jika perlu, ubah jadi:
     ```php
     require __DIR__.'/../vendor/autoload.php';
     $app = require_once __DIR__.'/../bootstrap/app.php';
     ```

7. **Rename .env.hostinger menjadi .env**
   - Di root directory
   - Klik kanan `.env.hostinger`
   - Rename → `.env`

8. **Set Permission**
   - Select folder `storage`
   - Klik kanan → Permissions → 775
   - Select folder `bootstrap/cache`
   - Klik kanan → Permissions → 775

### Langkah Upload via FTP (Alternatif)

1. **Download FTP Client**
   - FileZilla: https://filezilla-project.org/
   - WinSCP: https://winscp.net/

2. **Koneksi FTP**
   - Host: ftp.jastiphype.shop (atau IP dari Hostinger)
   - Username: u909490256
   - Password: (password hosting Anda)
   - Port: 21

3. **Upload Files**
   - Local: Project Laravel Anda
   - Remote: `/domains/jastiphype.shop/`
   - Upload semua file KECUALI `node_modules/` dan `.git/`

4. **Ikuti langkah 5-8 dari metode File Manager**

### Setup Database

1. **Buat Database di Hostinger**
   - Login ke hPanel
   - Pilih "Databases" → "MySQL Databases"
   - Klik "Create New Database"
   - Database name: `u909490256_jastiphype`
   - Create user: `u909490256_user`
   - Set password yang kuat
   - Grant all privileges

2. **Update .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=u909490256_jastiphype
   DB_USERNAME=u909490256_user
   DB_PASSWORD=password_yang_baru_dibuat
   ```

3. **Run Migration via SSH**
   - Login SSH ke Hostinger
   - Navigate: `cd domains/jastiphype.shop`
   - Run:
     ```bash
     php artisan migrate --force
     php artisan db:seed --force
     php artisan storage:link
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```

### Jika Tidak Ada Akses SSH

Gunakan web-based terminal atau buat file PHP untuk run artisan:

**File: `run-setup.php`** (upload ke root, akses via browser, HAPUS setelah selesai!)

```php
<?php
// HAPUS FILE INI SETELAH SETUP SELESAI!

echo "<pre>";

// Run migrations
echo "Running migrations...\n";
passthru('php artisan migrate --force 2>&1');

// Run seeders
echo "\nRunning seeders...\n";
passthru('php artisan db:seed --force 2>&1');

// Create storage link
echo "\nCreating storage link...\n";
passthru('php artisan storage:link 2>&1');

// Cache config
echo "\nCaching config...\n";
passthru('php artisan config:cache 2>&1');

// Cache routes
echo "\nCaching routes...\n";
passthru('php artisan route:cache 2>&1');

// Cache views
echo "\nCaching views...\n";
passthru('php artisan view:cache 2>&1');

echo "\n\nSetup completed! DELETE THIS FILE NOW!\n";
echo "</pre>";
?>
```

Akses: `https://jastiphype.shop/run-setup.php`

**PENTING: HAPUS file ini setelah selesai!**

### Verifikasi

1. **Cek struktur file**:
   ```
   Root directory:
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── vendor/
   ├── .env
   └── public_html/
       ├── index.php
       ├── .htaccess
       └── assets/
   ```

2. **Test website**: https://jastiphype.shop
   - Harus menampilkan homepage Laravel
   - Tidak ada error 403 atau 500

3. **Cek logs**: `storage/logs/laravel.log`

### Troubleshooting

**Error: "No such file or directory"**
- Pastikan path di `public_html/index.php` benar
- Cek: `require __DIR__.'/../vendor/autoload.php';`

**Error: "Permission denied"**
- Set permission: `chmod -R 775 storage bootstrap/cache`

**Error: "Class not found"**
- Run: `composer install --no-dev --optimize-autoloader`
- Atau upload folder `vendor/` yang sudah di-generate lokal

**Assets tidak load**
- Cek path di views
- Run: `php artisan storage:link`
- Pastikan folder `public_html/storage` ada

## Setelah Upload Berhasil

Jangan lupa:
1. **Hapus file `run-setup.php`** jika Anda buat
2. **Set APP_DEBUG=false** di `.env`
3. **Backup database** secara berkala
4. **Monitor logs** di `storage/logs/`
5. **Setup SSL** di Hostinger (gratis)

## Catatan Penting

- **JANGAN upload `.env` ke Git**
- **JANGAN expose `run-setup.php`** ke public
- **Backup dulu** sebelum upload
- **Test di local** dulu sebelum upload
