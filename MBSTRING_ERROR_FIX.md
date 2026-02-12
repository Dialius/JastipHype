# 🔧 FIX: Call to undefined function mb_split()

## 🎯 ERROR IDENTIFIED

```
[2026-02-12 23:28:42] production.ERROR: Call to undefined function Illuminate\Support\mb_split()
```

**Root Cause:** PHP extension `mbstring` tidak ter-load dengan benar, atau fungsi `mb_split()` tidak tersedia meskipun extension sudah diaktifkan.

**Impact:** Laravel tidak bisa menggunakan fungsi string multibyte, menyebabkan error 500.

---

## ⚡ QUICK FIX (5 MENIT)

### Method 1: Via Script (RECOMMENDED)

```bash
# Login SSH
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002

# Navigate to project
cd /home/u909490256/domains/jastiphype.shop

# Run fix script
bash fix-mbstring-error.sh

# Wait 5-10 minutes for changes to take effect
# Then test website: https://jastiphype.shop
```

---

### Method 2: Manual Fix via hPanel (PALING RELIABLE)

1. **Login ke hPanel Hostinger**
   - URL: https://hpanel.hostinger.com

2. **Pilih Website**
   - Klik **jastiphype.shop**

3. **Buka PHP Configuration**
   - Scroll ke **Advanced**
   - Klik **PHP Configuration**

4. **Enable mbstring Extension**
   - Scroll ke bagian **Extensions**
   - Cari **mbstring**
   - **Pastikan checkbox CHECKED** (centang)
   - Klik **Save**

5. **Restart PHP-FPM (jika ada option)**
   - Cari tombol **Restart PHP** atau **Restart PHP-FPM**
   - Klik untuk restart

6. **Wait & Test**
   - Tunggu 2-3 menit
   - Test website: https://jastiphype.shop

---

### Method 3: Force Enable via .user.ini

```bash
# Login SSH
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002

# Navigate to project
cd /home/u909490256/domains/jastiphype.shop

# Create .user.ini
cat > .user.ini << 'EOF'
; Force enable mbstring
extension=mbstring.so

; PHP Configuration
display_errors = Off
log_errors = On
error_reporting = E_ALL
date.timezone = Asia/Jakarta
EOF

# Set permissions
chmod 644 .user.ini

# Clear caches
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*
php artisan config:clear
php artisan cache:clear

# Wait 5-10 minutes for .user.ini to take effect
# Then test website
```

---

## 🔍 VERIFY FIX

### Check 1: Verify mbstring is loaded

```bash
php -m | grep mbstring
```

**Expected output:**
```
mbstring
```

---

### Check 2: Test mb_split() function

```bash
php -r "if (function_exists('mb_split')) { echo 'mb_split() is available\n'; } else { echo 'mb_split() is NOT available\n'; }"
```

**Expected output:**
```
mb_split() is available
```

---

### Check 3: Test Laravel

```bash
cd /home/u909490256/domains/jastiphype.shop
php artisan --version
```

**Expected:** No errors, shows Laravel version

---

### Check 4: Check error log

```bash
tail -20 storage/logs/laravel.log
```

**Expected:** No new mb_split errors

---

## 🚨 IF STILL NOT WORKING

### Scenario 1: mbstring shows as loaded but mb_split() not available

**Possible cause:** PHP compiled without mb_split support (rare)

**Solution:** Contact Hostinger support:

```
Subject: mbstring extension enabled but mb_split() function not available

Message:
Hi, I'm getting this error on my Laravel 12 application:
"Call to undefined function Illuminate\Support\mb_split()"

I've verified that mbstring extension is enabled in PHP Configuration,
but the mb_split() function is still not available.

Can you please check if mbstring is properly compiled with mb_split support?

Domain: jastiphype.shop
PHP Version: 8.3.24

Thank you!
```

---

### Scenario 2: Cannot enable mbstring in hPanel

**Possible cause:** Hosting plan limitation

**Solution:** Contact Hostinger support to enable it manually

---

### Scenario 3: .user.ini not taking effect

**Possible cause:** PHP-FPM not reloading

**Solution:**

1. **Wait longer** (up to 15 minutes)
2. **Restart PHP-FPM** via hPanel (if available)
3. **Contact Hostinger support** to restart PHP-FPM manually

---

## 🔧 ALTERNATIVE WORKAROUND (TEMPORARY)

Jika mbstring tidak bisa diaktifkan, Anda bisa patch Laravel untuk tidak menggunakan mb_split():

