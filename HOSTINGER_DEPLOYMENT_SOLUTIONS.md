# Solusi Deployment Hostinger - Private Repository

## Situasi Anda

Berdasarkan pencarian, ada **3 kemungkinan** kenapa menu GIT tidak muncul:

### 1. **Fitur Git Tidak Tersedia di Paket Hosting Anda**

Fitur Git deployment di Hostinger **hanya tersedia di paket tertentu**:
- ✅ **Business Hosting** dan ke atas
- ✅ **Cloud Hosting**
- ✅ **VPS Hosting**
- ❌ **Premium Hosting** (mungkin tidak ada)
- ❌ **Single Hosting** (tidak ada)

**Cara Cek Paket Anda**:
1. Login ke hPanel Hostinger
2. Klik "Billing" di sidebar
3. Lihat paket hosting yang aktif

Jika paket Anda tidak support Git, Anda punya 2 pilihan:
- **Upgrade paket** ke Business atau Cloud
- **Gunakan alternatif deployment** (lihat di bawah)

---

### 2. **Menu Git Ada Tapi Tersembunyi**

Jika paket Anda support Git, coba cari dengan cara ini:

**Lokasi Menu Git di hPanel**:
1. Login: https://hpanel.hostinger.com
2. Klik **"Websites"** di sidebar kiri
3. Pilih website: **jastiphype.shop**
4. Klik **"Manage"**
5. Di halaman website management, **scroll ke bawah**
6. Cari section **"Advanced"** atau langsung cari **"Git"** di sidebar kiri

Menu Git biasanya ada di antara:
- File Manager
- Database
- **Git** ← Di sini
- SSH Access
- Cron Jobs

**Jika tetap tidak ada**, berarti paket Anda tidak support Git deployment.

---

### 3. **Fitur Git Belum Diaktifkan**

Kadang fitur Git perlu diaktifkan manual. Coba:
1. Contact Hostinger Support
2. Minta aktifkan fitur Git deployment
3. Atau tanyakan apakah paket Anda support Git

---

## Solusi Alternatif (Tanpa Menu Git)

Karena kemungkinan besar paket Anda tidak support Git deployment built-in, berikut alternatif yang bisa digunakan:

---

## ✅ SOLUSI 1: GitHub Actions Auto-Deploy (RECOMMENDED)

Ini cara paling modern dan tidak perlu menu Git di Hostinger!

### Cara Kerja:
- Setiap push ke GitHub → GitHub Actions otomatis SSH ke Hostinger → Pull code → Setup Laravel

### Langkah-langkah:

#### 1. Setup GitHub Secrets

Di GitHub repository Anda:
1. **Settings** → **Secrets and variables** → **Actions**
2. Klik **"New repository secret"**
3. Tambahkan secrets berikut:

**SSH_HOST**:
```
195.35.62.164
```
(Dari screenshot Hostinger SSH Anda)

**SSH_USERNAME**:
```
u909490256
```

**SSH_PASSWORD**:
```
password_hosting_anda
```
(Password untuk login SSH Hostinger)

**SSH_PORT**:
```
65002
```
(Dari screenshot Hostinger SSH Anda)

**DEPLOY_PATH**:
```
/home/u909490256/domains/jastiphype.shop
```

#### 2. Buat GitHub Actions Workflow

Di project Laravel lokal Anda, buat file:

**`.github/workflows/deploy.yml`**:

```yaml
name: Deploy to Hostinger

on:
  push:
    branches: [ master ]
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Checkout code
      uses: actions/checkout@v3
    
    - name: Deploy to Hostinger via SSH
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.SSH_HOST }}
        username: ${{ secrets.SSH_USERNAME }}
        password: ${{ secrets.SSH_PASSWORD }}
        port: ${{ secrets.SSH_PORT }}
        script: |
          cd ${{ secrets.DEPLOY_PATH }}
          
          # Pull latest code
          git pull origin master || git clone https://github.com/${{ github.repository }}.git .
          
          # Install dependencies
          composer install --no-dev --optimize-autoloader --no-interaction
          
          # Copy public files to public_html
          cp -rf public/* public_html/
          
          # Set permissions
          chmod -R 775 storage bootstrap/cache
          chmod -R 755 public_html
          
          # Setup .env
          if [ ! -f .env ]; then
            cp .env.hostinger .env
          fi
          
          # Run Laravel commands
          php artisan migrate --force
          php artisan storage:link --force
          php artisan config:cache
          php artisan route:cache
          php artisan view:cache
          
          echo "Deployment completed!"
```

#### 3. Commit dan Push

```bash
git add .github/workflows/deploy.yml
git commit -m "Add GitHub Actions deployment"
git push origin master
```

#### 4. Setup Git di Hostinger (Manual First Time)

Karena ini private repo, Anda perlu setup Git di server Hostinger via SSH:

**Login SSH**:
```bash
ssh -p 65002 u909490256@195.35.62.164
```

**Clone Repository**:
```bash
cd /home/u909490256/domains/jastiphype.shop

# Generate SSH key di server
ssh-keygen -t ed25519 -C "hostinger-deploy" -f ~/.ssh/hostinger_deploy -N ""

# Tampilkan public key
cat ~/.ssh/hostinger_deploy.pub
```

