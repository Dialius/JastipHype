# 🚀 START HERE - MANUAL DEPLOY TANPA SSH

## 🎯 SITUASI SAAT INI

- ❌ Tidak bisa akses SSH
- ✅ Punya akses Hostinger hPanel
- ✅ Repository GitHub: https://github.com/Dialius/JastipHype
- 🚨 Website error 500: `Call to undefined function mb_split()`

---

## ⚡ SOLUSI CEPAT (2 CARA)

### CARA 1: Via Hostinger Web Terminal (RECOMMENDED - 15 menit)

Hostinger punya **Web Terminal** di hPanel yang bisa digunakan seperti SSH!

#### Step 1: Buka Web Terminal

1. Login **hPanel**: https://hpanel.hostinger.com
2. Pilih **jastiphype.shop**
3. Scroll ke **Advanced**
4. Klik **Web Terminal** atau **SSH Access**
5. Klik **Open Web Terminal**

Terminal akan terbuka di browser!

#### Step 2: Pull Latest Code

```bash
cd /home/u909490256/domains/jastiphype.shop
git pull origin master
```

#### Step 3: Install Dependencies

```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

#### Step 4: Setup Environment

```bash
cp .env.hostinger .env
php artisan key:generate --force
```

#### Step 5: Fix Permissions

```bash
chmod -R 775 storage bootstrap/cache
chmod -R 755 public/uploads
```

#### Step 6: Clear Cache

```bash
rm -rf bootstrap/cache/*.php
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

#### Step 7: Force PHP 8.3

```bash
cat > /home/u909490256/public_html/.htaccess.new << 'EOF'
# Force PHP 8.3
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php83 .php .php8 .phtml
</IfModule>

EOF

cat /home/u909490256/public_html/.htaccess >> /home/u909490256/public_html/.htaccess.new
mv /home/u909490256/public_html/.htaccess.new /home/u909490256/public_html/.htaccess
```

#### Step 8: Copy to public_html

```bash
cp -rf public/* /home/u909490256/public_html/
```

#### Step 9: Test

Wait 2-3 minutes, then open: **https://jastiphype.shop**

---

### CARA 2: Via File Manager (JIKA WEB TERMINAL TIDAK ADA - 45 menit)

Lihat panduan lengkap di: **MANUAL_DEPLOY_VIA_FILE_MANAGER.md**

**Ringkasan:**
1. Download ZIP dari GitHub
2. Upload via File Manager
3. Extract files
4. Setup .env
5. Set permissions
6. Copy to public_html
7. Update .htaccess
8. **CRITICAL:** Minta Hostinger support run `composer install`

---

## 🔍 CARA CEK WEB TERMINAL ADA ATAU TIDAK

1. Login hPanel
2. Pilih jastiphype.shop
3. Scroll ke **Advanced** section
4. Cari salah satu dari:
   - **Web Terminal**
   - **SSH Access** (dengan tombol "Open Web Terminal")
   - **Terminal**
   - **Online Terminal**

Jika ada salah satu, gunakan **CARA 1** (lebih mudah!)

Jika tidak ada, gunakan **CARA 2** (via File Manager)

---

## 🎯 KENAPA ERROR 500?

**Root Cause:** PHP environment mismatch

- mbstring enabled di hPanel PHP 8.3
- Tapi web server pakai PHP handler berbeda
- Solusi: Force PHP 8.3 via .htaccess

**Fix:** Tambahkan ini di `.htaccess`:
```apache
# Force PHP 8.3
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php83 .php .php8 .phtml
</IfModule>
```

---

## 📁 FILES YANG SUDAH SAYA BUAT

Semua file troubleshooting sudah di-commit ke GitHub:

1. **START_HERE_MANUAL_DEPLOY.md** ⭐⭐⭐ (file ini)
2. **MANUAL_DEPLOY_VIA_FILE_MANAGER.md** ⭐⭐ - Panduan lengkap deploy manual
3. **FIX_NOW.md** ⭐ - Quick fix guide
4. **check-php-environment.sh** - Check PHP mismatch
5. **extract-full-error.sh** - Extract error details
6. **fix-mbstring-error.sh** - Fix mbstring
7. **diagnose-error-500.sh** - General diagnosis
8. **nuclear-fix.sh** - All-in-one fix
9. **PHP_ENVIRONMENT_MISMATCH_FIX.md** - Mismatch fix guide
10. **MBSTRING_ERROR_FIX.md** - mbstring guide
11. **README_TROUBLESHOOTING.md** - Master index
12. **RINGKASAN_UNTUK_ANDA.md** - Indonesian summary
13. **TROUBLESHOOTING_FLOWCHART.md** - Visual guide
14. **HOSTINGER_ERROR_500_DIAGNOSIS.md** - Deep analysis
15. **ERROR_500_COMPLETE_CHECKLIST.md** - Complete checklist

---

## 🆘 JIKA MASIH BINGUNG

### Option 1: Contact Hostinger Support

**Live Chat di hPanel:**

```
Hi, I need help with my Laravel application deployment.

Can you please help me:
1. Open Web Terminal for my account
2. Or run these commands for me:
   cd /home/u909490256/domains/jastiphype.shop
   git pull origin master
   composer install --no-dev --optimize-autoloader
   php artisan key:generate --force
   php artisan config:cache
   cp -rf public/* /home/u909490256/public_html/

Domain: jastiphype.shop

Thank you!
```

### Option 2: Kirim Info ke Saya

Kirim screenshot dari:
1. hPanel → Advanced section (untuk cek ada Web Terminal atau tidak)
2. Error message terbaru dari website

---

## ✅ SUCCESS CRITERIA

Website dianggap **FIXED** jika:
- ✅ Website loads: https://jastiphype.shop
- ✅ No error 500
- ✅ Homepage muncul
- ✅ Login/register berfungsi

---

## 🎯 NEXT STEPS

**PILIH SALAH SATU:**

### Jika Ada Web Terminal:
1. Buka Web Terminal di hPanel
2. Follow **CARA 1** di atas
3. Test website

### Jika Tidak Ada Web Terminal:
1. Buka **MANUAL_DEPLOY_VIA_FILE_MANAGER.md**
2. Follow step-by-step
3. Contact Hostinger support untuk run `composer install`
4. Test website

---

**Good luck! 🚀**

---

**Created:** 12 Februari 2026  
**Priority:** CRITICAL  
**Estimated Time:** 15-45 menit (tergantung method)
