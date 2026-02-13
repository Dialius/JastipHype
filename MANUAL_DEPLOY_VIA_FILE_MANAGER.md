# 📦 MANUAL DEPLOY VIA HOSTINGER FILE MANAGER

## 🎯 OVERVIEW

Panduan ini untuk deploy Laravel dari GitHub ke Hostinger **tanpa SSH**, menggunakan File Manager dan terminal web Hostinger.

---

## 📋 PREREQUISITES

1. ✅ Repository GitHub: https://github.com/Dialius/JastipHype
2. ✅ Hostinger account dengan akses hPanel
3. ✅ Database sudah dibuat di Hostinger

---

## 🚀 METHOD 1: VIA HOSTINGER WEB TERMINAL (RECOMMENDED)

### Step 1: Login hPanel & Open Web Terminal

1. Login ke **hPanel Hostinger**: https://hpanel.hostinger.com
2. Pilih website **jastiphype.shop**
3. Scroll ke **Advanced** section
4. Klik **Web Terminal** atau **SSH Access**
5. Klik **Open Web Terminal** (terminal akan terbuka di browser)

---

### Step 2: Navigate & Backup Current Files

```bash
# Navigate to domain folder
cd /home/u909490256/domains/jastiphype.shop

# Backup current installation (optional)
tar -czf backup_$(date +%Y%m%d_%H%M%S).tar.gz . --exclude='backup_*.tar.gz'

# List files
ls -la
```

---

### Step 3: Pull Latest Code from GitHub

```bash
# If git is already initialized
git status

# Pull latest changes
git pull origin master

# Or if need to reset everything
git fetch origin
git reset --hard origin/master
```

**If git not initialized:**
```bash
# Remove old files (CAREFUL!)
rm -rf * .git .gitignore

# Clone fresh
git clone https://github.com/Dialius/JastipHype.git .
```

---

### Step 4: Install Dependencies

```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Verify
ls -la vendor/
```

---

### Step 5: Setup Environment

```bash
# Copy .env.hostinger to .env
cp .env.hostinger .env

# Generate APP_KEY if needed
php artisan key:generate --force

# Verify
cat .env | grep -E "APP_KEY|DB_DATABASE"
```

---

### Step 6: Create Required Folders

```bash
# Create uploads folders
mkdir -p public/uploads/products
mkdir -p public/uploads/brands
mkdir -p public/uploads/categories
mkdir -p public/uploads/banners

# Create storage folders
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set permissions
chmod -R 775 storage bootstrap/cache
chmod -R 755 public/uploads
```

---

### Step 7: Clear & Rebuild Cache

```bash
# Clear all caches
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*

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

### Step 8: Run Migrations

```bash
# Run migrations
php artisan migrate --force