**Copy public key** yang muncul, lalu:

1. Buka GitHub → Repository → **Settings** → **Deploy keys**
2. **Add deploy key**
3. Title: `Hostinger Server`
4. Key: Paste public key dari server
5. **Add key**

**Kembali ke SSH, setup Git**:
```bash
# Configure Git to use SSH key
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/hostinger_deploy

# Configure Git
git config --global user.email "your-email@example.com"
git config --global user.name "Your Name"

# Clone repository (first time)
git clone git@github.com:username/JastiHype.git .

# Atau jika sudah ada folder, init Git
git init
git remote add origin git@github.com:username/JastiHype.git
git fetch
git checkout master
```

**Setelah ini, GitHub Actions akan otomatis deploy setiap push!**

---

## ✅ SOLUSI 2: Manual Upload via FTP (TERCEPAT)

Jika tidak mau ribet dengan Git, upload manual:

### Tools:
- **FileZilla**: https://filezilla-project.org/
- **WinSCP**: https://winscp.net/

### FTP Credentials:
- **Host**: `ftp.jastiphype.shop` atau `195.35.62.164`
- **Username**: `u909490256`
- **Password**: (password hosting Anda)
- **Port**: `21`

### Langkah:
1. Connect FTP
2. Upload semua file Laravel ke `/domains/jastiphype.shop/`
3. Copy isi folder `public` ke `public_html`
4. Edit `public_html/index.php` untuk path yang benar
5. Rename `.env.hostinger` jadi `.env`
6. Set permission via File Manager

**Kekurangan**: Harus upload manual setiap update

---

## ✅ SOLUSI 3: Ubah Repository Jadi Public (TIDAK RECOMMENDED)

Jika Anda tidak keberatan code jadi public:

1. GitHub → Repository → **Settings**
2. Scroll ke bawah → **Danger Zone**
3. **Change visibility** → **Make public**
4. Confirm

Setelah public, Anda bisa clone langsung via SSH tanpa deploy key:

```bash
ssh -p 65002 u909490256@195.35.62.164
cd /home/u909490256/domains/jastiphype.shop
git clone https://github.com/username/JastiHype.git .
```

**Kekurangan**: 
- Code jadi public, siapa saja bisa lihat
- Tidak aman untuk production
- API keys dan secrets bisa ter-expose jika tidak hati-hati

---

## Perbandingan Solusi

| Solusi | Kecepatan Setup | Auto-Deploy | Keamanan | Rekomendasi |
|--------|----------------|-------------|----------|-------------|
| **GitHub Actions** | 30 menit | ✅ Ya | ✅ Tinggi | ⭐⭐⭐⭐⭐ |
| **Manual FTP** | 15 menit | ❌ Tidak | ✅ Tinggi | ⭐⭐⭐ |
| **Public Repo** | 10 menit | ⚠️ Manual | ❌ Rendah | ⭐⭐ |

---

## Rekomendasi Saya

### Untuk Production (Website Live):
**Gunakan GitHub Actions** (Solusi 1)
- Setup sekali, deploy otomatis selamanya
- Aman, professional, modern
- Tidak perlu menu Git di Hostinger

### Untuk Testing Cepat:
**Manual Upload FTP** (Solusi 2)
- Cepat, simple, langsung jalan
- Cocok untuk first deployment
- Nanti bisa setup GitHub Actions

### Jangan Gunakan:
**Public Repository** (Solusi 3)
- Tidak aman untuk production
- API keys bisa ter-expose
- Hanya untuk project open-source

---

## Langkah Selanjutnya

Pilih salah satu solusi di atas, lalu:

1. **Jika pilih GitHub Actions**:
   - Setup GitHub Secrets
   - Buat workflow file
   - Setup SSH key di server
   - Push dan lihat magic happen!

2. **Jika pilih Manual FTP**:
   - Download FileZilla
   - Connect ke server
   - Upload files
   - Setup struktur Laravel

3. **Jika mau upgrade paket**:
   - Contact Hostinger support
   - Upgrade ke Business plan
   - Gunakan built-in Git deployment

---

## Troubleshooting

### "Permission denied" saat SSH
- Pastikan password benar
- Cek port: 65002 (bukan 22)
- Coba reset password SSH di hPanel

### "Git command not found" di server
- Git mungkin tidak installed
- Contact Hostinger support
- Atau gunakan FTP upload

### GitHub Actions gagal
- Cek secrets sudah benar
- Cek SSH credentials
- Lihat logs di GitHub Actions tab

---

## Kesimpulan

**Menu Git tidak ada** karena kemungkinan besar paket hosting Anda tidak support fitur Git deployment built-in.

**Solusi terbaik**: Gunakan **GitHub Actions** untuk auto-deploy. Ini cara modern, aman, dan tidak perlu menu Git di Hostinger.

Mau saya bantu setup GitHub Actions step-by-step?

---

## Referensi

Panduan ini disusun berdasarkan:
- [Hostinger: Deploy Git Repository](https://www.hostinger.com/support/1583302-how-to-deploy-a-git-repository-in-hostinger/)
- [GitHub Actions Deployment for Hostinger](https://www.rathik.dev/blog/github-actions-deployment-for-hostinger)

Content rephrased for compliance with licensing restrictions.
