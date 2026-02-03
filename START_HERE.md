# 🎯 START HERE - Deploy JastipHype

**Mau deploy ke Vercel + Railway? Mulai di sini!**

---

## 🚀 3 Langkah Mudah (20 menit)

### 1️⃣ Setup Database (5 menit)
```
https://railway.app
→ New Project → PostgreSQL
→ Copy credentials
```

### 2️⃣ Deploy Website (10 menit)
```
https://vercel.com
→ Import dari GitHub
→ Paste environment variables
→ Deploy!
```

### 3️⃣ Setup Database Schema (5 menit)
```bash
php artisan migrate --force
php artisan db:seed --force
```

---

## 📚 Dokumentasi (Pilih sesuai kebutuhan)

### 🆕 Baru mulai?
→ **[DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md)** - Ringkasan cepat

### 📋 Mau step-by-step detail?
→ **[DEPLOY_CHECKLIST.md](DEPLOY_CHECKLIST.md)** - Checklist lengkap

### 🔑 Butuh environment variables?
→ **[VERCEL_ENV_VARIABLES.md](VERCEL_ENV_VARIABLES.md)** - Copy-paste ready

### 🐛 Ada error?
→ **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** - Solusi masalah umum

### 📖 Mau baca semua detail?
→ **[VERCEL_DEPLOYMENT.md](VERCEL_DEPLOYMENT.md)** - Panduan lengkap

---

## ⚡ Quick Commands

```bash
# Generate APP_KEY
php artisan key:generate --show

# Build assets
npm run build

# Run migrations (dengan Railway DB)
php artisan migrate --force

# Seed database
php artisan db:seed --force
```

---

## 🔑 Environment Variables Minimal

Copy ini ke Vercel:

```env
APP_KEY=base64:xxx                    # Generate dulu!
APP_URL=https://your-app.vercel.app
DB_CONNECTION=pgsql
DB_HOST=xxx.railway.app               # Dari Railway
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=xxx                       # Dari Railway
SESSION_DRIVER=cookie
CACHE_DRIVER=array
```

---

## ✅ Checklist Cepat

- [ ] Railway database dibuat
- [ ] Credentials di-copy
- [ ] Repository di-push ke GitHub
- [ ] Vercel project dibuat
- [ ] Environment variables di-set
- [ ] Deploy berhasil
- [ ] Migrations dijalankan
- [ ] Website bisa diakses

---

## 🎉 Selesai!

Jika semua ✅, website Anda sudah live!

**Test:**
- Homepage load?
- Bisa register/login?
- Products muncul?
- Cart works?

---

## 🆘 Butuh Bantuan?

**Error saat build?**
→ [TROUBLESHOOTING.md#build-errors](TROUBLESHOOTING.md#-build-errors)

**Database connection failed?**
→ [TROUBLESHOOTING.md#database-errors](TROUBLESHOOTING.md#-database-errors)

**Masalah lain?**
→ [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

---

**Estimasi waktu: 20-30 menit** ⏱️

**Good luck!** 🚀
