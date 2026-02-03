# ✅ Vercel Deployment - FIXED (Berdasarkan Tutorial Terbaru 2024)

## 🔧 Perubahan yang Sudah Dilakukan

1. ✅ **vercel.json** - Updated dengan konfigurasi yang benar
2. ✅ **package.json** - Added `"engines": { "node": "18.x" }`
3. ✅ **api/index.php** - Sudah ada
4. ✅ **.vercelignore** - Sudah ada
5. ✅ **bootstrap/app.php** - Trust proxies sudah di-set
6. ✅ **.gitignore** - Added `.vercel`

## 🚀 Cara Deploy (Step-by-Step)

### 1. Setup Database Railway (5 menit)

```
1. Buka https://railway.app
2. Login dengan GitHub
3. New Project → Provision PostgreSQL
4. Klik database → Variables tab
5. Copy credentials:
   - PGHOST
   - PGPORT (5432)
   - PGDATABASE
   - PGUSER
   - PGPASSWORD
```

### 2. Build Assets Lokal

```bash
# Build assets
npm run build

# Pastikan folder public/build ada
dir public\build  # Windows
# atau
ls public/build   # Linux/Mac
```

### 3. Generate APP_KEY

```bash
php artisan key:generate --show
```

Copy output (contoh: `base64:abcd1234...`)

### 4. Push ke GitHub

```bash
git add .
git commit -m "Ready for Vercel deployment"
git push origin master
```

### 5. Deploy ke Vercel

**Via Vercel Dashboard:**

1. Buka https://vercel.com
2. Login dengan GitHub
3. **Add New** → **Project**
4. **Import** repository JastipHype
5. **Configure Project:**
   - Framework Preset: **Other**
   - Root Directory: `./` (default)
   - Build Command: Biarkan kosong atau `npm run build`
   - Output Directory: Biarkan kosong (sudah di-set di vercel.json)
   - Install Command: Biarkan kosong atau `npm install`

6. **Jangan deploy dulu!** Klik **Environment Variables** dulu

### 6. Set Environment Variables di Vercel

**PENTING:** Set di Vercel Dashboard, BUKAN di vercel.json!

Klik **Environment Variables** tab, lalu add satu per satu:

#### Required Variables (WAJIB):

```
APP_NAME = JastipHype
APP_ENV = production
APP_KEY = base64:xxx (dari step 3)
APP_DEBUG = false
APP_URL = https://your-project.vercel.app (akan dapat setelah deploy)

# Cache paths (PENTING!)
APP_CONFIG_CACHE = /tmp/config.php
APP_EVENTS_CACHE = /tmp/events.php
APP_PACKAGES_CACHE = /tmp/packages.php
APP_ROUTES_CACHE = /tmp/routes.php
APP_SERVICES_CACHE = /tmp/services.php
VIEW_COMPILED_PATH = /tmp

# Database (dari Railway)
DB_CONNECTION = pgsql
DB_HOST = containers-us-west-xxx.railway.app
DB_PORT = 5432
DB_DATABASE = railway
DB_USERNAME = postgres
DB_PASSWORD = xxx

# Session & Cache (PENTING!)
SESSION_DRIVER = cookie
CACHE_DRIVER = array
QUEUE_CONNECTION = sync
LOG_CHANNEL = stderr

# File Storage
FILESYSTEM_DISK = public
```

#### Optional Variables (Recommended):

```
# Mail (Mailtrap)
MAIL_MAILER = smtp
MAIL_HOST = sandbox.smtp.mailtrap.io
MAIL_PORT = 2525
MAIL_USERNAME = xxx
MAIL_PASSWORD = xxx
MAIL_ENCRYPTION = tls
MAIL_FROM_ADDRESS = noreply@jastiphype.com
MAIL_FROM_NAME = JastipHype

# Midtrans (Sandbox)
MIDTRANS_SERVER_KEY = SB-Mid-server-xxx
MIDTRANS_CLIENT_KEY = SB-Mid-client-xxx
MIDTRANS_IS_PRODUCTION = false
MIDTRANS_IS_SANITIZED = true
MIDTRANS_IS_3DS = true

# RajaOngkir
RAJAONGKIR_API_KEY = xxx
```

