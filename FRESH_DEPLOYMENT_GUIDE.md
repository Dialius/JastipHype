# 🚀 PANDUAN DEPLOYMENT FRESH INSTALL KE HOSTINGER

## 📋 PERSIAPAN

### 1. Pastikan Anda Punya:
- ✅ Akses SSH ke Hostinger
- ✅ Database MySQL sudah dibuat di Hostinger
- ✅ Repository GitHub sudah up-to-date
- ✅ SSH key sudah di-setup

---

## 🔧 LANGKAH 1: LOGIN SSH

```bash
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002
```

---

## 📥 LANGKAH 2: CLONE REPOSITORY

```bash
# Masuk ke folder domains
cd /home/u909490256/domains/jastiphype.shop

# Backup folder lama jika ada
mv jastiphype.shop jastiphype.shop.backup.$(date +%Y%m%d_%H%M%S) 2>/dev/null || true

# Clone repository fresh
git clone git@github.com:Dialius/JastipHype.git .

# Atau jika SSH key belum setup, gunakan HTTPS:
# git clone https://github.com/Dialius/JastipHype.git .
```

---

## ⚙️ LANGKAH 3: INSTALL DEPENDENCIES

```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;
```

---

## 🔐 LANGKAH 4: SETUP ENVIRONMENT

```bash
# Copy .env.hostinger ke .env
cp .env.hostinger .env

# Generate APP_KEY jika belum ada
php artisan key:generate --force

# Verify .env
cat .env | grep -E "APP_KEY|DB_|FILESYSTEM_DISK"
```

**PENTING**: Pastikan output menunjukkan:
- `APP_KEY=base64:...` (ada value-nya)
- `DB_DATABASE=u909490256_jastiphype`
- `DB_USERNAME=u909490256_vinthegreat`
- `DB_PASSWORD=XmAJ4!9tmJEt4hE`
- `FILESYSTEM_DISK=public`

---

## 🗄️ LANGKAH 5: SETUP DATABASE

```bash
# Test koneksi database
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected!'; } catch (Exception \$e) { echo 'Error: ' . \$e->getMessage(); }"

# Run migrations
php artisan migrate --force

# Seed database (optional, jika ingin data dummy)
# php artisan db:seed --force
```

---

## 📁 LANGKAH 6: SETUP STORAGE & UPLOADS

```bash
# Buat folder uploads
mkdir -p public/uploads/products
mkdir -p public/uploads/brands
mkdir -p public/uploads/categories
mkdir -p public/uploads/banners

# Set permissions
chmod -R 755 public/uploads

# Buat folder storage jika belum ada
mkdir -p storage/app/public/products
mkdir -p storage/app/public/brands
mkdir -p storage/app/public/categories
mkdir -p storage/app/public/banners
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set permissions
chmod -R 775 storage
```

---

## 🌐 LANGKAH 7: COPY KE PUBLIC_HTML

```bash
# Copy semua file public ke public_html
cp -rf public/* public_html/

# Set permissions
chmod -R 755 public_html

# Verify
ls -la public_html/
```

---

## 🧹 LANGKAH 8: CLEAR & BUILD CACHE

```bash
# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Build cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ✅ LANGKAH 9: VERIFIKASI

```bash
# 1. Cek APP_KEY
php artisan tinker --execute="echo 'APP_KEY: ' . config('app.key') . PHP_EOL;"

# 2. Cek Database
php artisan tinker --execute="echo 'DB: ' . config('database.connections.mysql.database') . PHP_EOL;"

# 3. Cek FILESYSTEM_DISK
php artisan tinker --execute="echo 'FILESYSTEM_DISK: ' . config('filesystems.default') . PHP_EOL;"

# 4. Test database connection
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK!' . PHP_EOL;"

# 5. Cek folder uploads
ls -lh public/uploads/
ls -lh public_html/uploads/

# 6. Cek permissions
ls -la storage/ | head -10
ls -la bootstrap/cache/
```

---

## 🎨 LANGKAH 10: BUILD ASSETS (OPTIONAL)

Jika Anda ingin build assets di server (tidak recommended untuk shared hosting):

```bash
# Install Node.js dependencies (jika ada node di server)
npm install

# Build production assets
npm run build

# Copy build ke public_html
cp -rf public/build public_html/
```

**CATATAN**: Lebih baik build assets di local, lalu push ke GitHub.

---

## 🔄 LANGKAH 11: SETUP AUTO DEPLOYMENT (OPTIONAL)

Jika ingin auto-deploy via GitHub Actions:

```bash
# Fix SSH key permissions
chmod 600 ~/.ssh/hostinger_deploy

# Test git pull
git pull origin master
```

Pastikan GitHub Actions secrets sudah di-set:
- `SSH_HOST`: id-dci-web1319.main-hosting.eu
- `SSH_USERNAME`: u909490256
- `SSH_PRIVATE_KEY`: (isi private key)
- `SSH_PORT`: 65002

---

## 🧪 LANGKAH 12: TEST WEBSITE

1. **Buka browser**: https://jastiphype.shop
2. **Test homepage**: Harus load tanpa error
3. **Test login**: https://jastiphype.shop/login
4. **Test admin**: https://jastiphype.shop/admin/login
5. **Test gambar**: Cek apakah logo dan gambar muncul

---

## 🚨 TROUBLESHOOTING

### Error 500

```bash
# Cek error log
tail -50 storage/logs/laravel.log

# Clear cache
php artisan config:clear
php artisan cache:clear

# Rebuild cache
php artisan config:cache
```

### Database Error

```bash
# Cek credentials
cat .env | grep DB_

# Test connection
php artisan tinker --execute="DB::connection()->getPdo();"
```

### Gambar Tidak Muncul

```bash
# Cek folder uploads
ls -lh public/uploads/

# Cek permissions
chmod -R 755 public/uploads
chmod -R 755 public_html/uploads

# Verify FILESYSTEM_DISK
grep FILESYSTEM_DISK .env
```

### Permission Denied

```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
chmod -R 755 public public_html
find storage -type f -exec chmod 664 {} \;
```

---

## 📝 CHECKLIST DEPLOYMENT

Sebelum selesai, pastikan semua ini sudah OK:

- [ ] Repository ter-clone dengan benar
- [ ] Composer dependencies ter-install
- [ ] `.env` file sudah di-setup dengan credentials yang benar
- [ ] `APP_KEY` sudah di-generate
- [ ] Database migrations sudah dijalankan
- [ ] Folder `public/uploads/` sudah dibuat
- [ ] Folder `storage/` permissions sudah benar (775)
- [ ] File di-copy ke `public_html/`
- [ ] Cache sudah di-clear dan di-rebuild
- [ ] Website bisa diakses tanpa error
- [ ] Login admin berfungsi
- [ ] Gambar bisa di-upload dan ditampilkan

---

## 🎉 SELESAI!

Website seharusnya sudah live di: **https://jastiphype.shop**

Jika ada masalah, jalankan emergency fix:

```bash
bash nuclear-fix.sh
```

---

## 📞 BANTUAN

Jika masih ada masalah, kirim output dari:

```bash
# System info
php -v
composer --version
php artisan --version

# Error log
tail -50 storage/logs/laravel.log

# Environment check
cat .env | grep -E "APP_|DB_|FILESYSTEM"
```
