# 🔧 Fix Error 403 Forbidden di Hostinger

**Error:** 403 Forbidden - Access to this resource on the server is denied!  
**URL:** https://jastiphype.shop

---

## 🔍 Root Cause Analysis

Error 403 Forbidden terjadi karena:

1. ❌ **File permissions salah** - Laravel butuh permission khusus
2. ❌ **Public directory tidak di-set sebagai Document Root**
3. ❌ **.htaccess tidak ter-load** atau salah konfigurasi
4. ❌ **Index.php tidak ditemukan** di public directory
5. ❌ **ModSecurity blocking** request

---

## ✅ Solusi Lengkap (Step-by-Step)

### **Step 1: Set Document Root ke `/public`**

**Di Hostinger Control Panel:**

1. Login ke **hPanel** Hostinger
2. Klik **Advanced** → **File Manager**
3. Atau klik **Domains** → **Manage** (domain jastiphype.shop)
4. Cari **Document Root** setting
5. Ubah dari `/public_html` menjadi `/public_html/public`
6. Save changes

**Atau via File Manager:**

1. Buka File Manager
2. Rename folder `public_html` → `public_html_backup`
3. Buat symbolic link:
   ```bash
   ln -s /home/u909490256/domains/jastiphype.shop/public_html/public /home/u909490256/domains/jastiphype.shop/public_html
   ```

---

### **Step 2: Fix File Permissions**

**Via SSH (Recommended):**

```bash
# Login SSH
ssh u909490256@jastiphype.shop

# Masuk ke directory project
cd domains/jastiphype.shop/public_html

# Set permissions untuk directories
find . -type d -exec chmod 755 {} \;

# Set permissions untuk files
find . -type f -exec chmod 644 {} \;

# Set permissions khusus untuk storage dan bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (ganti 'nobody' dengan user server Anda)
chown -R u909490256:u909490256 .
```

**Via File Manager (Alternative):**

1. Buka File Manager
2. Select all files/folders
3. Right click → **Change Permissions**
4. Set:
   - Folders: `755` (rwxr-xr-x)
   - Files: `644` (rw-r--r--)
   - `storage/`: `775` (rwxrwxr-x)
   - `bootstrap/cache/`: `775` (rwxrwxr-x)

---

### **Step 3: Cek & Fix .htaccess**

**File: `public/.htaccess`**

Pastikan file ini ada dan berisi:

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

**Jika file tidak ada, buat manual:**

```bash
# Via SSH
cd public
nano .htaccess
# Paste content di atas, save (Ctrl+X, Y, Enter)
```

---

### **Step 4: Update .env untuk Hostinger**

**File: `.env`**

```env
APP_NAME=JastipHype
APP_ENV=production
APP_KEY=base64:Ksc82+I7kMwWoOGGzSFWV/VvTND1VcXZQQG5v5FVWUI=
APP_DEBUG=false
APP_URL=https://jastiphype.shop
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

# Database - Hostinger MySQL
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u909490256_jastiphype
DB_USERNAME=u909490256_vinthegreat
DB_PASSWORD=XmAJ4!9tmJEt4hE

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database

# Queue & Broadcast
QUEUE_CONNECTION=database
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local

# Mail - Hostinger SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@jastiphype.shop
MAIL_PASSWORD="XmAJ4!9tmJEt4hE"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@jastiphype.shop"
MAIL_FROM_NAME="JastipHype"

# Additional Email Addresses
MAIL_ADMIN=admin@jastiphype.shop
MAIL_ORDER=order@jastiphype.shop
MAIL_SUPPORT=support@jastiphype.shop
MAIL_NOREPLY=noreply@jastiphype.shop

# Google OAuth - UPDATE CALLBACK URL
GOOGLE_CLIENT_ID=69087409346-s4p6help1912k7svmgjrfnmf6bbrq44a.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URL=https://jastiphype.shop/auth/google/callback

# RajaOngkir
RAJAONGKIR_API_KEY=DL8eP5LD7c09c1638d2c213eYrBangX6
RAJAONGKIR_TYPE=starter
RAJAONGKIR_ORIGIN=151

# Midtrans Configuration
MIDTRANS_MERCHANT_ID=G689132907
MIDTRANS_CLIENT_KEY=Mid-client-DTSB6VSWG4ReInb0
MIDTRANS_SERVER_KEY=Mid-server-ezqCCqbhe-zfF83kGA1ETmVy
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

VITE_APP_NAME="${APP_NAME}"

# Cloudinary
CLOUDINARY_URL=cloudinary://966375899228811:HAOnLFxG8oxqMcEik5muf2JLLgE@dahizgvcq
CLOUDINARY_UPLOAD_PRESET=
CLOUDINARY_NOTIFICATION_URL=
CLOUDINARY_API_KEY=966375899228811
CLOUDINARY_API_SECRET=HAOnLFxG8oxqMcEik5muf2JLLgE
CLOUDINARY_CLOUD_NAME=dahizgvcq
```

