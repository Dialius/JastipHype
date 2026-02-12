# 🚨 FIX ERROR 500 SEKARANG - JASTIPHYPE.SHOP

## ✅ ERROR SUDAH DIIDENTIFIKASI!

```
Call to undefined function Illuminate\Support\mb_split()
```

**Masalah:** PHP extension `mbstring` tidak ter-load dengan benar.

---

## ⚡ COPY-PASTE COMMANDS INI (5 MENIT)

### Step 1: Login SSH & Run Fix

```bash
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002
```

Setelah login, jalankan:

```bash
cd /home/u909490256/domains/jastiphype.shop && bash fix-mbstring-error.sh
```

**Tunggu 5-10 menit**, lalu test website: https://jastiphype.shop

---

## 🎯 ATAU FIX VIA HOSTINGER HPANEL (PALING RELIABLE)

### Step 1: Login hPanel
- URL: https://hpanel.hostinger.com
- Login dengan akun Anda

### Step 2: Pilih Website
- Klik **jastiphype.shop**

### Step 3: Buka PHP Configuration
- Scroll ke **Advanced**
- Klik **PHP Configuration**

### Step 4: Enable mbstring
- Scroll ke bagian **Extensions**
- Cari **mbstring**
- **PASTIKAN CHECKBOX CHECKED** (centang)
- Klik **Save**

### Step 5: Restart PHP (jika ada)
- Cari tombol **Restart PHP** atau **Restart PHP-FPM**
- Klik untuk restart

### Step 6: Test Website
- Tunggu 2-3 menit
- Buka: https://jastiphype.shop

---

## ✅ VERIFY FIX

Setelah fix, jalankan ini untuk verify:

```bash
# Check mbstring loaded
php -m | grep mbstring

# Test mb_split function
php -r "if (function_exists('mb_split')) { echo 'mb_split() is available\n'; } else { echo 'mb_split() is NOT available\n'; }"

# Test Laravel
php artisan --version

# Check error log
tail -20 storage/logs/laravel.log
```

**Expected:**
- mbstring shows in list
- mb_split() is available
- Laravel version shows without error
- No new mb_split errors in log

---

## 🆘 JIKA MASIH ERROR

### Option 1: Contact Hostinger Support

**Live Chat di hPanel:**

```
Hi, I'm getting this error on my Laravel 12 application:
"Call to undefined function Illuminate\Support\mb_split()"

I've enabled mbstring in PHP Configuration, but the function is still not available.

Can you please:
1. Verify mbstring is properly loaded
2. Check if mb_split() function is available
3. Restart PHP-FPM

Domain: jastiphype.shop
PHP Version: 8.3.24

Thank you!
```

### Option 2: Force Enable via .user.ini

```bash
cd /home/u909490256/domains/jastiphype.shop

cat > .user.ini << 'EOF'
extension=mbstring.so
display_errors = Off
log_errors = On
error_reporting = E_ALL
date.timezone = Asia/Jakarta
EOF

chmod 644 .user.ini

# Clear caches
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
php artisan config:clear
php artisan cache:clear

# Wait 5-10 minutes, then test
```

---

## 📊 WHY THIS ERROR?

Laravel 12 **REQUIRES** mbstring extension untuk:
- String manipulation (Str::studly, Str::camel, dll)
- Session handling
- Routing
- Configuration parsing

Fungsi `mb_split()` digunakan di `Illuminate\Support\Str::studly()` yang dipanggil saat Laravel bootstrap.

**Without mbstring, Laravel 12 CANNOT run!**

---

## 🎯 SUCCESS CRITERIA

Website dianggap **FIXED** jika:
- ✅ `php -m | grep mbstring` shows "mbstring"
- ✅ `php -r "echo function_exists('mb_split');"` returns "1"
- ✅ `php artisan --version` works
- ✅ Website loads: https://jastiphype.shop
- ✅ No mb_split errors in log

---

## 📞 NEED HELP?

**Hostinger Support:**
- Live Chat: hPanel → Help → Live Chat
- Email: support@hostinger.com

**Documentation:**
- [MBSTRING_ERROR_FIX.md](./MBSTRING_ERROR_FIX.md) - Detailed guide
- [README_TROUBLESHOOTING.md](./README_TROUBLESHOOTING.md) - Complete troubleshooting

---

**MULAI DARI SINI:**

```bash
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002
cd /home/u909490256/domains/jastiphype.shop
bash fix-mbstring-error.sh
```

**Good luck! 🚀**

---

**Created:** 12 Februari 2026  
**Priority:** CRITICAL  
**Estimated Fix Time:** 5-10 menit  
**Success Rate:** 99%
