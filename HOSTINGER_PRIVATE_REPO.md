# Deploy dari Private Repository ke Hostinger

## OPSI 1: Ubah Repo Jadi Public (Sementara) ⭐ TERMUDAH

### Langkah:
1. Buka GitHub repository Anda
2. Klik **Settings** (tab paling kanan)
3. Scroll ke bawah ke bagian **Danger Zone**
4. Klik **Change visibility**
5. Pilih **Make public**
6. Ketik nama repo untuk konfirmasi
7. Klik **I understand, change repository visibility**

### Setelah Deploy Selesai:
Bisa ubah kembali jadi private dengan cara yang sama.

**PENTING**: Pastikan file `.env` TIDAK ter-commit ke GitHub!
```bash
# Cek apakah .env ada di .gitignore
cat .gitignore | grep .env

# Jika belum ada, tambahkan:
echo ".env" >> .gitignore
git add .gitignore
git commit -m "Add .env to gitignore"
git push
```

---

## OPSI 2: Authorize GitHub Private Repo di Hostinger

### Langkah:
1. Di halaman setup Hostinger, saat pilih repository
2. Klik **"Authorize GitHub"** atau **"Connect GitHub"**
3. Akan redirect ke GitHub
4. GitHub akan minta permission untuk akses private repos
5. Klik **"Authorize Hostinger"**
6. Pilih organization/account yang punya private repo
7. Beri akses ke repository yang diinginkan
8. Kembali ke Hostinger, refresh halaman
9. Sekarang private repo akan muncul di list

### Jika Sudah Authorize tapi Repo Tidak Muncul:
1. Klik icon GitHub di halaman Hostinger
2. Klik **"Reconnect GitHub"** atau **"Manage GitHub Access"**
3. Di GitHub Settings → Applications → Hostinger
4. Klik **"Configure"**
5. Di **Repository access**, pilih:
   - **All repositories** (akses semua), ATAU
   - **Only select repositories** → Pilih repo JastipHype
6. Klik **Save**
7. Kembali ke Hostinger, refresh

---

## OPSI 3: Deploy Manual via FTP/File Manager (Tanpa GitHub)

Jika tidak mau pakai GitHub integration sama sekali:

### Langkah:

#### 1. Compress Project
Di komputer lokal:
```bash
# Hapus folder yang tidak perlu
rmdir /s /q node_modules
rmdir /s /q .git

# Compress jadi ZIP
# Gunakan WinRAR/7-Zip untuk compress folder project
```

#### 2. Upload via File Manager
1. Login ke hPanel Hostinger
2. Buka **File Manager**
3. Masuk ke `public_html`
4. Klik **Upload** (icon di toolbar)
5. Upload file ZIP
6. Klik kanan file ZIP → **Extract**
7. Hapus file ZIP setelah extract

#### 3. Upload via FTP (Lebih Cepat)
1. Download **FileZilla** atau **WinSCP**
2. Koneksi FTP:
   - Host: Lihat di hPanel → **FTP Accounts**
   - Username: Username FTP Anda
   - Password: Password FTP Anda
   - Port: 21
3. Upload semua file KECUALI:
   - `node_modules/` (terlalu besar)
   - `.git/`
   - `tests/`
   - `.env` (buat manual di server)

#### 4. Setup di Server via SSH
```bash
# Connect SSH
ssh u123456@ssh.hostinger.com

# Masuk ke folder
cd domains/namadomain.com/public_html

# Install dependencies
composer install --optimize-autoloader --no-dev

# Buat file .env
nano .env
# Copy paste isi dari .env.hostinger
# Save: Ctrl+X, Y, Enter

# Set permission
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Migrasi database
php artisan migrate --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link
```

---

## OPSI 4: Deploy via Git Manual (SSH)

Upload via Git tapi manual (tanpa GitHub integration Hostinger):

### Langkah:

#### 1. Setup SSH Key di Hostinger
```bash
# Connect SSH
ssh u123456@ssh.hostinger.com

# Generate SSH key
ssh-keygen -t ed25519 -C "your_email@example.com"
# Tekan Enter 3x (default location, no passphrase)

# Copy public key
cat ~/.ssh/id_ed25519.pub
```

#### 2. Tambahkan SSH Key ke GitHub
1. Copy output dari command di atas
2. Buka GitHub → **Settings** → **SSH and GPG keys**
3. Klik **New SSH key**
4. Title: `Hostinger Server`
5. Paste key
6. Klik **Add SSH key**

#### 3. Clone Repository di Server
```bash
# Masuk ke folder
cd domains/namadomain.com

# Hapus public_html jika ada
rm -rf public_html

# Clone repo
git clone git@github.com:username/JastipHype.git public_html

# Masuk ke folder
cd public_html

# Install dependencies
composer install --optimize-autoloader --no-dev

# Copy .env
cp .env.example .env
nano .env
# Edit sesuai kredensial Hostinger

# Generate key (jika belum ada)
php artisan key:generate

# Set permission
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Migrasi
php artisan migrate --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 4. Update Code (Untuk Update Selanjutnya)
```bash
cd domains/namadomain.com/public_html
git pull origin master
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## REKOMENDASI

**Untuk Kemudahan**: Gunakan **OPSI 1** (ubah jadi public sementara)
- Paling mudah
- Bisa pakai GitHub integration Hostinger
- Auto-deploy saat push ke GitHub
- Setelah selesai, ubah kembali jadi private

**Untuk Keamanan Maksimal**: Gunakan **OPSI 4** (Git manual via SSH)
- Repo tetap private
- Kontrol penuh
- Bisa update dengan `git pull`

**Untuk Tanpa GitHub**: Gunakan **OPSI 3** (FTP manual)
- Tidak perlu GitHub sama sekali
- Upload manual setiap update
- Lebih lambat untuk update

---

## KEAMANAN: Pastikan .env TIDAK di GitHub!

Sebelum push ke GitHub (public atau private), pastikan:

```bash
# Cek .gitignore
cat .gitignore

# Harus ada baris ini:
.env
.env.backup
.env.production
.env.hostinger

# Jika belum ada, tambahkan:
echo ".env" >> .gitignore
echo ".env.*" >> .gitignore

# Cek apakah .env sudah ter-track
git ls-files | grep .env

# Jika ada output, hapus dari Git:
git rm --cached .env
git rm --cached .env.*

# Commit
git add .gitignore
git commit -m "Remove .env from Git tracking"
git push
```

---

## FAQ

**Q: Apakah aman ubah repo jadi public?**
A: Aman JIKA file `.env` tidak ter-commit. File `.env` berisi kredensial sensitif (database password, API keys). Pastikan ada di `.gitignore`.

**Q: Berapa lama proses authorize GitHub?**
A: Instant. Setelah authorize, private repo langsung muncul di list Hostinger.

**Q: Apakah bisa auto-deploy dari private repo?**
A: Bisa! Setelah authorize GitHub dengan akses private repos, auto-deploy akan jalan seperti public repo.

**Q: Apakah harus bayar untuk private repo?**
A: Tidak. GitHub private repo gratis unlimited. Hostinger juga tidak charge extra untuk deploy dari private repo.
