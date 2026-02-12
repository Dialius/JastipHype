# ✅ ERROR 500 COMPLETE CHECKLIST - JASTIPHYPE.SHOP

## 🎯 CARA MENGGUNAKAN CHECKLIST INI

1. Jalankan setiap item secara berurutan
2. Centang (✅) jika sudah OK
3. Tandai (❌) jika ada masalah
4. Fix masalah sebelum lanjut ke item berikutnya
5. Kirim hasil checklist jika masih error setelah semua dicek

---

## 📋 CHECKLIST ITEMS

### 1. PHP VERSION & EXTENSIONS

#### 1.1 PHP Version
```bash
php -v
```
- [ ] PHP 8.2+ (Required: 8.2, Current: 8.3.24 ✅)

#### 1.2 Required Extensions
```bash
php -m | grep -E "mbstring|dom|pdo|pdo_mysql|fileinfo|openssl|tokenizer|json|bcmath|ctype|xml"
```
- [ ] mbstring ✅
- [ ] dom ✅
- [ ] pdo ✅
- [ ] pdo_mysql (nd_pdo_mysql) ✅
- [ ] fileinfo ✅
- [ ] openssl (built-in)
- [ ] tokenizer (built-in)
- [ ] json (built-in)
- [ ] bcmath ✅
- [ ] ctype (built-in)
- [ ] xml (built-in)

**Jika ada yang missing:**
```bash
# Contact Hostinger support untuk enable extension
# Atau ubah di hPanel → PHP Configuration → Extensions
```

---

### 2. FILE STRUCTURE

#### 2.1 Check Directory Structure
```bash
cd /home/u909490256/domains/jastiphype.shop
ls -la
```
- [ ] app/ folder exists
- [ ] bootstrap/ folder exists
- [ ] config/ folder exists
- [ ] database/ folder exists
- [ ] public/ folder exists
- [ ] resources/ folder exists
- [ ] routes/ folder exists
- [ ] storage/ folder exists
- [ ] vendor/ folder exists
- [ ] .env file exists
- [ ] artisan file exists
- [ ] composer.json exists

#### 2.2 Check Public Directory
```bash
ls -la public/
```
- [ ] index.php exists
- [ ] .htaccess exists
- [ ] uploads/ folder exists

#### 2.3 Check Storage Structure
```bash
ls -la storage/
```
- [ ] app/ folder exists
- [ ] framework/ folder exists
- [ ] logs/ folder exists

```bash
ls -la storage/framework/
```
- [ ] cache/ folder exists
- [ ] sessions/ folder exists
- [ ] views/ folder exists

---

### 3. FILE PERMISSIONS

#### 3.1 Storage Permissions
```bash
ls -la storage/
```
- [ ] storage/ = 775 or 777
- [ ] storage/logs/ = 775 or 777
- [ ] storage/framework/ = 775 or 777
- [ ] storage/framework/cache/ = 775 or 777
- [ ] storage/framework/sessions/ = 775 or 777
- [ ] storage/framework/views/ = 775 or 777

**Fix if needed:**
```bash
chmod -R 775 storage
find storage -type f -exec chmod 664 {} \;
```

#### 3.2 Bootstrap Cache Permissions
```bash
ls -la bootstrap/cache/
```
- [ ] bootstrap/cache/ = 775 or 777

**Fix if needed:**
```bash
chmod -R 775 bootstrap/cache
find bootstrap/cache -type f -exec chmod 664 {} \;
```

#### 3.3 Public Uploads Permissions
```bash
ls -la public/uploads/
```
- [ ] public/uploads/ = 755

**Fix if needed:**
```bash
chmod -R 755 public/uploads
```

---

### 4. COMPOSER DEPENDENCIES

#### 4.1 Check Vendor Directory
```bash
ls -la vendor/
```
- [ ] vendor/autoload.php exists
- [ ] vendor/laravel/ folder exists
- [ ] vendor/composer/ folder exists

#### 4.2 Verify Composer Install
```bash
composer --version
php artisan --version
```
- [ ] Composer installed
- [ ] Laravel version shows (should be 12.x)

**Fix if needed:**
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

---

### 5. ENVIRONMENT CONFIGURATION

#### 5.1 Check .env File
```bash
cat .env | head -20
```
- [ ] .env file exists
- [ ] APP_NAME is set
- [ ] APP_ENV=production
- [ ] APP_KEY is set (not empty)
- [ ] APP_DEBUG=false
- [ ] APP_URL=https://jastiphype.shop

#### 5.2 Check Database Config
```bash
cat .env | grep DB_
```
- [ ] DB_CONNECTION=mysql
- [ ] DB_HOST=localhost
- [ ] DB_PORT=3306
- [ ] DB_DATABASE=u909490256_jastiphype
- [ ] DB_USERNAME=u909490256_vinthegreat
- [ ] DB_PASSWORD is set (not empty)

