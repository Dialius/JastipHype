# ✅ SOLUSI RESMI: Deploy Private Repository ke Hostinger

Berdasarkan dokumentasi resmi Hostinger dan screenshot Anda.

---

## 🎯 MASALAH ANDA

Saya lihat dari screenshot:
1. ✅ Deploy sudah berhasil (Status: **Selesai**)
2. ✅ Repository **JastipHype** sudah terkoneksi
3. ❌ Website menampilkan **403 Forbidden**

**Masalahnya BUKAN di GitHub integration**, tapi di **konfigurasi Laravel di server**.

---

## 🔧 SOLUSI UNTUK ERROR 403 FORBIDDEN

Error 403 terjadi karena **Document Root** belum diset ke folder `public/` Laravel.

### LANGKAH PERBAIKAN:

#### Step 1: Cek Struktur Folder di File Manager

Dari screenshot File Manager Anda, saya lihat:
```
srv2186-files.hstgr.io/
├── public_html/
└── DO_NOT_UPLOAD_HERE
```

Masuk ke folder `public_html` dan cek isinya:

1. Klik folder **public_html**
2. Cek apakah ada folder Laravel (app, bootstrap, config, dll)
3. Cek apakah ada folder **public**

#### Step 2: Setup Document Root

**OPSI A: Via hPanel (Termudah)**

1. Dari hPanel Hostinger, klik **Website** → **jastiphype.shop**
2. Scroll ke bawah, cari **Advanced** → **PHP Configuration**
3. Cari setting **Document Root** atau **Entry Point**
4. Ubah dari:
   ```
   /public_html
   ```
   Menjadi:
   ```
   /public_html/public
   ```
5. Klik **Save**
6. Tunggu 1-2 menit, refresh website

**OPSI B: Via SSH (Jika Opsi A tidak ada)**

1. Connect SSH (dari hPanel → Advanced → SSH Access)
2. Jalankan perintah:

```bash
# Masuk ke folder website
cd domains/jastiphype.shop

# Cek struktur folder
ls -la public_html/

# Jika ada folder app, bootstrap, config, dll di public_html:
# Berarti Laravel sudah ter-deploy dengan benar

# Cek apakah ada folder public
ls -la public_html/public/

# Jika ada, berarti struktur sudah benar
# Tinggal setup document root
```

#### Step 3: Set Permission Folder

Via SSH, jalankan:

```bash
cd domains/jastiphype.shop/public_html

# Set permission storage dan bootstrap/cache
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Set ownership (ganti u909490256 dengan username Anda)
chown -R u909490256:u909490256 storage
chown -R u909490256:u909490256 bootstrap/cache
```

#### Step 4: Setup .env File

```bash
# Cek apakah .env ada
ls -la .env

# Jika tidak ada, buat dari .env.example
cp .env.example .env

# Edit .env
nano .env
```

Isi dengan kredensial Hostinger (sesuaikan):

```env
APP_NAME=JastipHype
APP_ENV=production
APP_KEY=base64:Ksc82+I7kMwWoOGGzSFWV/VvTND1VcXZQQG5v5FVWUI=
APP_DEBUG=false
APP_URL=https://jastiphype.shop

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u909490256_jastiphype
DB_USERNAME=u909490256_user
DB_PASSWORD=password_database_anda

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# ... (sisanya sama seperti .env.hostinger)
```

Save: `Ctrl+X`, `Y`, `Enter`

#### Step 5: Install Dependencies & Setup Laravel

```bash
# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Generate key jika belum ada
php artisan key:generate

# Migrasi database
php artisan migrate --force

# Seed database (jika perlu)
php artisan db:seed --force

# Clear dan cache ulang
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link
```

#### Step 6: Verifikasi .htaccess

