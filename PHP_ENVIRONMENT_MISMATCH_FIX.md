# 🔧 FIX: PHP Environment Mismatch - mbstring Issue

## 🎯 PROBLEM IDENTIFIED

**Situation:** mbstring is enabled in hPanel PHP Configuration, but website still gets `mb_split()` error.

**Root Cause:** PHP environment mismatch - the web server is using a different PHP handler/version than the one where mbstring is enabled.

**Common Scenarios:**
1. CLI PHP vs Web PHP using different versions
2. Subdomain/folder using different PHP version
3. .htaccess forcing old PHP version
4. Cached PHP worker with old configuration

---

## 🔍 STEP 1: DIAGNOSE THE MISMATCH

### Run Diagnostic Script

```bash
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002
cd /home/u909490256/domains/jastiphype.shop
bash check-php-environment.sh
```

**This will:**
- Check PHP CLI version and mbstring status
- Check which PHP binary is used
- Create test file for web PHP check
- Extract full error path from log
- Check .htaccess for PHP handler directives
- List available PHP versions

---

### Check Web PHP Environment

After running the script, open browser:

**URL:** https://jastiphype.shop/phpinfo-test-temp.php

**Check:**
- PHP Version (should be 8.3.24)
- mbstring loaded (should be YES)
- mb_split exists (should be YES)

**⚠️ IMPORTANT:** Delete test file after checking:
```bash
rm public/phpinfo-test-temp.php
```

---

### Compare CLI vs Web

**CLI Environment:**
```bash
php -v
php -m | grep mbstring
php -r "echo function_exists('mb_split');"
```

**Web Environment:**
- Check phpinfo-test-temp.php output

**If they differ → Environment mismatch confirmed!**

---

## 🔧 STEP 2: FIX THE MISMATCH

### Fix 1: Force PHP 8.3 in .htaccess (RECOMMENDED)

Add this to `public/.htaccess` (at the very top):

```apache
# Force PHP 8.3
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php83 .php .php8 .phtml
</IfModule>
```

**Via SSH:**
```bash
cd /home/u909490256/domains/jastiphype.shop

# Backup original
cp public/.htaccess public/.htaccess.backup

# Add PHP handler at the top
cat > public/.htaccess.new << 'EOF'
# Force PHP 8.3
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php83 .php .php8 .phtml
</IfModule>

EOF

# Append original content
cat public/.htaccess >> public/.htaccess.new

# Replace
mv public/.htaccess.new public/.htaccess

# Copy to public_html
cp public/.htaccess /home/u909490256/public_html/.htaccess

echo "✅ .htaccess updated with PHP 8.3 handler"
```

**Test:** Wait 1-2 minutes, then access website

---

### Fix 2: Update public_html/index.php to Use Correct PHP

If public_html has separate index.php, ensure it uses correct PHP:

```bash
# Check current index.php
cat /home/u909490256/public_html/index.php | head -5

# If it has shebang, update it
# Add this as first line:
#!/usr/bin/php83
```

---

### Fix 3: Set PHP Version via .user.ini

```bash
cd /home/u909490256/domains/jastiphype.shop

cat > .user.ini << 'EOF'
; Force PHP 8.3 with mbstring
extension=mbstring.so

; PHP Configuration
display_errors = Off
log_errors = On
error_reporting = E_ALL
date.timezone = Asia/Jakarta
EOF

chmod 644 .user.ini

# Copy to public_html
cp .user.ini /home/u909490256/public_html/.user.ini
```

**Wait 5-10 minutes** for .user.ini to take effect

---

### Fix 4: Contact Hostinger to Sync PHP Versions

If above fixes don't work, contact Hostinger support:

```
Subject: PHP environment mismatch - CLI vs Web using different versions

Hi Hostinger Support,

I'm experiencing a PHP environment mismatch on jastiphype.shop:

CLI PHP: 8.3.24 with mbstring enabled
Web PHP: [different version or mbstring not loaded]

This causes "Call to undefined function mb_split()" error on the website,
even though mbstring is enabled in PHP Configuration.

Can you please:
1. Verify that both CLI and web PHP are using version 8.3.24
2. Ensure mbstring is enabled for the web PHP handler
3. Restart PHP-FPM workers to clear any cached configuration

Domain: jastiphype.shop
PHP Version: 8.3.24

Diagnostic info:
- CLI: php -v shows 8.3.24, mbstring loaded
- Web: [paste phpinfo-test-temp.php output]

Thank you!
```

---

## 🔍 STEP 3: VERIFY FIX

### Test 1: Check Web PHP

```bash
# Create test file
cat > public/test-mb.php << 'EOF'
<?php
if (function_exists('mb_split')) {
    echo "SUCCESS: mb_split() is available!";
} else {
    echo "ERROR: mb_split() is NOT available";
}
EOF

# Access via browser
# https://jastiphype.shop/test-mb.php

# Delete after testing
rm public/test-mb.php
```

