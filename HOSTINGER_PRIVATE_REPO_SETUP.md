# Setup Hostinger dengan Private GitHub Repository

## Masalah
Repository GitHub Anda **private**, sehingga Hostinger tidak bisa akses tanpa authentication. Ini sebabnya deployment gagal dan file tidak ter-copy.

## Solusi: Setup SSH Key untuk Private Repository

### Langkah 1: Generate SSH Key di Hostinger

1. **Login ke hPanel Hostinger**
   - Buka: https://hpanel.hostinger.com
   - Login dengan akun Anda

2. **Navigasi ke Git Settings**
   - Pilih website: **jastiphype.shop**
   - Klik **"Manage"**
   - Di sidebar kiri, cari dan klik **"GIT"**

3. **Generate SSH Key**
   - Klik tombol **"Generate Key"** atau **"Generate SSH Key"**
   - Hostinger akan generate SSH key pair
   - **COPY** SSH public key yang muncul (biasanya dimulai dengan `ssh-rsa` atau `ssh-ed25519`)
   - Simpan key ini, Anda akan butuh untuk langkah berikutnya

   Contoh SSH key:
   ```
   ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDExample...
   ```

### Langkah 2: Add SSH Key ke GitHub

1. **Login ke GitHub**
   - Buka: https://github.com
   - Login dengan akun Anda

2. **Buka Repository Settings**
   - Navigasi ke repository: **JastiHype** (atau nama repo Anda)
   - Klik tab **"Settings"** (di menu repository)

3. **Add Deploy Key**
   - Di sidebar kiri, klik **"Deploy keys"**
   - Klik tombol **"Add deploy key"**

4. **Paste SSH Key**
   - **Title**: Beri nama, misalnya: `Hostinger Deployment Key`
   - **Key**: Paste SSH public key dari Hostinger (langkah 1.3)
   - **Allow write access**: ❌ JANGAN centang (read-only sudah cukup)
   - Klik **"Add key"**

5. **Verifikasi**
   - Deploy key akan muncul di list
   - Status: belum pernah digunakan (normal)

### Langkah 3: Setup Git Deployment di Hostinger

1. **Kembali ke Hostinger Git Settings**
   - Websites → jastiphype.shop → Manage → GIT

2. **Create New Repository**
   - Klik **"Create a New Repository"** atau **"Add New Repository"**

3. **Isi Form Deployment**

   **Repository Address**:
   ```
   git@github.com:username/JastiHype.git
   ```
   ⚠️ **PENTING**: 
   - Gunakan format `git@github.com:` (SSH), BUKAN `https://github.com/`
   - Ganti `username` dengan username GitHub Anda
   - Ganti `JastiHype` dengan nama repository Anda yang sebenarnya

   **Branch**:
   ```
   master
   ```
   (atau `main` jika branch utama Anda bernama `main`)

   **Install Path** (Directory):
   ```
   
   ```
   ⚠️ **KOSONGKAN** atau isi dengan `.` (titik)
   - Kosong = deploy ke root directory
   - Jika isi dengan nama folder, file akan di-deploy ke subfolder tersebut

4. **Save Configuration**
   - Klik **"Save"** atau **"Create"**

### Langkah 4: Deploy Repository

1. **Trigger Deployment**
   - Setelah save, akan muncul tombol:
     - **Deploy** - Deploy manual
     - **Auto Deploy** - Setup webhook untuk auto-deploy
     - **View latest build output** - Lihat log deployment
     - **Delete** - Hapus konfigurasi

2. **Klik "Deploy"**
   - Hostinger akan mulai clone repository
   - Proses biasanya 1-5 menit tergantung ukuran repo

3. **Monitor Progress**
   - Klik **"View latest build output"** untuk lihat progress
   - Tunggu sampai status: **"Deployment successful"** atau **"Selesai"**

4. **Cek Log**
   - Jika ada error, akan muncul di build output
   - Error umum:
     - `Permission denied (publickey)` → SSH key belum ter-setup dengan benar
     - `Repository not found` → URL repository salah
     - `Directory not empty` → Install path sudah ada file

### Langkah 5: Setup Struktur Laravel

Setelah deployment berhasil, file Laravel akan ada di root. Sekarang setup struktur untuk Hostinger:

1. **Via File Manager Hostinger**
   - Buka File Manager
   - Navigasi ke root directory

2. **Copy Public Files ke public_html**
   - Masuk ke folder `public`
   - Select All files (Ctrl+A)
   - Cut (Ctrl+X)
   - Keluar ke parent directory
   - Masuk ke folder `public_html`
   - Paste (Ctrl+V)

3. **Edit public_html/index.php**
   - Buka file `public_html/index.php`
   - Pastikan path benar:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

4. **Setup .env**
   - Di root directory, rename `.env.hostinger` menjadi `.env`
   - Atau copy isi `.env.hostinger` ke `.env`

5. **Set Permissions**
   - Folder `storage`: 775
   - Folder `bootstrap/cache`: 775
   - Folder `public_html`: 755

### Langkah 6: Setup Database & Run Migrations

1. **Buat Database di Hostinger**
   - hPanel → Databases → MySQL Databases
   - Create new database: `u909490256_jastiphype`
   - Create user: `u909490256_user`
   - Set password yang kuat
   - Grant all privileges

