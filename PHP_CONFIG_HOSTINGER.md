# 🔧 CARA UBAH PHP CONFIGURATION DI HOSTINGER

## 📋 KONFIGURASI YANG PERLU DIUBAH

### Current Configuration (jastiphype.shop)
```ini
PHP version: 8.3.24 ✅
display_errors: On ⚠️ HARUS DIUBAH!
log_errors: On ✅
error_reporting: E_ALL ✅
max_execution_time: 360 ✅
max_input_time: 360 ✅
memory_limit: 1024M ✅
upload_max_filesize: 1024M ✅
post_max_size: 1024M ✅
max_input_vars: 5000 ✅
date.timezone: UTC ⚠️ SEBAIKNYA DIUBAH
OPcache: On ✅
```

### Recommended Changes for Production

```ini
display_errors = Off          # Keamanan: jangan tampilkan error ke user
date.timezone = Asia/Jakarta  # Timestamp sesuai waktu Indonesia
```

---

## 🎯 CARA UBAH VIA HOSTINGER HPANEL

### Method 1: Via PHP Configuration Tool (RECOMMENDED)

1. **Login ke hPanel Hostinger**
   - URL: https://hpanel.hostinger.com
   - Login dengan akun Anda

2. **Pilih Website**
   - Klik pada **jastiphype.shop**

3. **Buka PHP Configuration**
   - Scroll ke bagian **Advanced**
   - Klik **PHP Configuration**

4. **Ubah Settings**
   
   **Untuk display_errors:**
   - Cari setting **display_errors**
   - Ubah dari **On** menjadi **Off**
   - Klik **Save**

   **Untuk date.timezone:**
   - Cari setting **date.timezone**
   - Ubah dari **UTC** menjadi **Asia/Jakarta**
   - Klik **Save**

5. **Verify Changes**
   - Tunggu 1-2 menit untuk perubahan aktif
   - Refresh website Anda

---

### Method 2: Via .user.ini File (ALTERNATIVE)

Jika PHP Configuration tool tidak tersedia, buat file `.user.ini` di root directory.

**Via File Manager:**

1. Login ke hPanel → **File Manager**
2. Navigate ke `/home/u909490256/domains/jastiphype.shop`
3. Klik **New File**
4. Nama file: `.user.ini`
5. Edit file dan tambahkan:

```ini
; PHP Configuration for JastipHype Production
display_errors = Off
log_errors = On
error_reporting = E_ALL
date.timezone = Asia/Jakarta
```

6. Save file
7. Set permissions: **644**

**Via SSH:**

```bash
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002

cd /home/u909490256/domains/jastiphype.shop

# Buat .user.ini
cat > .user.ini << 'EOF'
; PHP Configuration for JastipHype Production
display_errors = Off
log_errors = On
error_reporting = E_ALL
date.timezone = Asia/Jakarta
EOF

# Set permissions
chmod 644 .user.ini

# Verify
cat .user.ini
```

**CATATAN:** Perubahan via `.user.ini` butuh waktu 5-10 menit untuk aktif.

---

### Method 3: Via php.ini (Jika Tersedia)

Beberapa hosting plan Hostinger support custom `php.ini`.

**Via File Manager:**

1. Login ke hPanel → **File Manager**
2. Navigate ke `/home/u909490256/domains/jastiphype.shop`
3. Cek apakah ada file `php.ini`
4. Jika ada, edit dan tambahkan:

```ini
display_errors = Off
date.timezone = Asia/Jakarta
```

5. Save dan restart PHP-FPM (jika ada option)

**Via SSH:**

```bash
cd /home/u909490256/domains/jastiphype.shop

# Cek apakah php.ini ada
ls -la php.ini

# Jika ada, edit
nano php.ini

# Tambahkan:
# display_errors = Off
# date.timezone = Asia/Jakarta

# Save (Ctrl+X, Y, Enter)
```

---

## ✅ VERIFY PERUBAHAN

### Via SSH (Paling Akurat)

```bash
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002

cd /home/u909490256/domains/jastiphype.shop

# Buat file test PHP
cat > public/phpinfo-test.php << 'EOF'
<?php
phpinfo();
EOF

# Akses via browser
# https://jastiphype.shop/phpinfo-test.php
# Cari "display_errors" dan "date.timezone"

# HAPUS file setelah cek (PENTING untuk keamanan!)
rm public/phpinfo-test.php
```

### Via Laravel Artisan

```bash
cd /home/u909490256/domains/jastiphype.shop

# Cek display_errors
php -r "echo 'display_errors: ' . ini_get('display_errors') . PHP_EOL;"

# Cek date.timezone
php -r "echo 'date.timezone: ' . ini_get('date.timezone') . PHP_EOL;"

# Cek semua PHP config
php -i | grep -E "display_errors|date.timezone"
```

### Expected Output

```
display_errors: 0 (atau Off)
date.timezone: Asia/Jakarta
```

---

## 🔍 TROUBLESHOOTING

### Perubahan Tidak Aktif

**Penyebab:**
- Cache PHP-FPM belum di-refresh
- File `.user.ini` permissions salah
- Hosting plan tidak support custom PHP config

**Solusi:**

1. **Tunggu 5-10 menit** untuk PHP-FPM reload

2. **Restart PHP-FPM** (jika ada option di hPanel)
   - hPanel → Advanced → PHP Configuration
   - Klik **Restart PHP**

