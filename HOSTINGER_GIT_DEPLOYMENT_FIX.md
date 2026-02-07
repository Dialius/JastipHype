# Fix Git Deployment di Hostinger

## Masalah
Git deployment menunjukkan "Selesai" tapi file tidak ter-copy ke server.

## Penyebab Kemungkinan

1. **Deployment path salah** - File di-deploy ke folder yang salah
2. **Build script tidak berjalan** - Composer install tidak dijalankan
3. **Git branch salah** - Deploy dari branch yang kosong
4. **Permission issue** - Server tidak bisa write ke directory

## Solusi: Konfigurasi Ulang Git Deployment

### 1. Cek Konfigurasi Deployment di Hostinger

Di Hostinger panel → Deployments → Settings, pastikan:

**Repository**: `https://github.com/username/JastiHype` (atau repo Anda)
**Branch**: `master` (atau `main`)
**Deployment Path**: `/domains/jastiphype.shop` (BUKAN `/public_html`)

### 2. Tambahkan Build Script

Di Hostinger deployment settings, tambahkan **Build Commands**:

```bash
#!/bin/bash

# Install Composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Copy public files to public_html
cp -r public/* public_html/

# Set permissions
chmod -R 775 storage bootstrap/cache

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed successfully!"
```

### 3. Tambahkan Post-Deployment Script

Buat file `.hostinger-deploy.sh` di root project:

```bash
#!/bin/bash

echo "Starting post-deployment tasks..."

# Navigate to project directory
cd /home/u909490256/domains/jastiphype.shop

# Install dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Copy public files
echo "Copying public files to public_html..."
rm -rf public_html/*
cp -r public/* public_html/

# Update index.php paths (jika perlu)
sed -i "s|__DIR__.'/../|__DIR__.'/../|g" public_html/index.php

# Set permissions
echo "Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 755 public_html

# Create storage link
echo "Creating storage link..."
php artisan storage:link --force

# Clear all caches
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache for production
echo "Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (optional, uncomment if needed)
# php artisan migrate --force

echo "Post-deployment tasks completed!"
```

Jangan lupa commit file ini ke Git:
```bash
git add .hostinger-deploy.sh
git commit -m "Add Hostinger deployment script"
git push origin master
```

### 4. Konfigurasi di Hostinger Panel

1. **Login ke hPanel Hostinger**
2. **Pilih website**: jastiphype.shop
3. **Klik "Git"** atau **"Deployments"**
4. **Klik "Settings"** atau **"Configure"**
5. **Set Post-Deployment Command**:
   ```bash
   bash .hostinger-deploy.sh
   ```

### 5. Trigger Manual Deployment

Di Hostinger panel:
1. Klik **"Deploy Now"** atau **"Redeploy"**
2. Tunggu proses selesai
3. Cek logs untuk error

### 6. Verifikasi File Ter-Deploy

Via File Manager, cek apakah file berikut ada di root:
- `app/`
- `bootstrap/`
- `config/`
- `vendor/`
- `.env`
- `artisan`

Dan di `public_html/`:
- `index.php`
- `.htaccess`
- `css/`, `js/`, `images/`

## Alternatif: Deploy via SSH

Jika Git deployment tetap tidak berfungsi, deploy manual via SSH:

### 1. Login SSH

```bash
ssh u909490256@jastiphype.shop
# Atau
ssh u909490256@srv2186.hstgr.io
```

Password: (password hosting Anda)

### 2. Clone Repository

```bash
cd /home/u909490256/domains/jastiphype.shop

# Backup existing files (jika ada)
mv public_html public_html.backup

# Clone repository
git clone https://github.com/username/JastiHype.git .

# Atau jika sudah ada .git
git pull origin master
```

### 3. Install Dependencies

```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Copy public files
cp -r public/* public_html/

# Set permissions
chmod -R 775 storage bootstrap/cache
chmod -R 755 public_html
```

### 4. Setup Environment

```bash
# Copy .env
cp .env.hostinger .env

# Generate app key (jika belum ada)
php artisan key:generate

# Run migrations
php artisan migrate --force

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link
```

### 5. Setup Auto-Deploy (Git Hooks)

Buat webhook untuk auto-deploy saat push ke Git:

**File: `.git/hooks/post-receive`**

```bash
#!/bin/bash

TARGET="/home/u909490256/domains/jastiphype.shop"
GIT_DIR="$TARGET/.git"
BRANCH="master"

while read oldrev newrev ref
do
    if [[ $ref = refs/heads/$BRANCH ]]; then
        echo "Deploying $BRANCH branch..."
        cd $TARGET
        git --work-tree=$TARGET --git-dir=$GIT_DIR checkout -f $BRANCH
        
        # Run deployment script
        bash .hostinger-deploy.sh
    fi
done
```

Set executable:
```bash
chmod +x .git/hooks/post-receive
```

## Troubleshooting Git Deployment

### Error: "Permission denied"

```bash
# Fix ownership
chown -R u909490256:u909490256 /home/u909490256/domains/jastiphype.shop

# Fix permissions
chmod -R 755 /home/u909490256/domains/jastiphype.shop
chmod -R 775 storage bootstrap/cache
```

### Error: "Composer not found"

Hostinger biasanya sudah install Composer. Jika tidak:

```bash
# Check Composer
which composer

# Jika tidak ada, install
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
```

### Error: "Git not found"

```bash
# Check Git
which git

# Jika tidak ada, hubungi support Hostinger
```

### Deployment Stuck

1. **Cancel deployment** di Hostinger panel
2. **Clear deployment cache**:
   ```bash
   rm -rf /home/u909490256/.cache/deployment
   ```
3. **Try again**

## Rekomendasi

Untuk deployment yang lebih reliable:

1. **Gunakan SSH + Git** (lebih kontrol)
2. **Setup CI/CD** dengan GitHub Actions
3. **Atau gunakan manual upload** untuk production

## GitHub Actions untuk Auto-Deploy (Bonus)

Buat file `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Hostinger

on:
  push:
    branches: [ master ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Deploy to Hostinger via SSH
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.SSH_HOST }}
        username: ${{ secrets.SSH_USERNAME }}
        password: ${{ secrets.SSH_PASSWORD }}
        script: |
          cd /home/u909490256/domains/jastiphype.shop
          git pull origin master
          bash .hostinger-deploy.sh
```

Set secrets di GitHub:
- `SSH_HOST`: srv2186.hstgr.io
- `SSH_USERNAME`: u909490256
- `SSH_PASSWORD`: (password hosting)

Dengan ini, setiap push ke master akan auto-deploy ke Hostinger.