### 7. Deploy!

Klik **Deploy** button.

Tunggu 2-5 menit. Vercel akan:
1. Clone repository
2. Install dependencies (composer & npm)
3. Build assets (npm run build)
4. Deploy ke serverless

### 8. Update APP_URL

Setelah deploy berhasil:
1. Copy URL Vercel (contoh: `https://jastiphype-xxx.vercel.app`)
2. Kembali ke **Settings** → **Environment Variables**
3. Edit `APP_URL` dengan URL yang baru
4. **Redeploy**: Deployments → Latest → **Redeploy**

### 9. Setup Database Schema

```bash
# Update .env lokal dengan Railway credentials
DB_CONNECTION=pgsql
DB_HOST=containers-us-west-xxx.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=xxx

# Run migrations
php artisan migrate --force

# Seed data (optional)
php artisan db:seed --force
```

## ✅ Verifikasi Deployment

Test website Anda:

- [ ] Homepage load tanpa error
- [ ] Assets (CSS/JS) load dengan benar
- [ ] Register akun baru
- [ ] Login
- [ ] Browse products
- [ ] Add to cart
- [ ] Checkout (test dengan Midtrans sandbox)

## 🐛 Troubleshooting

### Error: "No Output Directory named 'dist'"
✅ **FIXED!** vercel.json sudah di-update dengan `"outputDirectory": "public"`

### Error: "php: error while loading shared libraries: libssl.so.10"
✅ **FIXED!** package.json sudah di-update dengan `"engines": { "node": "18.x" }`

### Error: "Database connection failed"
- Cek Railway database masih running
- Verify semua DB_* variables di Vercel
- Test koneksi dari local dulu

### Error: "500 Internal Server Error"
1. Set `APP_DEBUG=true` di Vercel (temporary)
2. Redeploy
3. Buka website, lihat error message
4. Fix error
5. Set `APP_DEBUG=false` kembali

### Error: "Session not working"
- Pastikan `SESSION_DRIVER=cookie`
- Pastikan `APP_URL` benar
- Clear browser cookies

### Assets tidak load (404)
1. Pastikan `npm run build` berhasil lokal
2. Pastikan folder `public/build` ada
3. Check Vercel build logs
4. Redeploy dengan clear cache

## 📝 Perbedaan dengan Konfigurasi Lama

| Item | Lama | Baru (Fixed) |
|------|------|--------------|
| vercel.json functions | `"api/index.php"` | `"api/*.php"` |
| vercel.json runtime | `vercel-php@0.7.0` | `vercel-php@0.7.3` |
| vercel.json routes | Kompleks | Simplified |
| vercel.json env | Di vercel.json | Di Vercel Dashboard |
| package.json | No engines | `"node": "18.x"` |
| .gitignore | No .vercel | Added `.vercel` |

## 🎯 Kenapa Sekarang Bisa?

1. **Runtime PHP terbaru** (`0.7.3`) lebih stabil
2. **Node 18.x** mengatasi masalah libssl.so
3. **Routes simplified** lebih mudah di-handle Vercel
4. **Environment variables** di dashboard lebih aman
5. **outputDirectory** explicit di vercel.json

## 📚 Referensi

Tutorial ini berdasarkan:
- [Deploy Laravel 11 on Vercel (2024)](https://edjohnsonwilliams.co.uk/blog/2024-06-04-deploy-laravel-11-for-free-on-vercel-in-2024/)
- [Livewire on Vercel (Oct 2024)](https://www.elven.dev/articles/2024-10-19.livewire-on-vercel)
- [Vercel PHP Runtime](https://github.com/vercel-community/php)

## 🎉 Selesai!

Jika semua step diikuti dengan benar, website Anda sekarang sudah live di Vercel!

**Next Steps:**
1. Test semua fitur
2. Setup custom domain (optional)
3. Enable production Midtrans
4. Setup monitoring

Good luck! 🚀
