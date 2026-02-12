# 🔍 DIAGNOSIS ERROR 500 - JASTIPHYPE.SHOP

## 📊 INFORMASI SISTEM

### Laravel Configuration
- **Laravel Version:** 12.x
- **PHP Required:** 8.2+ (Server: 8.3.24 ✅)
- **Database:** MySQL (u909490256_jastiphype)
- **Filesystem:** public (uploads folder)

### PHP Extensions Status
✅ **Aktif & Sesuai:**
- mbstring, dom, pdo, pdo_mysql (nd_pdo_mysql)
- fileinfo, gd, intl, opcache
- bcmath (untuk Midtrans)
- imagick, imap, gmp

### PHP Configuration Current
```
PHP Version: 8.3.24
display_errors: On ⚠️ (HARUS Off di production!)
log_errors: On ✅
error_reporting: E_ALL ✅
max_execution_time: 360 ✅
memory_limit: 1024M ✅
upload_max_filesize: 1024M ✅
post_max_size: 1024M ✅
date.timezone: UTC ⚠️ (Sebaiknya Asia/Jakarta)
OPcache: On ✅
```

---

## 🚨 KEMUNGKINAN PENYEBAB ERROR 500

### 1. ❌ FILE PERMISSIONS (PALING SERING)

Laravel butuh write access ke:
- `storage/` → 775
- `storage/logs/` → 775
- `storage/framework/cache/` → 775
- `storage/framework/sessions/` → 775
- `storage/framework/views/` → 775
- `bootstrap/cache/` → 775
- `public/uploads/` → 755

**Cek via SSH:**
```bash
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002

cd /home/u909490256/domains/jastiphype.shop

# Cek permissions
ls -la storage/
ls -la bootstrap/cache/
ls -la public/uploads/

# Fix permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 755 public/uploads
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;
```

---

### 2. ❌ MISSING VENDOR DEPENDENCIES

Composer packages belum terinstall atau tidak lengkap.

**Cek via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop

# Cek apakah vendor ada
ls -la vendor/

# Reinstall dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Verify
php artisan --version
```

---

### 3. ❌ DATABASE CONNECTION ERROR

Credentials di `.env` tidak match atau database tidak accessible.

**Cek via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop

# Cek .env
cat .env | grep DB_

# Test connection
php artisan tinker --execute="try { \$pdo = DB::connection()->getPdo(); echo 'Database OK!' . PHP_EOL; } catch (Exception \$e) { echo 'Error: ' . \$e->getMessage() . PHP_EOL; }"
```

**Expected Output:**
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u909490256_jastiphype
DB_USERNAME=u909490256_vinthegreat
DB_PASSWORD=XmAJ4!9tmJEt4hE
```

---

### 4. ❌ CACHED CONFIG DENGAN .ENV LAMA

Laravel cache config lama yang masih pakai credentials salah.

**Fix via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop

# NUCLEAR: Hapus semua cache
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*
rm -rf storage/framework/sessions/*

# Clear artisan cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify config
php artisan tinker --execute="echo 'DB: ' . config('database.connections.mysql.database') . PHP_EOL;"
```

---

### 5. ❌ MISSING .ENV FILE

File `.env` tidak ada atau tidak ter-copy ke server.

**Cek via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop

# Cek apakah .env ada
ls -la .env

# Jika tidak ada, copy dari .env.hostinger
cp .env.hostinger .env

# Generate APP_KEY jika belum ada
php artisan key:generate --force

# Verify
cat .env | grep APP_KEY
```

---

### 6. ❌ WRONG DOCUMENT ROOT / INDEX.PHP PATH

Hostinger structure berbeda dengan local. Ada 2 kemungkinan:

#### **Scenario A: Document Root = /public_html (Salah untuk Laravel)**

File structure:
```
/home/u909490256/domains/jastiphype.shop/
├── app/
├── bootstrap/
├── config/
├── public/
│   ├── index.php
│   └── .htaccess
└── public_html/  ← Document root (SALAH!)
```

**Fix:** Copy isi `public/` ke `public_html/` dan update path di `index.php`

#### **Scenario B: Document Root = /public_html/public (Benar)**

File structure:
```
/home/u909490256/domains/jastiphype.shop/
├── app/
├── bootstrap/
├── config/
└── public_html/
    └── public/  ← Document root (BENAR!)
        ├── index.php
        └── .htaccess
```

**Cek via SSH:**
```bash
# Cek structure
ls -la /home/u909490256/domains/jastiphype.shop/
ls -la /home/u909490256/public_html/

# Cek index.php location
find /home/u909490256 -name "index.php" -path "*/public*" 2>/dev/null
```

---

### 7. ❌ MISSING UPLOADS FOLDER

Folder `public/uploads/` tidak ada, Laravel error saat upload/display gambar.

**Fix via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop

# Buat folder uploads
mkdir -p public/uploads/products
mkdir -p public/uploads/brands
mkdir -p public/uploads/categories
mkdir -p public/uploads/banners

# Set permissions
chmod -R 755 public/uploads

# Copy ke public_html jika perlu
cp -rf public/uploads /home/u909490256/public_html/
```

---

### 8. ❌ .HTACCESS TIDAK TER-LOAD

Apache mod_rewrite tidak aktif atau .htaccess tidak dibaca.

**Cek via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop/public

# Cek apakah .htaccess ada
ls -la .htaccess

# Cek content
cat .htaccess | head -20

