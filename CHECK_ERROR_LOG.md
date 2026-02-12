# 🔍 CARA CEK ERROR LOG UNTUK HTTP 500 - JASTIPHYPE.SHOP

## ❌ KESALAHAN: Ini Bukan Node.js!

Website Anda adalah **Laravel (PHP)**, bukan Node.js. AI Hostinger salah deteksi.

---

## ✅ LANGKAH BENAR CEK ERROR 500

### Opsi 1: Via SSH (TERCEPAT)

```bash
# Login SSH
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002

# Cek Laravel error log
tail -50 /home/u909490256/domains/jastiphype.shop/storage/logs/laravel.log

# Atau cek error log terbaru
ls -lt /home/u909490256/domains/jastiphype.shop/storage/logs/
cat /home/u909490256/domains/jastiphype.shop/storage/logs/laravel-$(date +%Y-%m-%d).log
```

### Opsi 2: Via File Manager hPanel

1. Login ke **hPanel** → **Files** → **File Manager**
2. Navigate ke: `/domains/jastiphype.shop/storage/logs/`
3. Buka file `laravel.log` (atau file dengan tanggal hari ini)
4. Scroll ke bawah, copy **20-30 baris terakhir**
5. Paste di sini

### Opsi 3: Cek PHP Error Log

1. File Manager → `/domains/jastiphype.shop/.logs/`
2. Atau: `/domains/jastiphype.shop/public_html/error_log`
3. Copy isi file tersebut

---

## 🚨 KEMUNGKINAN PENYEBAB ERROR 500

Berdasarkan setup Anda, kemungkinan besar salah satu dari ini:

### 1. APP_KEY Tidak Ada/Salah
```bash
# Fix via SSH:
cd /home/u909490256/domains/jastiphype.shop
php artisan key:generate --force
php artisan config:cache
```

### 2. Database Connection Error
```bash
# Test koneksi:
php artisan tinker --execute="DB::connection()->getPdo(); echo 'OK';"
```

### 3. Permission Error
```bash
# Fix permissions:
chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 664 {} \;
```

### 4. Cache Corrupt
```bash
# Clear semua cache:
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild:
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. .env File Tidak Ada
```bash
# Copy dari template:
cp .env.hostinger .env
php artisan key:generate --force
```

---

## 🔧 QUICK FIX (Jalankan Ini Dulu)

Login SSH dan jalankan:

```bash
cd /home/u909490256/domains/jastiphype.shop

# 1. Cek error log
echo "=== LARAVEL ERROR LOG ==="
tail -30 storage/logs/laravel.log

# 2. Cek .env
echo -e "\n=== ENV CHECK ==="
ls -la .env
grep -E "APP_KEY|DB_DATABASE" .env

# 3. Fix permissions
echo -e "\n=== FIXING PERMISSIONS ==="
chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 664 {} \;

# 4. Clear cache
echo -e "\n=== CLEARING CACHE ==="
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 5. Rebuild cache
echo -e "\n=== REBUILDING CACHE ==="
php artisan config:cache

# 6. Test
echo -e "\n=== TESTING ==="
php artisan --version
```

---

## 📋 INFO YANG SAYA BUTUHKAN

Setelah jalankan command di atas, kirim output dari:

1. **Error log** (dari `tail -30 storage/logs/laravel.log`)
2. **ENV check** (apakah APP_KEY ada?)
3. **Permission status** (apakah ada error?)
4. **Test result** (apakah `php artisan --version` jalan?)

Dengan info ini saya bisa kasih fix yang tepat.

---

## 🎯 CATATAN PENTING

- **JANGAN** ikuti saran Node.js dari AI Hostinger
- Website Anda adalah **Laravel/PHP**, bukan Node.js
- Error 500 biasanya karena:
  - APP_KEY kosong
  - Database tidak connect
  - Permission salah
  - Cache corrupt

Jalankan quick fix di atas, lalu kirim hasilnya ke saya! 🚀