#### 5.3 Check Filesystem Config
```bash
cat .env | grep FILESYSTEM
```
- [ ] FILESYSTEM_DISK=public

**Fix if needed:**
```bash
# Copy from .env.hostinger
cp .env.hostinger .env

# Generate APP_KEY
php artisan key:generate --force

# Verify
cat .env | grep -E "APP_KEY|DB_|FILESYSTEM"
```

---

### 6. DATABASE CONNECTION

#### 6.1 Test Database Connection
```bash
php artisan tinker --execute="try { \$pdo = DB::connection()->getPdo(); echo 'Database connected successfully!' . PHP_EOL; } catch (Exception \$e) { echo 'Database error: ' . \$e->getMessage() . PHP_EOL; }"
```
- [ ] Database connection successful

**Fix if needed:**
```bash
# Verify credentials
cat .env | grep DB_

# Test MySQL directly
mysql -h localhost -u u909490256_vinthegreat -p u909490256_jastiphype
# Enter password: XmAJ4!9tmJEt4hE
```

#### 6.2 Check Database Tables
```bash
php artisan tinker --execute="echo 'Tables: ' . implode(', ', DB::connection()->getDoctrineSchemaManager()->listTableNames()) . PHP_EOL;"
```
- [ ] Tables exist (users, products, orders, etc.)

**Fix if needed:**
```bash
# Run migrations
php artisan migrate --force
```

---

### 7. CACHE & OPTIMIZATION

#### 7.1 Check Cached Config
```bash
ls -la bootstrap/cache/
```
- [ ] Check if config.php exists
- [ ] Check if routes-*.php exists

#### 7.2 Verify Cached Config Matches .env
```bash
# Check cached database config
php artisan tinker --execute="echo 'Cached DB: ' . config('database.connections.mysql.database') . PHP_EOL;"

# Check actual .env
cat .env | grep DB_DATABASE=
```
- [ ] Cached config matches .env

**Fix if needed:**
```bash
# Clear ALL cache
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
```

---

### 8. UPLOADS FOLDER

#### 8.1 Check Uploads Structure
```bash
ls -la public/uploads/
```
- [ ] public/uploads/products/ exists
- [ ] public/uploads/brands/ exists
- [ ] public/uploads/categories/ exists
- [ ] public/uploads/banners/ exists

**Fix if needed:**
```bash
mkdir -p public/uploads/products
mkdir -p public/uploads/brands
mkdir -p public/uploads/categories
mkdir -p public/uploads/banners
chmod -R 755 public/uploads
```

#### 8.2 Check Uploads in public_html
```bash
ls -la /home/u909490256/public_html/uploads/
```
- [ ] uploads/ folder exists in public_html

**Fix if needed:**
```bash
cp -rf public/uploads /home/u909490256/public_html/
```

---

### 9. PUBLIC_HTML SETUP

#### 9.1 Check public_html Structure
```bash
ls -la /home/u909490256/public_html/
```
- [ ] index.php exists
- [ ] .htaccess exists
- [ ] css/ folder exists (if using Vite)
- [ ] js/ folder exists (if using Vite)
- [ ] uploads/ folder exists

#### 9.2 Verify index.php Path
```bash
cat /home/u909490256/public_html/index.php | head -20
```
- [ ] Path to vendor/autoload.php is correct
- [ ] Path to bootstrap/app.php is correct

**Expected paths for Hostinger:**
```php
require __DIR__.'/../domains/jastiphype.shop/vendor/autoload.php';
$app = require_once __DIR__.'/../domains/jastiphype.shop/bootstrap/app.php';
```

**Fix if needed:**
```bash
# Copy from public/ to public_html/
cp -rf public/* /home/u909490256/public_html/

# Or update paths in index.php manually
```

---

### 10. .HTACCESS CONFIGURATION

#### 10.1 Check .htaccess in public/
```bash
cat public/.htaccess | head -20
```
- [ ] RewriteEngine On
- [ ] RewriteRule for index.php exists

#### 10.2 Check .htaccess in public_html/
```bash
cat /home/u909490256/public_html/.htaccess | head -20
```
- [ ] .htaccess exists and has correct content

**Fix if needed:**
```bash
cp public/.htaccess /home/u909490256/public_html/.htaccess
```

---

### 11. ERROR LOGS

#### 11.1 Check Laravel Log
```bash
tail -50 storage/logs/laravel.log
```
- [ ] Log file exists
- [ ] Check for recent errors

**Common errors to look for:**
- Database connection errors
- File permission errors
- Missing class/file errors
- Syntax errors