```bash
# Cek .htaccess di folder public
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

Jika tidak ada atau salah, buat file baru:

```bash
nano public/.htaccess
# Paste kode di atas
# Save: Ctrl+X, Y, Enter
```

#### Step 7: Setup PHP Version

1. Di hPanel → **Advanced** → **PHP Configuration**
2. Pilih **PHP 8.2** atau **PHP 8.3**
3. Enable extensions:
   - ✅ mbstring
   - ✅ openssl
   - ✅ pdo_mysql
   - ✅ tokenizer
   - ✅ xml
   - ✅ ctype
   - ✅ json
   - ✅ bcmath
   - ✅ fileinfo
   - ✅ gd
   - ✅ curl
4. Klik **Save**

#### Step 8: Test Website

Buka: https://jastiphype.shop

Seharusnya sekarang sudah bisa!

---

## 📋 UNTUK PRIVATE REPO (Jika Masih Belum Terkoneksi)

Jika repository private Anda tidak muncul di list Hostinger:

### Cara Update Permission GitHub:

1. **Buka GitHub Settings**
   - Go to: https://github.com/settings/installations
   - Login dengan akun yang punya repo JastipHype

2. **Cari Hostinger**
   - Di tab **Installed GitHub Apps**
   - Cari **Hostinger** di list
   - Klik **Configure**

3. **Update Repository Access**
   
   **Jika muncul "Only select repositories":**
   - Klik **Select repositories**
   - Pilih **JastipHype** dari dropdown
   - Klik **Save**

   **Jika muncul "All repositories":**
   - Pilih **All repositories** (lebih mudah)
   - Klik **Save**

4. **Untuk Organization Repository**
   
   Jika repo ada di GitHub Organization:
   - Buka Organization Settings
   - Klik **Third-party access**
   - Cari **Hostinger**
   - Klik **Grant** atau **Request access**
   - Pilih repository JastipHype
   
   **Note**: Harus punya admin rights di organization

5. **Refresh di Hostinger**
   - Kembali ke Hostinger dashboard
   - Refresh halaman
   - Repository seharusnya muncul

6. **Jika Masih Tidak Muncul**
   - Di Hostinger, klik **Pengaturan dan deploy ulang**
   - Disconnect GitHub
   - Connect ulang GitHub
   - Authorize dengan permission private repos

---

## 🔍 TROUBLESHOOTING BERDASARKAN SCREENSHOT

### Dari Screenshot 1: Error 403 Forbidden
**Penyebab**: Document root belum diset ke `/public`

**Solusi**: Ikuti Step 2 di atas

### Dari Screenshot 2: File Manager
Saya lihat struktur:
```
public_html/
└── DO_NOT_UPLOAD_HERE
```

**Kemungkinan**:
1. Deploy belum selesai sempurna
2. File Laravel ada di folder lain
3. Document root salah

**Solusi**:
```bash
# Via SSH, cek semua folder
cd ~
find . -name "artisan" -type f 2>/dev/null

# Akan menunjukkan lokasi file artisan Laravel
# Biasanya di: ./domains/jastiphype.shop/public_html/artisan
```

### Dari Screenshot 3: Detail Penerapan
Saya lihat:
- ✅ Status: **Selesai**
- ✅ Repository: **JastipHype**
- ✅ Branch: **master**
- ✅ Framework: **Vite**
- ✅ Node: **24.x**
- ⚠️ Pengaturan bangun dan keluaran: **Kebiasaan**

**Kemungkinan masalah**: Build settings belum diset

**Solusi**:
1. Klik **Pengaturan dan deploy ulang**
2. Klik **Ubah** di "Pengaturan build dan output"
3. Isi:
   - **Build command**: `npm run build`
   - **Output directory**: `public/build`
4. Klik **Simpan**
5. Klik **Deploy ulang**

### Dari Screenshot 4: Deployments
Saya lihat deploy sudah **Selesai** (hijau)

Berarti masalahnya di **konfigurasi server**, bukan di GitHub integration.

---

## ✅ CHECKLIST PERBAIKAN

Ikuti checklist ini step by step:

- [ ] Masuk SSH ke server Hostinger
- [ ] Cek struktur folder: `ls -la domains/jastiphype.shop/public_html/`
- [ ] Pastikan ada folder Laravel (app, bootstrap, config, dll)
- [ ] Set document root ke `/public_html/public`
- [ ] Set permission: `chmod -R 755 storage bootstrap/cache`
- [ ] Cek/buat file `.env` dengan kredensial Hostinger
- [ ] Install dependencies: `composer install --no-dev`
- [ ] Migrasi database: `php artisan migrate --force`
- [ ] Cache Laravel: `php artisan config:cache`
- [ ] Cek .htaccess di folder `public/`
- [ ] Set PHP version 8.2+ di hPanel
- [ ] Enable PHP extensions yang dibutuhkan
- [ ] Test website: https://jastiphype.shop

---

## 🎯 KESIMPULAN

Berdasarkan screenshot Anda:

1. ✅ **GitHub integration sudah berhasil** (repo private sudah terkoneksi)
2. ✅ **Deploy sudah selesai** (status hijau)
3. ❌ **Website error 403** karena document root belum diset

**Fokus perbaikan**: Ikuti Step 1-8 di bagian "SOLUSI UNTUK ERROR 403 FORBIDDEN" di atas.

**Estimasi waktu**: 10-15 menit via SSH

---

## 📞 JIKA MASIH ERROR

Setelah ikuti semua langkah di atas, jika masih error:

1. **Cek error log Laravel**:
```bash
tail -50 storage/logs/laravel.log
```

2. **Cek error log PHP**:
Di hPanel → **Advanced** → **Error Logs**

3. **Test database connection**:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

4. **Contact Hostinger Support**:
Live chat di hPanel (pojok kanan bawah)

---

**Panduan ini dibuat berdasarkan dokumentasi resmi Hostinger dan screenshot Anda.**
