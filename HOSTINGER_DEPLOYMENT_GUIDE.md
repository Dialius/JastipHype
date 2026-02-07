# Panduan Deploy JastipHype ke Hostinger

## Persiapan Sebelum Upload

### 1. Build Assets Production
Jalankan di komputer lokal:
```bash
npm install
npm run build
```

### 2. Optimize Laravel
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. File yang TIDAK Perlu Diupload
Buat file `.deployignore` atau hapus folder ini sebelum upload:
- `node_modules/` (ukuran besar, install ulang di server)
- `.git/`
- `tests/`
- `storage/logs/*.log`
- `.env.example`

---

## Langkah Deploy di Hostinger

### Step 1: Login ke hPanel Hostinger
1. Login ke [hpanel.hostinger.com](https://hpanel.hostinger.com)
2. Pilih hosting yang sudah dibeli
3. Buka **File Manager** atau gunakan **FTP/SFTP**

### Step 2: Upload File

#### Opsi A: Via File Manager (Web)
1. Buka File Manager di hPanel
2. Masuk ke folder `public_html` atau `domains/namadomain.com/public_html`
3. Upload semua file KECUALI `node_modules`
4. Pastikan struktur folder tetap sama

#### Opsi B: Via FTP (Lebih Cepat)
1. Download FileZilla atau WinSCP
2. Koneksi FTP:
   - Host: Lihat di hPanel → FTP Accounts
   - Username: Username FTP Anda
   - Password: Password FTP Anda
   - Port: 21 (FTP) atau 22 (SFTP)
3. Upload semua file ke `public_html`

### Step 3: Setup Struktur Folder di Hostinger

**PENTING:** Laravel membutuhkan struktur khusus!

```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← Ini yang harus jadi root domain
│   ├── index.php
│   ├── .htaccess
│   └── ...
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── artisan
```

**Cara Setup:**
1. Upload semua file Laravel ke `public_html`
2. Di hPanel, buka **Advanced → PHP Configuration**
3. Set **Document Root** ke: `public_html/public`

ATAU

1. Upload file Laravel ke folder `laravel` di `public_html`
2. Pindahkan isi folder `public` ke `public_html`
3. Edit `public_html/index.php`:

```php
// Ubah baris ini:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Menjadi:
require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
```

### Step 4: Setup Database

1. Di hPanel, buka **Databases → MySQL Databases**
2. Buat database baru:
   - Database name: `u123456_jastiphype` (contoh)
   - Username: Buat user baru
   - Password: Buat password kuat
3. Catat kredensial database

### Step 5: Konfigurasi .env

Edit file `.env` di server (via File Manager atau FTP):

```env
APP_NAME=JastipHype
APP_ENV=production
APP_KEY=base64:Ksc82+I7kMwWoOGGzSFWV/VvTND1VcXZQQG5v5FVWUI=
APP_DEBUG=false
APP_URL=https://namadomain.com

# Database Hostinger
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456_jastiphype
DB_USERNAME=u123456_user
DB_PASSWORD=password_database_anda

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database

# Mail - Gmail SMTP (tetap sama)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=putradavinza9@gmail.com
MAIL_PASSWORD="pxluzdwoqzwwdxgo"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="jastiphype@gmail.com"
MAIL_FROM_NAME="JastipHype"

# Google OAuth - UPDATE CALLBACK URL!
GOOGLE_CLIENT_ID=69087409346-s4p6help1912k7svmgjrfnmf6bbrq44a.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URL=https://namadomain.com/auth/google/callback

# RajaOngkir (tetap sama)
RAJAONGKIR_API_KEY=DL8eP5LD7c09c1638d2c213eYrBangX6
RAJAONGKIR_TYPE=starter
RAJAONGKIR_ORIGIN=151

# Midtrans (tetap sama)
MIDTRANS_MERCHANT_ID=G689132907
MIDTRANS_CLIENT_KEY=Mid-client-DTSB6VSWG4ReInb0
MIDTRANS_SERVER_KEY=Mid-server-ezqCCqbhe-zfF83kGA1ETmVy
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# Cloudinary (tetap sama)
CLOUDINARY_URL=cloudinary://966375899228811:HAOnLFxG8oxqMcEik5muf2JLLgE@dahizgvcq
CLOUDINARY_API_KEY=966375899228811
CLOUDINARY_API_SECRET=HAOnLFxG8oxqMcEik5muf2JLLgE
CLOUDINARY_CLOUD_NAME=dahizgvcq
```

### Step 6: Set Permission Folder

Via SSH atau File Manager, set permission:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

Di File Manager:
- Klik kanan folder `storage` → Permissions → 755
- Klik kanan folder `bootstrap/cache` → Permissions → 755

### Step 7: Install Composer Dependencies

**Opsi A: Via SSH (Recommended)**
```bash
cd public_html
composer install --optimize-autoloader --no-dev
```

**Opsi B: Upload vendor/ yang sudah di-build**
Jika tidak ada akses SSH, upload folder `vendor/` dari lokal (ukuran besar ~50-100MB)

### Step 8: Install Node.js Dependencies & Build

**Via SSH:**
```bash
cd public_html
npm install
npm run build
```

**ATAU upload folder `public/build/` yang sudah di-build dari lokal**

### Step 9: Migrasi Database

Via SSH:
```bash
php artisan migrate --force
php artisan db:seed --force
```

Atau import database manual:
1. Export database dari Railway/lokal
2. Import via phpMyAdmin di Hostinger

### Step 10: Setup .htaccess

Pastikan file `public/.htaccess` ada dan berisi:

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

### Step 11: Clear Cache & Optimize

Via SSH:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 12: Setup PHP Version

1. Di hPanel → **Advanced → PHP Configuration**
2. Pilih **PHP 8.2** atau **PHP 8.3**
3. Enable extensions:
   - `mbstring`
   - `openssl`
   - `pdo_mysql`
   - `tokenizer`
   - `xml`
   - `ctype`
   - `json`
   - `bcmath`
   - `fileinfo`
   - `gd`

### Step 13: Update Google OAuth Callback

1. Buka [Google Cloud Console](https://console.cloud.google.com)
2. Pilih project Anda
3. APIs & Services → Credentials
4. Edit OAuth 2.0 Client
5. Tambahkan Authorized redirect URIs:
   ```
   https://namadomain.com/auth/google/callback
   ```

### Step 14: Update Midtrans Notification URL

1. Login ke [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Settings → Configuration
3. Update **Payment Notification URL**:
   ```
   https://namadomain.com/midtrans/notification
   ```

---

## Troubleshooting

### Error 500 Internal Server Error
```bash
# Check error log
tail -f storage/logs/laravel.log

# Clear all cache
php artisan optimize:clear
```

### Blank Page / White Screen
- Cek permission `storage/` dan `bootstrap/cache/` (harus 755)
- Cek `.env` sudah benar
- Cek `APP_KEY` sudah di-set

### CSS/JS Tidak Load
- Pastikan `npm run build` sudah dijalankan
- Cek folder `public/build/` ada dan berisi file
- Cek `APP_URL` di `.env` sudah benar

### Database Connection Error
- Cek kredensial database di `.env`
- Pastikan `DB_HOST=localhost` (bukan IP)
- Cek user database punya akses ke database

### Node.js Tidak Tersedia
Jika paket Hostinger tidak support Node.js:
1. Build di lokal: `npm run build`
2. Upload folder `public/build/` ke server
3. Tidak perlu install Node.js di server

---

## Checklist Deployment

- [ ] Build assets: `npm run build`
- [ ] Optimize Laravel: `composer install --no-dev`
- [ ] Upload semua file ke Hostinger
- [ ] Setup document root ke `/public`
- [ ] Buat database MySQL di hPanel
- [ ] Update `.env` dengan kredensial Hostinger
- [ ] Set permission `storage/` dan `bootstrap/cache/`
- [ ] Install composer dependencies (atau upload `vendor/`)
- [ ] Upload hasil build (folder `public/build/`)
- [ ] Migrasi database
- [ ] Setup PHP 8.2+ dengan extensions
- [ ] Update Google OAuth callback URL
- [ ] Update Midtrans notification URL
- [ ] Test website: https://namadomain.com
- [ ] Test login Google
- [ ] Test payment Midtrans

---

## Tips Performa

1. **Enable OPcache** di PHP Configuration
2. **Gunakan CDN** untuk assets (Cloudinary sudah aktif)
3. **Setup Cron Job** untuk queue:
   ```
   * * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
   ```
4. **Monitor logs** secara berkala

---

## Support

Jika ada masalah:
1. Cek `storage/logs/laravel.log`
2. Hubungi support Hostinger via live chat
3. Pastikan paket hosting support:
   - PHP 8.2+
   - MySQL
   - SSH access (untuk composer & npm)
   - Node.js (untuk build assets)
