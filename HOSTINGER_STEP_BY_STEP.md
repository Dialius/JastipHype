# 🚀 Panduan Deploy JastipHype ke Hostinger - STEP BY STEP

## ✅ PERSIAPAN SUDAH SELESAI

Perintah berikut sudah dijalankan:
- ✅ `composer install --optimize-autoloader --no-dev`
- ✅ `npm install`
- ✅ `npm run build` (Assets sudah di-build di folder `public/build/`)
- ✅ `php artisan config:cache`
- ✅ `php artisan route:cache`
- ✅ `php artisan view:cache`

---

## 📋 LANGKAH DEPLOYMENT KE HOSTINGER

### FASE 1: PERSIAPAN DI HOSTINGER

#### Step 1: Buat Database MySQL
1. Login ke **hPanel Hostinger**: https://hpanel.hostinger.com
2. Pilih hosting Anda
3. Klik **Databases** → **MySQL Databases**
4. Klik **Create New Database**
5. Isi form:
   - **Database Name**: `jastiphype` (akan jadi `u123456_jastiphype`)
   - **Username**: Buat username baru atau pilih existing
   - **Password**: Buat password kuat (CATAT INI!)
6. Klik **Create**
7. **CATAT KREDENSIAL INI:**
   ```
   Database Name: u123456_jastiphype
   Username: u123456_user
   Password: [password yang Anda buat]
   Host: localhost
   Port: 3306
   ```

#### Step 2: Setup GitHub Repository (Jika Belum)
Jika belum push ke GitHub:
```bash
git init
git add .
git commit -m "Initial commit for Hostinger deployment"
git branch -M master
git remote add origin https://github.com/username/JastipHype.git
git push -u origin master
```

---

### FASE 2: DEPLOY VIA HOSTINGER GITHUB INTEGRATION

Saya lihat Anda sudah di halaman setup Hostinger. Ikuti langkah ini:

#### Step 3: Konfigurasi di Halaman Hostinger
Berdasarkan screenshot Anda:

1. **Preset framework**: Sudah pilih **Vite** ✅
2. **Branch**: Pilih **master** ✅
3. **Versi node**: Pilih **24.x** ✅
4. **Root directory**: Biarkan `/` (kosong) ✅

5. **Pengaturan build dan output**:
   - Klik tombol **Ubah**
   - Isi:
     - **Build command**: `npm run build`
     - **Output directory**: `public/build`
   - Klik **Simpan**

6. **Variabel environment**:
   - Klik tombol **Tambahkan**
   - Tambahkan variabel berikut SATU PER SATU:

```
APP_NAME=JastipHype
APP_ENV=production
APP_KEY=base64:Ksc82+I7kMwWoOGGzSFWV/VvTND1VcXZQQG5v5FVWUI=
APP_DEBUG=false
APP_URL=https://namadomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456_jastiphype
DB_USERNAME=u123456_user
DB_PASSWORD=password_database_anda

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=putradavinza9@gmail.com
MAIL_PASSWORD=pxluzdwoqzwwdxgo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=jastiphype@gmail.com
MAIL_FROM_NAME=JastipHype

GOOGLE_CLIENT_ID=69087409346-s4p6help1912k7svmgjrfnmf6bbrq44a.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URL=https://namadomain.com/auth/google/callback

RAJAONGKIR_API_KEY=DL8eP5LD7c09c1638d2c213eYrBangX6
RAJAONGKIR_TYPE=starter
RAJAONGKIR_ORIGIN=151

MIDTRANS_MERCHANT_ID=G689132907
MIDTRANS_CLIENT_KEY=Mid-client-DTSB6VSWG4ReInb0
MIDTRANS_SERVER_KEY=Mid-server-ezqCCqbhe-zfF83kGA1ETmVy
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

CLOUDINARY_URL=cloudinary://966375899228811:HAOnLFxG8oxqMcEik5muf2JLLgE@dahizgvcq
CLOUDINARY_API_KEY=966375899228811
CLOUDINARY_API_SECRET=HAOnLFxG8oxqMcEik5muf2JLLgE
CLOUDINARY_CLOUD_NAME=dahizgvcq

VITE_APP_NAME=JastipHype
```

**PENTING**: Ganti nilai berikut:
- `APP_URL` → Domain Hostinger Anda
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` → Kredensial dari Step 1
- `GOOGLE_REDIRECT_URL` → Domain Hostinger Anda

7. Klik tombol **Deploy** (ungu di kanan bawah)

---

### FASE 3: KONFIGURASI SETELAH DEPLOY

#### Step 4: Akses SSH Hostinger
1. Di hPanel, cari **Advanced** → **SSH Access**
2. Aktifkan SSH jika belum aktif
3. Catat kredensial SSH:
   ```
   Host: ssh.hostinger.com (atau IP server)
   Port: 22
   Username: u123456
   Password: [password hosting Anda]
   ```

4. Buka terminal/CMD dan connect:
```bash
ssh u123456@ssh.hostinger.com
```

#### Step 5: Setup Laravel di Server

Setelah masuk SSH, jalankan perintah berikut:

```bash
# 1. Masuk ke folder website
cd domains/namadomain.com/public_html