**Upload file ini ke server:**

```bash
# Via SSH
cd /home/u909490256/domains/jastiphype.shop/public_html
nano .env
# Paste content di atas, save
```

---

### **Step 5: Clear Cache & Optimize**

```bash
# Via SSH
cd /home/u909490256/domains/jastiphype.shop/public_html

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate optimized autoload
composer dump-autoload --optimize
```

---

### **Step 6: Cek PHP Version**

Laravel 12 butuh **PHP 8.2+**

**Di Hostinger:**

1. Go to **Advanced** → **PHP Configuration**
2. Select **PHP 8.2** atau **PHP 8.3**
3. Save

**Via SSH:**

```bash
php -v
# Harus menampilkan PHP 8.2.x atau 8.3.x
```

---

### **Step 7: Cek Index.php**

**File: `public/index.php`**

Pastikan file ini ada dan executable:

```bash
# Via SSH
cd public
ls -la index.php
# Output harus: -rw-r--r-- (644)

# Jika tidak ada, copy dari Laravel:
cp ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php index.php
```

---

### **Step 8: Disable ModSecurity (Jika Perlu)**

Jika masih error 403, coba disable ModSecurity:

**Tambahkan di `public/.htaccess` (paling atas):**

```apache
<IfModule mod_security.c>
    SecFilterEngine Off
    SecFilterScanPOST Off
</IfModule>
```

---

### **Step 9: Check Error Logs**

**Via File Manager:**

1. Buka `/storage/logs/laravel.log`
2. Cek error terakhir

**Via SSH:**

```bash
tail -f storage/logs/laravel.log
```

**Hostinger Error Logs:**

1. hPanel → **Advanced** → **Error Logs**
2. Cek error terbaru

---

## 🧪 Testing

Setelah semua step di atas, test:

1. **Clear browser cache** (Ctrl+Shift+Delete)
2. Buka https://jastiphype.shop
3. Seharusnya muncul homepage Laravel

**Jika masih 403:**

```bash
# Cek permission lagi
ls -la public/
# index.php harus: -rw-r--r--

# Cek .htaccess
cat public/.htaccess
# Harus ada content

# Cek PHP error
php public/index.php
# Harus menampilkan HTML atau error Laravel
```

---

## 🚨 Common Issues & Solutions

### Issue 1: "No input file specified"

**Solution:**
```apache
# Tambahkan di public/.htaccess
RewriteRule ^(.*)$ index.php/$1 [L]
```

### Issue 2: "500 Internal Server Error"

**Solution:**
```bash
# Cek error log
tail -f storage/logs/laravel.log

# Biasanya permission issue
chmod -R 775 storage bootstrap/cache
```

### Issue 3: "Symlink not allowed"

**Solution:**
```bash
# Jangan pakai symlink, copy manual
cp -r storage/app/public/* public/storage/
```

### Issue 4: "Database connection failed"

**Solution:**
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
# Harus return PDO object
```

---

## 📋 Checklist

Sebelum declare "DONE", pastikan:

- [ ] Document Root = `/public_html/public`
- [ ] File permissions: 644 (files), 755 (folders)
- [ ] `storage/` & `bootstrap/cache/` = 775
- [ ] `.htaccess` ada di `public/`
- [ ] `.env` sudah update (APP_URL, DB_HOST, etc)
- [ ] PHP version = 8.2+
- [ ] Cache cleared & optimized
- [ ] `public/index.php` exists & executable
- [ ] Error logs checked
- [ ] Browser cache cleared
- [ ] Website accessible (no 403)

---

## 🆘 Jika Masih Gagal

**Contact Hostinger Support:**

1. Login hPanel
2. Klik **Help** → **Live Chat**
3. Bilang: "I'm getting 403 Forbidden error on my Laravel app. I've set document root to /public and fixed permissions. Can you help check server configuration?"

**Atau kirim screenshot error ke saya untuk analisis lebih lanjut!**

---

**Dibuat:** 6 Februari 2026  
**Skill Used:** `systematic-debugging`, `hostinger-deployment`, `laravel-troubleshooting`