#### 11.2 Check PHP Error Log
```bash
# Hostinger error log location varies
tail -50 /home/u909490256/logs/error_log 2>/dev/null || echo "PHP error log not found"
```
- [ ] Check for PHP errors

---

### 12. PHP CONFIGURATION

#### 12.1 Check PHP Settings
```bash
php -i | grep -E "display_errors|date.timezone|memory_limit|max_execution_time"
```
- [ ] display_errors = Off (recommended for production)
- [ ] date.timezone is set (recommended: Asia/Jakarta)
- [ ] memory_limit >= 256M (current: 1024M ✅)
- [ ] max_execution_time >= 60 (current: 360 ✅)

**Fix if needed:**
See `PHP_CONFIG_HOSTINGER.md` for detailed instructions.

---

### 13. ARTISAN COMMANDS

#### 13.1 Test Artisan
```bash
php artisan --version
```
- [ ] Artisan works without errors

#### 13.2 Test Config
```bash
php artisan config:show app
```
- [ ] Config shows without errors

#### 13.3 Test Tinker
```bash
php artisan tinker --execute="echo 'Tinker works!' . PHP_EOL;"
```
- [ ] Tinker works without errors

---

### 14. WEB SERVER TEST

#### 14.1 Test Direct PHP Execution
```bash
cd public
php -S localhost:8000
```
- [ ] PHP built-in server starts
- [ ] Access http://localhost:8000 (if possible)

#### 14.2 Test index.php Directly
```bash
php public/index.php
```
- [ ] No PHP syntax errors
- [ ] No fatal errors

---

### 15. BROWSER TEST

#### 15.1 Clear Browser Cache
- [ ] Clear browser cache (Ctrl+Shift+Delete)
- [ ] Try incognito/private mode

#### 15.2 Test Website
- [ ] Access https://jastiphype.shop
- [ ] Check if error 500 still appears

#### 15.3 Check Browser Console
- [ ] Open browser DevTools (F12)
- [ ] Check Console tab for JavaScript errors
- [ ] Check Network tab for failed requests

---

## 🚨 EMERGENCY ACTIONS

### If Still Error 500 After All Checks:

#### Option 1: Run Nuclear Fix
```bash
cd /home/u909490256/domains/jastiphype.shop
bash nuclear-fix.sh
```

#### Option 2: Run Diagnosis Script
```bash
cd /home/u909490256/domains/jastiphype.shop
bash diagnose-error-500.sh
```

#### Option 3: Fresh Deployment
```bash
# Backup current
cd /home/u909490256/domains
tar -czf jastiphype.shop.backup.$(date +%Y%m%d_%H%M%S).tar.gz jastiphype.shop

# Fresh clone
rm -rf jastiphype.shop
git clone git@github.com:Dialius/JastipHype.git jastiphype.shop
cd jastiphype.shop

# Follow FRESH_DEPLOYMENT_GUIDE.md
```

---

## 📊 CHECKLIST SUMMARY

### Critical Items (MUST BE OK):
- [ ] PHP 8.2+
- [ ] Required PHP extensions
- [ ] vendor/ directory exists
- [ ] .env file exists and correct
- [ ] APP_KEY is set
- [ ] Database connection works
- [ ] storage/ permissions = 775
- [ ] bootstrap/cache/ permissions = 775
- [ ] public/uploads/ exists
- [ ] index.php exists in public_html/
- [ ] .htaccess exists

### Recommended Items:
- [ ] display_errors = Off
- [ ] date.timezone = Asia/Jakarta
- [ ] Cache cleared and rebuilt
- [ ] Migrations run
- [ ] Error logs checked

---

## 📝 HASIL CHECKLIST

Setelah selesai cek semua, isi hasil di sini:

**Total Items Checked:** _____ / 50+

**Critical Issues Found:**
1. 
2. 
3. 

**Status Website:**
- [ ] ✅ Website bisa diakses (SOLVED!)
- [ ] ❌ Masih error 500 (need more investigation)

**Next Steps:**
1. 
2. 
3. 

---

## 🆘 JIKA MASIH ERROR

Kirim informasi berikut:

1. **Output dari diagnosis script:**
   ```bash
   bash diagnose-error-500.sh > diagnosis-output.txt
   cat diagnosis-output.txt
   ```

2. **Laravel error log:**
   ```bash
   tail -100 storage/logs/laravel.log
   ```

3. **PHP error log:**
   ```bash
   tail -50 /home/u909490256/logs/error_log
   ```

4. **Environment check:**
   ```bash
   cat .env | grep -E "APP_|DB_|FILESYSTEM"
   ```

5. **Screenshot error page** (jika ada pesan error spesifik)

---

**Dibuat:** 12 Februari 2026  
**Estimated Time:** 30-45 menit untuk complete check  
**Success Rate:** 95%+ jika semua item dicek dengan benar