# 2. Install Composer dependencies
composer install --optimize-autoloader --no-dev

# 3. Set permission folder
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 4. Clear cache Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 5. Migrasi database
php artisan migrate --force

# 6. Seed database (jika perlu)
php artisan db:seed --force

# 7. Cache ulang untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Create storage link
php artisan storage:link
```

#### Step 6: Setup Document Root

**PENTING**: Laravel harus serve dari folder `public/`

**Opsi A: Via hPanel (Recommended)**
1. Di hPanel, buka **Advanced** → **PHP Configuration**
2. Cari **Document Root**
3. Ubah dari `/public_html` menjadi `/public_html/public`
4. Klik **Save**

**Opsi B: Manual (Jika Opsi A tidak ada)**
1. Via SSH, pindahkan file:
```bash
cd domains/namadomain.com
mkdir laravel
mv public_html/* laravel/
mv laravel/public/* public_html/
```

2. Edit `public_html/index.php`:
```bash
nano public_html/index.php
```

Ubah baris:
```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

Menjadi:
```php
require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
```

Save: `Ctrl+X`, `Y`, `Enter`

#### Step 7: Setup PHP Version & Extensions

1. Di hPanel → **Advanced** → **PHP Configuration**
2. Pilih **PHP Version**: 8.2 atau 8.3
3. Pastikan extensions ini AKTIF (centang):
   - ✅ `mbstring`
   - ✅ `openssl`
   - ✅ `pdo_mysql`
   - ✅ `tokenizer`
   - ✅ `xml`
   - ✅ `ctype`
   - ✅ `json`
   - ✅ `bcmath`
   - ✅ `fileinfo`
   - ✅ `gd`
   - ✅ `curl`
4. Klik **Save**

#### Step 8: Verifikasi .htaccess

Via SSH atau File Manager, cek file `public/.htaccess`:

```bash
cat public/.htaccess
```

Harus berisi:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

### FASE 4: UPDATE EXTERNAL SERVICES

#### Step 9: Update Google OAuth

1. Buka [Google Cloud Console](https://console.cloud.google.com)
2. Pilih project: **JastipHype**
3. Menu: **APIs & Services** → **Credentials**
4. Klik OAuth 2.0 Client ID Anda
5. Di **Authorized redirect URIs**, tambahkan:
   ```
   https://namadomain.com/auth/google/callback
   ```
6. Klik **Save**

#### Step 10: Update Midtrans

1. Login ke [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Pilih environment: **Sandbox** (karena `MIDTRANS_IS_PRODUCTION=false`)
3. Menu: **Settings** → **Configuration**
4. Update **Payment Notification URL**:
   ```
   https://namadomain.com/midtrans/notification
   ```
5. Update **Finish Redirect URL**:
   ```
   https://namadomain.com/orders/success
   ```
6. Update **Error Redirect URL**:
   ```
   https://namadomain.com/orders/failed
   ```
7. Klik **Save**

#### Step 11: Setup Cron Job (Optional tapi Recommended)

Untuk menjalankan scheduled tasks Laravel:

1. Di hPanel → **Advanced** → **Cron Jobs**
2. Klik **Create Cron Job**
3. Isi:
   - **Common Settings**: Custom
   - **Minute**: `*`
   - **Hour**: `*`
   - **Day**: `*`
   - **Month**: `*`
   - **Weekday**: `*`
   - **Command**:
     ```bash
     cd /home/u123456/domains/namadomain.com/public_html && php artisan schedule:run >> /dev/null 2>&1
     ```
4. Klik **Create**

---

### FASE 5: TESTING

#### Step 12: Test Website

1. **Buka website**: https://namadomain.com
   - ✅ Homepage load dengan benar
   - ✅ CSS/JS load (tidak ada error 404)
   - ✅ Gambar dari Cloudinary load

2. **Test Login Google**:
   - Klik tombol "Login dengan Google"
   - ✅ Redirect ke Google
   - ✅ Setelah login, redirect kembali ke website
   - ✅ User tersimpan di database

3. **Test Fitur Utama**:
   - ✅ Browse products
   - ✅ Add to cart
   - ✅ Checkout
   - ✅ Payment Midtrans
   - ✅ Admin panel

4. **Test RajaOngkir**:
   - ✅ Cek ongkir di halaman checkout
   - ✅ Pilih kurir dan service

#### Step 13: Monitor Logs

Via SSH:
```bash
# Lihat error log Laravel
tail -f storage/logs/laravel.log

# Lihat error log PHP
tail -f /home/u123456/logs/error.log
```

---

## 🔧 TROUBLESHOOTING

### Error 500 Internal Server Error

**Penyebab & Solusi:**

1. **Permission salah**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

2. **Cache corrupt**
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Cek error log**
```bash
tail -50 storage/logs/laravel.log
```

### Blank Page / White Screen

1. **Cek .env**
```bash
cat .env
# Pastikan APP_KEY ada
# Pastikan DB credentials benar
```

2. **Test database connection**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### CSS/JS Tidak Load (404)

1. **Cek folder build ada**
```bash
ls -la public/build/
# Harus ada file: manifest.json, assets/app-*.css, assets/app-*.js
```

2. **Cek APP_URL di .env**
```bash
grep APP_URL .env
# Harus: APP_URL=https://namadomain.com (tanpa trailing slash)
```

3. **Clear cache**
```bash
php artisan config:clear
php artisan view:clear
```

### Database Connection Error

1. **Test credentials**
```bash
mysql -h localhost -u u123456_user -p u123456_jastiphype
# Masukkan password
# Jika berhasil, credentials benar
```

2. **Cek .env**
```bash
grep DB_ .env
# Pastikan:
# DB_HOST=localhost (bukan IP)
# DB_PORT=3306
# DB_DATABASE, DB_USERNAME, DB_PASSWORD benar
```

### Google OAuth Error

**Error**: "redirect_uri_mismatch"

**Solusi**:
1. Cek URL di Google Console PERSIS sama dengan di .env
2. Harus HTTPS (bukan HTTP)
3. Tidak ada trailing slash
4. Tunggu 5-10 menit setelah update di Google Console

### Midtrans Payment Error

1. **Cek environment**
   - Sandbox: `MIDTRANS_IS_PRODUCTION=false`
   - Production: `MIDTRANS_IS_PRODUCTION=true`

2. **Cek notification URL**
   - Harus HTTPS
   - Harus accessible dari internet (test: `curl https://namadomain.com/midtrans/notification`)

---

## 📊 CHECKLIST DEPLOYMENT

Gunakan checklist ini untuk memastikan semua langkah sudah dilakukan:

### Persiapan Lokal
- [x] `composer install --no-dev` ✅
- [x] `npm install` ✅
- [x] `npm run build` ✅
- [x] `php artisan config:cache` ✅
- [x] `php artisan route:cache` ✅
- [x] `php artisan view:cache` ✅

### Setup Hostinger
- [ ] Buat database MySQL di hPanel
- [ ] Catat kredensial database
- [ ] Push code ke GitHub
- [ ] Setup GitHub integration di Hostinger
- [ ] Tambahkan environment variables
- [ ] Klik Deploy

### Konfigurasi Server
- [ ] Connect via SSH
- [ ] `composer install --no-dev`
- [ ] Set permission `storage/` dan `bootstrap/cache/`
- [ ] `php artisan migrate --force`
- [ ] `php artisan db:seed --force`
- [ ] `php artisan config:cache`
- [ ] `php artisan storage:link`
- [ ] Setup document root ke `/public`
- [ ] Set PHP version 8.2+
- [ ] Enable PHP extensions

### External Services
- [ ] Update Google OAuth callback URL
- [ ] Update Midtrans notification URL
- [ ] Setup cron job

### Testing
- [ ] Test homepage load
- [ ] Test CSS/JS load
- [ ] Test login Google
- [ ] Test browse products
- [ ] Test checkout & payment
- [ ] Test admin panel
- [ ] Monitor error logs

---

## 🎯 NEXT STEPS SETELAH DEPLOY

1. **Monitoring**
   - Setup uptime monitoring (UptimeRobot, Pingdom)
   - Monitor error logs daily
   - Setup email alerts untuk errors

2. **Performance**
   - Enable OPcache di PHP Configuration
   - Setup CDN (Cloudflare)
   - Optimize database queries

3. **Security**
   - Set `APP_DEBUG=false` di production
   - Setup SSL certificate (biasanya auto di Hostinger)
   - Regular backup database
   - Update dependencies secara berkala

4. **SEO**
   - Submit sitemap ke Google Search Console
   - Setup Google Analytics
   - Optimize meta tags

---

## 📞 SUPPORT

Jika ada masalah:
1. Cek `storage/logs/laravel.log`
2. Cek error log PHP di hPanel
3. Hubungi Hostinger Support via live chat
4. Dokumentasi Laravel: https://laravel.com/docs

---

**File ini dibuat otomatis setelah menjalankan perintah persiapan deployment.**
**Semua assets sudah di-build dan siap untuk di-deploy!**