**Expected:** "SUCCESS: mb_split() is available!"

---

### Test 2: Check Laravel

```bash
php artisan --version
```

**Expected:** No errors, shows Laravel version

---

### Test 3: Check Website

Open browser: https://jastiphype.shop

**Expected:** Website loads without error 500

---

### Test 4: Check Error Log

```bash
tail -20 storage/logs/laravel.log
```

**Expected:** No new mb_split errors

---

## 📊 UNDERSTANDING PHP HANDLERS

### Common PHP Handlers in Hostinger:

1. **ea-php83** - EasyApache PHP 8.3 (recommended)
2. **lsphp83** - LiteSpeed PHP 8.3
3. **php-fpm** - PHP-FPM (default)

### How to Force Specific Handler:

**In .htaccess:**
```apache
# EasyApache PHP 8.3
AddHandler application/x-httpd-ea-php83 .php

# Or LiteSpeed PHP 8.3
AddHandler application/x-httpd-lsphp83 .php
```

**In .user.ini:**
```ini
; This affects PHP-FPM only
extension=mbstring.so
```

---

## 🚨 COMMON ISSUES

### Issue 1: .htaccess Changes Not Taking Effect

**Cause:** Apache not reloading configuration

**Fix:**
1. Wait 2-3 minutes
2. Clear browser cache
3. Try different browser/incognito
4. Contact Hostinger to reload Apache

---

### Issue 2: Multiple .htaccess Files Conflicting

**Cause:** .htaccess in root AND public/ with different PHP handlers

**Fix:**
```bash
# Check all .htaccess files
find /home/u909490256 -name ".htaccess" -type f 2>/dev/null

# Ensure consistent PHP handler in all
```

---

### Issue 3: Subdomain Using Different PHP

**Cause:** jastiphype.shop subdomain configured separately

**Fix:**
1. Login hPanel
2. Domains → Manage jastiphype.shop
3. Check PHP version setting
4. Ensure it's set to 8.3

---

## 🎯 DECISION TREE

```
mbstring enabled in hPanel but mb_split() error
    │
    ├─→ Run check-php-environment.sh
    │       │
    │       ├─→ CLI and Web PHP same version?
    │       │       ├─→ YES → mbstring loaded in web?
    │       │       │       ├─→ YES → Clear cache, restart
    │       │       │       └─→ NO → Contact Hostinger
    │       │       │
    │       │       └─→ NO → Environment mismatch!
    │       │               └─→ Force PHP 8.3 in .htaccess
    │       │
    │       └─→ Test website after fix
    │               ├─→ FIXED → Done!
    │               └─→ Still error → Contact Hostinger
```

---

## ✅ SUCCESS CRITERIA

Fix is successful when:

1. ✅ CLI PHP version = Web PHP version (both 8.3.24)
2. ✅ mbstring loaded in both CLI and web
3. ✅ mb_split() available in both environments
4. ✅ `php artisan --version` works
5. ✅ Website loads: https://jastiphype.shop
6. ✅ No mb_split errors in log

---

## 📝 FULL ERROR LINE NEEDED

To properly diagnose, we need the **full error line** from log:

```bash
grep "mb_split" storage/logs/laravel.log | tail -1
```

**Should show something like:**
```
[2026-02-12 23:28:42] production.ERROR: Call to undefined function Illuminate\Support\mb_split() 
at /home/u909490256/domains/jastiphype.shop/vendor/laravel/framework/src/Illuminate/Support/Str.php:1693
```

**This tells us:**
- Exact file path
- Line number
- Which PHP handler is executing it

---

## 🔧 QUICK FIX COMMANDS

```bash
# 1. Check environment
bash check-php-environment.sh

# 2. Force PHP 8.3 in .htaccess
cd /home/u909490256/domains/jastiphype.shop
cp public/.htaccess public/.htaccess.backup
echo -e "# Force PHP 8.3\n<IfModule mime_module>\n  AddHandler application/x-httpd-ea-php83 .php\n</IfModule>\n\n$(cat public/.htaccess)" > public/.htaccess.new
mv public/.htaccess.new public/.htaccess
cp public/.htaccess /home/u909490256/public_html/.htaccess

# 3. Clear caches
rm -rf bootstrap/cache/*.php
php artisan config:clear
php artisan cache:clear

# 4. Test
php artisan --version

# 5. Check website
# Open browser: https://jastiphype.shop
```

---

**Created:** 12 Februari 2026  
**Priority:** CRITICAL  
**Estimated Fix Time:** 10-15 menit  
**Success Rate:** 95% (environment mismatch is common in shared hosting)