# Verify
php artisan tinker --execute="echo 'Tables: ' . implode(', ', DB::connection()->getDoctrineSchemaManager()->listTableNames()) . PHP_EOL;"
```

---

### Step 9: Copy to public_html

```bash
# Copy public files to public_html
cp -rf public/* /home/u909490256/public_html/

# Verify
ls -la /home/u909490256/public_html/
```

---

### Step 10: Force PHP 8.3 in .htaccess

```bash
# Add PHP handler to .htaccess
cat > /home/u909490256/public_html/.htaccess.new << 'EOF'
# Force PHP 8.3
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php83 .php .php8 .phtml
</IfModule>

EOF

# Append original content
cat /home/u909490256/public_html/.htaccess >> /home/u909490256/public_html/.htaccess.new

# Replace
mv /home/u909490256/public_html/.htaccess.new /home/u909490256/public_html/.htaccess

echo "✅ .htaccess updated with PHP 8.3 handler"
```

---

### Step 11: Test Website

Wait 2-3 minutes, then open browser:

**URL:** https://jastiphype.shop

**Expected:** Website loads without error 500

---

## 🚀 METHOD 2: VIA FILE MANAGER (IF WEB TERMINAL NOT AVAILABLE)

### Step 1: Download Repository from GitHub

1. Go to: https://github.com/Dialius/JastipHype
2. Click **Code** → **Download ZIP**
3. Save to your computer: `JastipHype-master.zip`

---

### Step 2: Login Hostinger File Manager

1. Login to **hPanel Hostinger**
2. Click **File Manager**
3. Navigate to `/home/u909490256/domains/jastiphype.shop`

---

### Step 3: Backup Current Files

1. Select all files in `jastiphype.shop` folder
2. Right click → **Compress**
3. Name: `backup_old_files.zip`
4. Click **Compress**
5. Download `backup_old_files.zip` to your computer (for safety)

---

### Step 4: Delete Old Files

1. Select all files EXCEPT `backup_old_files.zip`
2. Right click → **Delete**
3. Confirm deletion

---

### Step 5: Upload New Files

1. Click **Upload** button
2. Select `JastipHype-master.zip`
3. Wait for upload to complete
4. Right click on `JastipHype-master.zip` → **Extract**
5. Files will be extracted to `JastipHype-master` folder

---

### Step 6: Move Files to Root

1. Open `JastipHype-master` folder
2. Select all files inside
3. Right click → **Move**
4. Destination: `/home/u909490256/domains/jastiphype.shop`
5. Click **Move**
6. Delete empty `JastipHype-master` folder

---

### Step 7: Setup .env File

1. Find file `.env.hostinger`
2. Right click → **Copy**
3. Paste in same folder
4. Rename copy to `.env`
5. Right click `.env` → **Edit**
6. Verify these values:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://jastiphype.shop
   DB_DATABASE=u909490256_jastiphype
   DB_USERNAME=u909490256_vinthegreat
   DB_PASSWORD=XmAJ4!9tmJEt4hE
   FILESYSTEM_DISK=public
   ```
7. Save

---

### Step 8: Create Required Folders

**Via File Manager:**

1. Navigate to `public` folder
2. Create folder: `uploads`
3. Inside `uploads`, create folders:
   - `products`
   - `brands`
   - `categories`
   - `banners`

4. Navigate to `storage/framework`
5. Create folders if not exist:
   - `cache/data`
   - `sessions`
   - `views`

6. Navigate to `storage`
7. Create folder: `logs`

---

### Step 9: Set Permissions

**For each folder, right click → Permissions:**

- `storage/` → **775** (rwxrwxr-x)
- `storage/logs/` → **775**
- `storage/framework/` → **775**
- `storage/framework/cache/` → **775**
- `storage/framework/sessions/` → **775**
- `storage/framework/views/` → **775**
- `bootstrap/cache/` → **775**
- `public/uploads/` → **755** (rwxr-xr-x)

---

### Step 10: Copy to public_html

1. Navigate to `public` folder
2. Select all files inside `public`
3. Right click → **Copy**
4. Navigate to `/home/u909490256/public_html`
5. Paste files
6. Confirm overwrite

---

### Step 11: Update .htaccess in public_html

1. Navigate to `/home/u909490256/public_html`
2. Find `.htaccess` file
3. Right click → **Edit**
4. Add these lines at the **very top** (before everything else):

```apache
# Force PHP 8.3
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php83 .php .php8 .phtml
</IfModule>
```

5. Save

---

### Step 12: Install Composer Dependencies (CRITICAL!)

**⚠️ This step REQUIRES Web Terminal or SSH!**

If you can't access terminal, you have 2 options:

**Option A: Use Hostinger Web Terminal**
1. hPanel → Advanced → Web Terminal
2. Run:
```bash
cd /home/u909490256/domains/jastiphype.shop
composer install --no-dev --optimize-autoloader --no-interaction
```

**Option B: Contact Hostinger Support**
Ask them to run:
```
cd /home/u909490256/domains/jastiphype.shop
composer install --no-dev --optimize-autoloader --no-interaction
php artisan key:generate --force
php artisan migrate --force
php artisan config:cache
```

---

### Step 13: Test Website

Wait 2-3 minutes, then open browser:

**URL:** https://jastiphype.shop

---

## 🚨 TROUBLESHOOTING

### Issue 1: "vendor/autoload.php not found"

**Cause:** Composer dependencies not installed

**Fix:** MUST run `composer install` via Web Terminal or contact Hostinger support

---

### Issue 2: "No application encryption key"

**Cause:** APP_KEY not generated

**Fix via File Manager:**
1. Open `.env` file
2. Find line: `APP_KEY=`
3. Replace with: `APP_KEY=base64:Ksc82+I7kMwWoOGGzSFWV/VvTND1VcXZQQG5v5FVWUI=`
4. Save

---

### Issue 3: Still Error 500

**Check:**
1. Permissions correct? (storage=775, uploads=755)
2. .env file exists and correct?
3. Composer dependencies installed?
4. .htaccess has PHP 8.3 handler?

**Get help:**
1. Check error log: `storage/logs/laravel.log`
2. Contact Hostinger support with error message

---

## 📊 VERIFICATION CHECKLIST

After deployment, verify:

- [ ] All files uploaded to `/home/u909490256/domains/jastiphype.shop`
- [ ] `.env` file exists with correct values
- [ ] `vendor/` folder exists (after composer install)
- [ ] `storage/` permissions = 775
- [ ] `bootstrap/cache/` permissions = 775
- [ ] `public/uploads/` exists with subfolders
- [ ] Files copied to `/home/u909490256/public_html/`
- [ ] `.htaccess` in public_html has PHP 8.3 handler
- [ ] Website loads: https://jastiphype.shop

---

## 🎯 QUICK REFERENCE

### Folder Structure After Deploy:

```
/home/u909490256/domains/jastiphype.shop/
├── app/
├── bootstrap/
│   └── cache/ (775)
├── config/
├── database/
├── public/
│   ├── uploads/ (755)
│   │   ├── products/
│   │   ├── brands/
│   │   ├── categories/
│   │   └── banners/
│   ├── index.php
│   └── .htaccess
├── resources/
├── routes/
├── storage/ (775)
│   ├── app/
│   ├── framework/
│   │   ├── cache/ (775)
│   │   ├── sessions/ (775)
│   │   └── views/ (775)
│   └── logs/ (775)
├── vendor/ (after composer install)
├── .env
├── .env.hostinger
├── artisan
└── composer.json

/home/u909490256/public_html/
├── index.php
├── .htaccess (with PHP 8.3 handler)
├── css/
├── js/
└── uploads/
```

---

## 📞 NEED HELP?

### Hostinger Support:
- **Live Chat:** hPanel → Help → Live Chat
- **Email:** support@hostinger.com

### What to say:
```
Hi, I need help deploying my Laravel application manually.

Can you please:
1. Run composer install in /home/u909490256/domains/jastiphype.shop
2. Run php artisan key:generate --force
3. Run php artisan migrate --force
4. Run php artisan config:cache

Domain: jastiphype.shop

Thank you!
```

---

**Created:** 12 Februari 2026  
**Method:** Manual deployment without SSH  
**Estimated Time:** 30-45 menit  
**Success Rate:** 90% (if composer install can be run)