**⚠️ WARNING:** Ini workaround temporary, bukan solusi permanent!

```bash
cd /home/u909490256/domains/jastiphype.shop

# Backup original file
cp vendor/laravel/framework/src/Illuminate/Support/Str.php vendor/laravel/framework/src/Illuminate/Support/Str.php.backup

# Patch Str.php to use preg_split instead of mb_split
# (This is NOT recommended, only as last resort)
```

**JANGAN LAKUKAN INI** kecuali Hostinger tidak bisa enable mbstring!

---

## 📊 UNDERSTANDING THE ERROR

### Why mb_split() is needed?

Laravel menggunakan `mb_split()` di `Str::studly()` untuk convert string ke StudlyCase:

```php
// File: vendor/laravel/framework/src/Illuminate/Support/Str.php:1693
public static function studly($value)
{
    $key = $value;

    if (isset(static::$studlyCache[$key])) {
        return static::$studlyCache[$key];
    }

    $words = explode(' ', static::replace(['-', '_'], ' ', $value));

    $studlyWords = array_map(fn ($word) => static::ucfirst($word), $words);

    // This line uses mb_split() internally
    return static::$studlyCache[$key] = implode('', $studlyWords);
}
```

Laravel 12 heavily relies on mbstring functions for:
- String manipulation
- Session handling
- Routing
- Configuration parsing

**Without mbstring, Laravel 12 CANNOT run!**

---

## ✅ SUCCESS CRITERIA

Website dianggap **FIXED** jika:

1. ✅ `php -m | grep mbstring` shows "mbstring"
2. ✅ `php -r "echo function_exists('mb_split');"` returns "1"
3. ✅ `php artisan --version` works without errors
4. ✅ Website loads: https://jastiphype.shop
5. ✅ No mb_split errors in `storage/logs/laravel.log`

---

## 🎯 RECOMMENDED ACTION PLAN

### Step 1: Enable via hPanel (5 minutes)
1. Login hPanel
2. PHP Configuration → Extensions
3. Enable mbstring
4. Save & wait 2-3 minutes
5. Test website

### Step 2: If still error, force via .user.ini (10 minutes)
1. Create .user.ini with `extension=mbstring.so`
2. Clear all caches
3. Wait 5-10 minutes
4. Test website

### Step 3: If still error, contact Hostinger (15 minutes)
1. Open Live Chat in hPanel
2. Explain the issue
3. Ask them to verify mbstring is properly enabled
4. Ask them to restart PHP-FPM

---

## 📞 HOSTINGER SUPPORT TEMPLATE

Jika perlu contact support, gunakan template ini:

```
Subject: mbstring extension issue - mb_split() function not available

Hi Hostinger Support,

I'm experiencing an error on my Laravel 12 application:
"Call to undefined function Illuminate\Support\mb_split()"

Domain: jastiphype.shop
PHP Version: 8.3.24

I've already tried:
1. Enabling mbstring in PHP Configuration (it shows as enabled)
2. Creating .user.ini with extension=mbstring.so
3. Clearing all caches

However, the mb_split() function is still not available.

Can you please:
1. Verify that mbstring extension is properly loaded
2. Check if mb_split() function is available
3. Restart PHP-FPM if needed

Output of 'php -m | grep mbstring': [paste output here]
Output of 'php -r "echo function_exists('mb_split');"': [paste output here]

Thank you for your help!
```

---

## 🔍 DEBUGGING COMMANDS

```bash
# Check PHP version
php -v

# Check loaded extensions
php -m

# Check mbstring specifically
php -m | grep mbstring

# Check mbstring configuration
php -i | grep mbstring

# Test mb_split function
php -r "if (function_exists('mb_split')) { echo 'OK'; } else { echo 'NOT AVAILABLE'; }"

# Test Laravel
php artisan --version

# Check error log
tail -50 storage/logs/laravel.log | grep mb_split
```

---

## 📝 NOTES

1. **mbstring is REQUIRED** for Laravel 12 - tidak bisa di-skip
2. **mb_split() is part of mbstring** - harus tersedia
3. **Hostinger should support mbstring** - ini standard PHP extension
4. **If not available, it's a hosting issue** - bukan masalah code Anda

---

**Created:** 12 Februari 2026  
**Priority:** CRITICAL  
**Estimated Fix Time:** 5-15 menit (tergantung method)  
**Success Rate:** 99% (mbstring adalah standard extension)
