# ⚡ Quick Deploy Guide - Vercel + Railway

## 🎯 Masalah Sudah Diperbaiki!

✅ Error "No Output Directory named 'dist'" - **FIXED!**
✅ Konfigurasi vercel.json - **UPDATED!**
✅ Node engine issue - **FIXED!**

**Baca:** `VERCEL_DEPLOY_FIXED.md` untuk panduan lengkap

---

## 🚀 Deploy Sekarang (15 menit)

### 1️⃣ Railway Database (3 menit)

```
https://railway.app
→ New Project → PostgreSQL
→ Copy credentials
```

### 2️⃣ Generate APP_KEY (1 menit)

```bash
php artisan key:generate --show
```

Copy output: `base64:xxx...`

### 3️⃣ Push ke GitHub (1 menit)

```bash
git push origin master
```

### 4️⃣ Deploy Vercel (5 menit)

```
https://vercel.com
→ Import repository
→ Framework: Other
→ Add Environment Variables (lihat bawah)
→ Deploy!
```

### 5️⃣ Setup Database (5 menit)

```bash
# Update .env lokal dengan Railway
php artisan migrate --force
php artisan db:seed --force
```

---

## 🔑 Environment Variables (Copy ke Vercel)

**Minimal yang WAJIB:**

```
APP_KEY = base64:xxx
APP_ENV = production
APP_DEBUG = false
APP_URL = https://your-app.vercel.app

APP_CONFIG_CACHE = /tmp/config.php
APP_EVENTS_CACHE = /tmp/events.php
APP_PACKAGES_CACHE = /tmp/packages.php
APP_ROUTES_CACHE = /tmp/routes.php
APP_SERVICES_CACHE = /tmp/services.php
VIEW_COMPILED_PATH = /tmp

DB_CONNECTION = pgsql
DB_HOST = xxx.railway.app
DB_PORT = 5432
DB_DATABASE = railway
DB_USERNAME = postgres
DB_PASSWORD = xxx

SESSION_DRIVER = cookie
CACHE_DRIVER = array
QUEUE_CONNECTION = sync
LOG_CHANNEL = stderr
FILESYSTEM_DISK = public
```

**Lengkapnya:** Lihat `VERCEL_DEPLOY_FIXED.md`

---

## ✅ Test Deployment

- [ ] Homepage load
- [ ] Register/Login works
- [ ] Products muncul
- [ ] Cart works
- [ ] Checkout works

---

## 🐛 Masih Error?

**Build error?**
→ Check Vercel build logs

**Database error?**
→ Verify Railway credentials di Vercel

**500 error?**
→ Set `APP_DEBUG=true`, check logs, fix, set back to `false`

**Assets 404?**
→ Run `npm run build` lokal, pastikan `public/build` ada

---

## 📚 Dokumentasi Lengkap

- **VERCEL_DEPLOY_FIXED.md** ← Baca ini untuk detail lengkap!
- **TROUBLESHOOTING.md** - Solusi masalah umum
- **VERCEL_ENV_VARIABLES.md** - Environment variables reference

---

**Estimasi: 15-20 menit** ⏱️

**Good luck!** 🚀