3. **Cek permissions `.user.ini`**
   ```bash
   chmod 644 .user.ini
   ```

4. **Clear OPcache**
   ```bash
   cd /home/u909490256/domains/jastiphype.shop
   php artisan cache:clear
   php artisan config:clear
   ```

5. **Contact Hostinger Support**
   - Live Chat di hPanel
   - Bilang: "I need to change PHP display_errors to Off for security. Can you help?"

---

### display_errors Masih On

Jika setelah semua cara di atas masih On, override via Laravel:

**File: `public/index.php`**

Tambahkan di baris paling atas (setelah `<?php`):

```php
<?php

// Force disable display_errors for production
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// ... rest of the file
```

**Via SSH:**

```bash
cd /home/u909490256/domains/jastiphype.shop

# Backup original
cp public/index.php public/index.php.backup

# Add ini_set at the top
sed -i '2i\\n// Force disable display_errors for production\nini_set('"'"'display_errors'"'"', '"'"'0'"'"');\nini_set('"'"'display_startup_errors'"'"', '"'"'0'"'"');\nerror_reporting(E_ALL);\n' public/index.php

# Verify
head -10 public/index.php
```

---

### date.timezone Tidak Berubah

Override via Laravel config:

**File: `config/app.php`**

```php
'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
```

**Update .env:**

```bash
cd /home/u909490256/domains/jastiphype.shop

# Tambahkan ke .env
echo "APP_TIMEZONE=Asia/Jakarta" >> .env

# Clear cache
php artisan config:clear
php artisan config:cache

# Verify
php artisan tinker --execute="echo 'Timezone: ' . config('app.timezone') . PHP_EOL;"
```

---

## 📊 DAMPAK PERUBAHAN

### display_errors = Off

**Keuntungan:**
- ✅ Error tidak ditampilkan ke user (keamanan)
- ✅ Mencegah information disclosure (database credentials, file paths)
- ✅ User experience lebih baik (tidak lihat error teknis)
- ✅ Error tetap tercatat di `storage/logs/laravel.log`

**Kerugian:**
- ⚠️ Debugging lebih susah (harus cek log file)

**Solusi untuk Debugging:**
```bash
# Sementara enable untuk debugging
php artisan tinker --execute="ini_set('display_errors', '1');"

# Atau cek log real-time
tail -f storage/logs/laravel.log
```

---

### date.timezone = Asia/Jakarta

**Keuntungan:**
- ✅ Timestamp order/transaksi sesuai waktu Indonesia (WIB)
- ✅ Midtrans payment timestamp akurat
- ✅ Email notification timestamp benar
- ✅ User melihat waktu yang familiar

**Contoh:**
```php
// Sebelum (UTC):
Order created at: 2026-02-12 03:00:00 (bingung user!)

// Sesudah (Asia/Jakarta):
Order created at: 2026-02-12 10:00:00 (jelas!)
```

---

## 🎯 CHECKLIST SETELAH PERUBAHAN

Setelah ubah PHP config, pastikan:

- [ ] `display_errors = Off` (cek via `php -i | grep display_errors`)
- [ ] `date.timezone = Asia/Jakarta` (cek via `php -i | grep date.timezone`)
- [ ] Website masih bisa diakses (tidak error)
- [ ] Error tetap tercatat di `storage/logs/laravel.log`
- [ ] Timestamp di database sesuai waktu Indonesia
- [ ] Hapus file `phpinfo-test.php` jika dibuat (KEAMANAN!)

---

## 🔐 KEAMANAN TAMBAHAN

Setelah ubah PHP config, tambahkan juga:

### 1. Disable PHP Execution di Uploads Folder

**File: `public/uploads/.htaccess`**

```apache
# Disable PHP execution in uploads folder
<FilesMatch "\.(php|php3|php4|php5|phtml)$">
    Order Deny,Allow
    Deny from all
</FilesMatch>
```

**Via SSH:**

```bash
cd /home/u909490256/domains/jastiphype.shop

cat > public/uploads/.htaccess << 'EOF'
# Disable PHP execution in uploads folder
<FilesMatch "\.(php|php3|php4|php5|phtml)$">
    Order Deny,Allow
    Deny from all
</FilesMatch>
EOF

chmod 644 public/uploads/.htaccess
```

---

### 2. Hide Laravel Version

**File: `public/.htaccess`**

Tambahkan di bagian atas:

```apache
# Hide server information
ServerSignature Off

# Disable directory listing
Options -Indexes
```

---

### 3. Set Secure Headers

**File: `public/.htaccess`**

Tambahkan:

```apache
# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

---

## 📞 BANTUAN

Jika masih ada masalah setelah ubah PHP config:

1. **Kirim output dari:**
   ```bash
   php -i | grep -E "display_errors|date.timezone|Loaded Configuration File"
   ```

2. **Screenshot PHP Configuration** di hPanel

3. **Cek apakah perubahan aktif:**
   ```bash
   php -r "echo 'display_errors: ' . ini_get('display_errors') . PHP_EOL;"
   php -r "echo 'date.timezone: ' . ini_get('date.timezone') . PHP_EOL;"
   ```

---

**Dibuat:** 12 Februari 2026  
**Status:** Ready to implement  
**Estimated Time:** 5-10 menit untuk perubahan aktif