2. **Update .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=u909490256_jastiphype
   DB_USERNAME=u909490256_user
   DB_PASSWORD=password_yang_baru_dibuat
   ```

3. **Run Migrations via SSH** (jika ada akses SSH)
   ```bash
   cd /home/u909490256/domains/jastiphype.shop
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Atau via Web Script** (jika tidak ada SSH)
   - Buat file `setup.php` di root:
   ```php
   <?php
   // HAPUS FILE INI SETELAH SELESAI!
   echo "<pre>";
   passthru('php artisan migrate --force 2>&1');
   passthru('php artisan db:seed --force 2>&1');
   passthru('php artisan storage:link 2>&1');
   passthru('php artisan config:cache 2>&1');
   passthru('php artisan route:cache 2>&1');
   passthru('php artisan view:cache 2>&1');
   echo "\nSetup completed! DELETE THIS FILE NOW!";
   echo "</pre>";
   ?>
   ```
   - Akses: `https://jastiphype.shop/setup.php`
   - **HAPUS file ini setelah selesai!**

### Langkah 7: Setup Auto-Deploy (Opsional)

Agar setiap push ke GitHub otomatis deploy ke Hostinger:

1. **Di Hostinger Git Settings**
   - Klik tombol **"Auto Deploy"**
   - Copy **Webhook URL** yang muncul
   - Contoh: `https://api.hostinger.com/deploy/webhook/abc123...`

2. **Di GitHub Repository**
   - Settings → Webhooks → Add webhook
   - **Payload URL**: Paste webhook URL dari Hostinger
   - **Content type**: `application/json`
   - **Which events**: Just the push event
   - **Active**: ✅ Centang
   - Klik **"Add webhook"**

3. **Test Auto-Deploy**
   - Push perubahan ke GitHub:
     ```bash
     git add .
     git commit -m "Test auto-deploy"
     git push origin master
     ```
   - Cek di Hostinger apakah deployment otomatis berjalan

### Langkah 8: Buat Post-Deployment Script

Agar setiap deployment otomatis setup Laravel:

1. **Buat file `.hostinger-deploy.sh`** di root project (local):
   ```bash
   #!/bin/bash
   
   echo "Starting post-deployment..."
   
   # Navigate to project
   cd /home/u909490256/domains/jastiphype.shop
   
   # Copy public files
   echo "Copying public files..."
   cp -rf public/* public_html/
   
   # Set permissions
   echo "Setting permissions..."
   chmod -R 775 storage bootstrap/cache
   chmod -R 755 public_html
   
   # Clear caches
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
   
   # Storage link
   php artisan storage:link --force
   
   echo "Deployment completed!"
   ```

2. **Commit ke Git**
   ```bash
   git add .hostinger-deploy.sh
   git commit -m "Add deployment script"
   git push origin master
   ```

3. **Set di Hostinger**
   - Git Settings → Post-Deployment Command:
   ```bash
   bash .hostinger-deploy.sh
   ```

## Troubleshooting

### Error: "Permission denied (publickey)"
**Penyebab**: SSH key tidak ter-setup dengan benar

**Solusi**:
1. Generate ulang SSH key di Hostinger
2. Hapus deploy key lama di GitHub
3. Add deploy key baru
4. Try deploy lagi

### Error: "Repository not found"
**Penyebab**: URL repository salah atau tidak ada akses

**Solusi**:
1. Pastikan format URL: `git@github.com:username/repo.git`
2. Pastikan deploy key sudah di-add ke GitHub
3. Pastikan repository name benar (case-sensitive)

### Error: "Directory not empty"
**Penyebab**: Install path sudah ada file

**Solusi**:
1. Kosongkan install path di Hostinger
2. Atau hapus file di directory target
3. Deploy ulang

### Deployment Berhasil tapi Website Error 403/500
**Penyebab**: Struktur file belum sesuai atau permission salah

**Solusi**:
1. Ikuti Langkah 5: Setup Struktur Laravel
2. Pastikan `public_html/index.php` ada dan path benar
3. Set permission: `chmod -R 775 storage bootstrap/cache`
4. Cek `.env` sudah benar

### File Tidak Ter-Update Setelah Push
**Penyebab**: Auto-deploy belum setup atau webhook tidak berfungsi

**Solusi**:
1. Cek webhook di GitHub → Settings → Webhooks
2. Lihat "Recent Deliveries" untuk error
3. Atau deploy manual dari Hostinger panel

## Verifikasi Final

Setelah semua setup:

1. ✅ **File Structure**:
   ```
   Root:
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── vendor/
   ├── .env
   └── public_html/
       ├── index.php
       └── .htaccess
   ```

2. ✅ **Website**: https://jastiphype.shop
   - Harus menampilkan homepage Laravel
   - Tidak ada error 403 atau 500

3. ✅ **Auto-Deploy**: 
   - Push ke GitHub → otomatis deploy
   - Cek di Hostinger Git panel

4. ✅ **Database**: 
   - Tables ter-create
   - Data seeder ter-load

## Catatan Penting

- ⚠️ **Deploy key** hanya untuk read access, tidak bisa push
- ⚠️ **Jangan commit `.env`** ke Git
- ⚠️ **Backup database** sebelum migration
- ⚠️ **Test di local** dulu sebelum push
- ⚠️ **Monitor logs** setelah deployment

## Referensi

Panduan ini disusun berdasarkan dokumentasi resmi:
- [Hostinger: Add SSH Key to GitHub](https://www.hostinger.com/support/1583773-how-to-add-your-ssh-key-to-github-bitbucket-in-hostinger/)
- [Deploy Git Repository to Hostinger](https://blog.arfy.ca/deploy-from-git-repo-to-hostinger-server-in/)

Content rephrased for compliance with licensing restrictions.
