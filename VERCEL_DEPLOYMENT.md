# Panduan Deployment ke Vercel + Railway Database

## 🚀 Setup Database di Railway

### 1. Buat Database PostgreSQL di Railway

1. Buka [railway.app](https://railway.app)
2. Login/Sign up
3. Klik **New Project** → **Provision PostgreSQL**
4. Tunggu database dibuat
5. Klik database → **Variables** tab
6. Copy semua credentials:
   - `DATABASE_URL` (atau individual: HOST, PORT, DATABASE, USERNAME, PASSWORD)

### 2. Konversi ke Format Laravel

Jika Railway memberikan `DATABASE_URL` format:
```
postgresql://user:password@host:port/database
```

Konversi ke:
- `DB_HOST` = host
- `DB_PORT` = port (biasanya 5432)
- `DB_DATABASE` = database
- `DB_USERNAME` = user
- `DB_PASSWORD` = password

## 🌐 Setup Deployment di Vercel

### 1. Install Vercel CLI (Opsional)

```bash
npm install -g vercel
```

### 2. Deploy via Vercel Dashboard (Recommended)

1. Buka [vercel.com](https://vercel.com)
2. Login dengan GitHub
3. Klik **Add New** → **Project**
4. Import repository GitHub Anda
5. Configure Project:
   - **Framework Preset**: Other
   - **Build Command**: `npm run build`
   - **Output Directory**: `public`
   - **Install Command**: `npm install`

### 3. Setup Environment Variables di Vercel

Di Vercel Dashboard → Project Settings → Environment Variables, tambahkan:

```env
# App
APP_NAME="JastipHype"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-domain.vercel.app

# Database (dari Railway)
DB_CONNECTION=pgsql
DB_HOST=your-railway-host.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=your-railway-password

# Session & Cache
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
CACHE_DRIVER=array
QUEUE_CONNECTION=sync

# Mail (Mailtrap/SMTP)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jastiphype.com
MAIL_FROM_NAME="${APP_NAME}"

# Midtrans
MIDTRANS_SERVER_KEY=your-midtrans-server-key
MIDTRANS_CLIENT_KEY=your-midtrans-client-key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# RajaOngkir
RAJAONGKIR_API_KEY=your-rajaongkir-key

# OAuth (Optional)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

# File Storage
FILESYSTEM_DISK=public
```

### 4. Generate APP_KEY

Jika belum punya `APP_KEY`, generate dengan:

```bash
php artisan key:generate --show
```

Copy output dan paste ke Vercel environment variables.

## 📦 Setup Database Schema

### 1. Jalankan Migrasi

Setelah database Railway siap dan environment variables di-set:

**Option A: Via Local (Recommended)**

```bash
# Update .env lokal dengan credentials Railway
DB_CONNECTION=pgsql
DB_HOST=your-railway-host.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=your-railway-password

# Jalankan migrasi
php artisan migrate --force

# Jalankan seeder (optional)
php artisan db:seed --force
```

**Option B: Via Railway CLI**

1. Install Railway CLI:
```bash
npm install -g @railway/cli
```

2. Login dan link project:
```bash
railway login
railway link
```

3. Jalankan migrasi:
```bash
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

## 🔧 Post-Deployment Setup

### 1. Storage Link

Karena Vercel serverless, file upload akan hilang setiap deployment. Solusi:

**Option A: Gunakan Cloudinary/S3**
- Ubah `FILESYSTEM_DISK=s3` atau cloudinary
- Setup credentials di environment variables

**Option B: Gunakan Railway untuk Storage**
- Deploy Laravel API di Railway untuk handle uploads
- Vercel hanya untuk frontend

### 2. Optimize untuk Production

Di local, jalankan:

```bash
# Build assets
npm run build

# Test production mode
APP_ENV=production php artisan serve
```

### 3. Clear Cache di Vercel

Jika ada masalah, redeploy dengan:
- Vercel Dashboard → Deployments → Latest → **Redeploy**
- Atau via CLI: `vercel --prod`

## 🐛 Troubleshooting

### Error: "No Output Directory named 'dist'"
✅ **Fixed** - `vercel.json` sudah dikonfigurasi dengan `outputDirectory: "public"`

### Error: Database Connection Failed
- Cek Railway database masih running
- Verify environment variables di Vercel
- Test koneksi dari local dengan credentials Railway

### Error: 500 Internal Server Error
- Set `APP_DEBUG=true` sementara untuk lihat error
- Cek Vercel Logs: Dashboard → Project → Deployments → View Function Logs
- Pastikan `APP_KEY` sudah di-set

### Error: Assets Not Loading
- Pastikan `npm run build` berhasil
- Cek `public/build` folder ada setelah build
- Verify routes di `vercel.json` untuk `/build/*`

### Error: Session/Cookie Issues
- Pastikan `SESSION_DRIVER=cookie`
- Set `SESSION_SECURE_COOKIE=true` untuk HTTPS
- Tambahkan domain ke `SESSION_DOMAIN` jika perlu

## 📝 Checklist Deployment

- [ ] Database PostgreSQL dibuat di Railway
- [ ] Environment variables di-copy dari Railway ke Vercel
- [ ] `APP_KEY` di-generate dan di-set di Vercel
- [ ] Midtrans credentials di-set (sandbox untuk testing)
- [ ] RajaOngkir API key di-set
- [ ] Repository di-push ke GitHub
- [ ] Project di-import ke Vercel
- [ ] Build berhasil di Vercel
- [ ] Migrasi database dijalankan
- [ ] Test website bisa diakses
- [ ] Test fitur utama: register, login, browse products
- [ ] Test checkout flow (dengan Midtrans sandbox)

## 🎯 Next Steps

1. **Setup Custom Domain** (Optional)
   - Vercel Dashboard → Project → Settings → Domains
   - Tambahkan domain custom Anda

2. **Setup Email Production**
   - Ganti Mailtrap dengan SMTP real (Gmail, SendGrid, dll)
   - Update `MAIL_*` environment variables

3. **Enable Midtrans Production**
   - Ganti ke production credentials
   - Set `MIDTRANS_IS_PRODUCTION=true`

4. **Setup Monitoring**
   - Vercel Analytics (built-in)
   - Sentry untuk error tracking
   - Google Analytics

5. **Optimize Performance**
   - Enable Vercel Edge Caching
   - Optimize images dengan Vercel Image Optimization
   - Setup CDN untuk static assets

## 📞 Support

Jika ada masalah:
1. Cek Vercel deployment logs
2. Cek Railway database logs
3. Test koneksi database dari local
4. Verify semua environment variables

Good luck! 🚀