# Test rewrite
php -S localhost:8000 -t public
# Buka browser: http://localhost:8000
```

**Jika .htaccess tidak ada, buat:**
```bash
cat > public/.htaccess << 'EOF'
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
```

---

### 9. ❌ MIGRATION BELUM DIJALANKAN

Database tables belum dibuat, Laravel error saat query.

**Fix via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop

# Cek tables
php artisan tinker --execute="echo 'Tables: ' . implode(', ', DB::connection()->getDoctrineSchemaManager()->listTableNames()) . PHP_EOL;"

# Run migration
php artisan migrate --force

# Verify
php artisan tinker --execute="echo 'Users count: ' . DB::table('users')->count() . PHP_EOL;"
```

---

### 10. ❌ APP_KEY TIDAK DI-SET

Laravel butuh APP_KEY untuk encryption.

**Fix via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop

# Cek APP_KEY
cat .env | grep APP_KEY

# Generate jika kosong
php artisan key:generate --force

# Verify
php artisan tinker --execute="echo 'APP_KEY: ' . config('app.key') . PHP_EOL;"
```

---

## 🔧 REKOMENDASI PHP CONFIGURATION CHANGES

### Settings yang HARUS Diubah:

```ini
display_errors = Off          # Saat ini: On (BAHAYA di production!)
date.timezone = Asia/Jakarta  # Saat ini: UTC (untuk timestamp Indonesia)
```

### Alasan:
1. **display_errors = Off**
   - Error tidak ditampilkan ke user (keamanan)
   - Error tetap tercatat di `storage/logs/laravel.log`
   - Mencegah information disclosure

2. **date.timezone = Asia/Jakarta**
   - Timestamp order/transaksi sesuai waktu Indonesia
   - Midtrans payment timestamp akurat
   - User experience lebih baik

---

## 🚀 LANGKAH DIAGNOSIS (STEP-BY-STEP)

### Step 1: Cek Error Log

```bash
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002
cd /home/u909490256/domains/jastiphype.shop

# Cek Laravel log
tail -50 storage/logs/laravel.log

# Cek PHP error log (jika ada)
tail -50 /home/u909490256/logs/error_log
```

**Kirim output log ini untuk analisis lebih lanjut!**

---

### Step 2: Jalankan Nuclear Fix Script

```bash
cd /home/u909490256/domains/jastiphype.shop
bash nuclear-fix.sh
```

Script ini akan:
- Backup .env lama
- Pull latest code
- Force copy .env.hostinger
- Delete ALL cache
- Recreate cache directories
- Set permissions
- Clear artisan cache
- Rebuild config cache
- Test database connection

---

### Step 3: Verify Configuration

```bash
# Cek APP_KEY
php artisan tinker --execute="echo 'APP_KEY: ' . config('app.key') . PHP_EOL;"

# Cek Database
php artisan tinker --execute="echo 'DB: ' . config('database.connections.mysql.database') . PHP_EOL;"

# Cek FILESYSTEM_DISK
php artisan tinker --execute="echo 'FILESYSTEM: ' . config('filesystems.default') . PHP_EOL;"

# Test database connection
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK!' . PHP_EOL;"

# Cek folder uploads
ls -lh public/uploads/
```

---

### Step 4: Fix Permissions (Jika Perlu)

```bash
# Fix permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 755 public/uploads
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;

# Verify
ls -la storage/ | head -10
ls -la bootstrap/cache/
```

---

### Step 5: Test Website

1. Clear browser cache (Ctrl+Shift+Delete)
2. Buka: https://jastiphype.shop
3. Cek apakah masih error 500

**Jika masih error:**
- Kirim output dari `tail -50 storage/logs/laravel.log`
- Screenshot error page
- Output dari verification commands di Step 3

---

## 📋 CHECKLIST TROUBLESHOOTING

Sebelum declare "masih error", pastikan sudah cek:

- [ ] Error log Laravel (`storage/logs/laravel.log`)
- [ ] PHP error log Hostinger
- [ ] File permissions (storage, bootstrap/cache)
- [ ] Vendor dependencies installed
- [ ] Database connection working
- [ ] .env file exists dan benar
- [ ] APP_KEY generated
- [ ] Cache cleared & rebuilt
- [ ] Uploads folder exists
- [ ] .htaccess exists di public/
- [ ] Migration sudah dijalankan
- [ ] Browser cache cleared

---

## 🆘 NEXT STEPS

### Jika Sudah Cek Semua & Masih Error:

1. **Kirim output dari:**
   ```bash
   # System info
   php -v
   composer --version
   php artisan --version
   
   # Error log
   tail -50 storage/logs/laravel.log
   
   # Environment check
   cat .env | grep -E "APP_|DB_|FILESYSTEM"
   
   # Permissions
   ls -la storage/ | head -10
   ls -la bootstrap/cache/
   
   # Database test
   php artisan tinker --execute="DB::connection()->getPdo();"
   ```

2. **Screenshot error page** (jika ada pesan error spesifik)

3. **Cek Hostinger error log** di hPanel → Advanced → Error Logs

---

## 🔥 EMERGENCY FIX (LAST RESORT)

Jika semua gagal, jalankan fresh deployment:

```bash
cd /home/u909490256/domains/jastiphype.shop

# Backup everything
tar -czf backup_$(date +%Y%m%d_%H%M%S).tar.gz .

# Fresh clone
cd ..
rm -rf jastiphype.shop
git clone git@github.com:Dialius/JastipHype.git jastiphype.shop
cd jastiphype.shop

# Follow FRESH_DEPLOYMENT_GUIDE.md
```

---

**Dibuat:** 12 Februari 2026  
**Status:** Ready for diagnosis  
**Next:** Jalankan Step 1 (Cek Error Log) dan kirim output
