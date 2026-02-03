# 🔍 Analisis Detail Masalah Deployment Vercel - HTTP 500 Error

## 📊 Diagnosis Masalah

Berdasarkan research dan analisis kode, berikut adalah **semua kemungkinan masalah** yang bisa menyebabkan HTTP 500 error pada deployment Laravel ke Vercel:

---

## 🎯 Masalah #1: APP_KEY Tidak Di-Set (PALING UMUM - 80%)

### Gejala:
- HTTP 500 error tanpa pesan detail
- Jika APP_DEBUG=true, muncul: "No application encryption key has been specified"

### Penyebab:
Laravel **WAJIB** memiliki APP_KEY untuk enkripsi session, cookies, dan data sensitif. Tanpa ini, aplikasi akan crash dengan error 500.

### Cara Cek:
1. Buka Vercel Dashboard → Settings → Environment Variables
2. Cari variable `APP_KEY`
3. Jika tidak ada atau kosong = INI MASALAHNYA!

### Solusi:
```bash
# Di local terminal
php artisan key:generate --show

# Output contoh:
# base64:abcd1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890=

# COPY SEMUA (termasuk "base64:")
```

**Set di Vercel:**
1. Settings → Environment Variables
2. Add New Variable:
   - Name: `APP_KEY`
   - Value: `base64:abcd1234...` (paste dari command di atas)
   - Environment: Production, Preview, Development (pilih semua)
3. Save
4. Deployments → Latest → **Redeploy**

### ⚠️ PENTING:
- APP_KEY harus **PERSIS** seperti output dari `php artisan key:generate --show`
- Harus include prefix `base64:`
- Jangan ada spasi di awal/akhir
- Jangan pakai quotes

---

## 🎯 Masalah #2: Cache Paths Tidak Writable (60%)

### Gejala:
- Error: "The stream or file could not be opened"
- Error: "Permission denied"
- Error 500 setelah APP_KEY di-set

### Penyebab:
Vercel serverless environment adalah **read-only** kecuali folder `/tmp`. Laravel perlu write ke cache files, tapi default path-nya tidak writable di Vercel.

### Solusi:
Set environment variables ini di Vercel:

```
APP_CONFIG_CACHE = /tmp/config.php
APP_EVENTS_CACHE = /tmp/events.php
APP_PACKAGES_CACHE = /tmp/packages.php
APP_ROUTES_CACHE = /tmp/routes.php
APP_SERVICES_CACHE = /tmp/services.php
VIEW_COMPILED_PATH = /tmp
LOG_CHANNEL = stderr
```

**Kenapa `/tmp`?**
- `/tmp` adalah satu-satunya folder writable di Vercel serverless
- Laravel akan write cache files ke sini
- `LOG_CHANNEL=stderr` agar logs muncul di Vercel Function Logs

---

## 🎯 Masalah #3: Session Driver Tidak Compatible (50%)

### Gejala:
- Error: "Session store not set on request"
- Error: "Unable to write to session"
- Login tidak work, session hilang

### Penyebab:
Default Laravel session driver (`file` atau `database`) tidak work di Vercel karena:
- `file`: Filesystem read-only
- `database`: Butuh database connection (belum setup)

### Solusi:
Set di Vercel Environment Variables:

```
SESSION_DRIVER = cookie
SESSION_LIFETIME = 120
```

**Kenapa `cookie`?**
- Cookie-based session work di serverless
- Tidak butuh filesystem atau database
- Data session disimpan di browser user (encrypted)

**Alternatif (jika butuh database session):**
```
SESSION_DRIVER = database
```
Tapi harus setup database dulu (Railway PostgreSQL).

---

## 🎯 Masalah #4: Database Connection Failed (40%)

### Gejala:
- Error: "SQLSTATE[08006] Connection refused"
- Error: "could not find driver"
- Error 500 saat akses halaman yang query database

### Penyebab:
1. Database credentials salah atau tidak di-set
2. Railway database tidak running
3. DB_CONNECTION salah (pakai `mysql` padahal Railway pakai PostgreSQL)

### Cara Cek:
1. **Check Railway database:**
   - Buka https://railway.app
   - Check database status = "Active"
   - Jika "Sleeping" atau "Stopped" → Start database

2. **Check Vercel environment variables:**
   ```
   DB_CONNECTION = pgsql (BUKAN mysql!)
   DB_HOST = containers-us-west-xxx.railway.app
   DB_PORT = 5432
   DB_DATABASE = railway
   DB_USERNAME = postgres
   DB_PASSWORD = xxx
   ```

### Solusi:
**Option A: Pakai Database (Railway PostgreSQL)**

1. Setup Railway database:
   - https://railway.app → New Project → PostgreSQL
   - Copy credentials dari Variables tab

2. Set di Vercel:
   ```
   DB_CONNECTION = pgsql
   DB_HOST = (dari Railway)
   DB_PORT = 5432
   DB_DATABASE = railway
   DB_USERNAME = postgres
   DB_PASSWORD = (dari Railway)
   ```

3. Run migrations:
   ```bash
   # Update .env lokal dengan Railway credentials
   php artisan migrate --force
   ```

**Option B: Disable Database (Temporary)**

Jika belum mau setup database, comment out database queries di code:
- Disable authentication
- Disable database-dependent features
- Test deployment dulu

---

## 🎯 Masalah #5: Environment Variables Tidak Terbaca (30%)

### Gejala:
- Sudah set env vars di Vercel tapi masih error
- `env('APP_KEY')` return null
- Config cache issue

### Penyebab:
1. **Belum redeploy** setelah add env vars
2. **Typo** di nama variable (case-sensitive!)
3. **Environment** salah (set di Preview tapi deploy ke Production)
4. **Config cached** di local, di-commit ke Git

### Solusi:

**1. Redeploy setelah add env vars:**
- Vercel **TIDAK** auto-reload env vars
- Harus manual redeploy: Deployments → Redeploy

**2. Check typo:**
- `APP_KEY` bukan `APPKEY` atau `App_Key`
- Case-sensitive!

**3. Set untuk semua environment:**
- Production ✅
- Preview ✅
- Development ✅

**4. Clear config cache di local:**
```bash
php artisan config:clear
php artisan cache:clear
```

Jangan commit file cache:
- `bootstrap/cache/config.php`
- `bootstrap/cache/services.php`

---

## 🎯 Masalah #6: Composer Dependencies Missing (20%)

### Gejala:
- Error: "Class 'X' not found"
- Error: "Interface 'Y' not found"
- Build berhasil tapi runtime error

### Penyebab:
1. `composer install` gagal di Vercel
2. Dependencies tidak compatible dengan PHP version
3. `composer.lock` outdated

### Cara Cek:
Check Vercel Build Logs:
1. Deployments → Latest
2. Scroll ke bagian "Installing Composer dependencies"
3. Lihat ada error atau warning?

### Solusi:

**1. Update composer.lock:**
```bash
composer update
git add composer.lock
git commit -m "Update composer.lock"
git push
```

**2. Check PHP version compatibility:**
Di `composer.json`:
```json
{
  "require": {
    "php": "^8.2"
  }
}
```

Vercel PHP runtime: `vercel-php@0.7.3` support PHP 8.2

**3. Clear composer cache:**
```bash
composer clear-cache
composer install
```

---

## 🎯 Masalah #7: Node.js Build Failed (15%)

### Gejala:
- Build error: "Found invalid or discontinued Node.js Version"
- Assets tidak ter-generate
- `public/build` folder kosong

### Penyebab:
Node.js version tidak compatible atau `npm run build` gagal.

### Solusi:

**1. Set Node.js version di package.json:**
```json
{
  "engines": {
    "node": "24.x"
  }
}
```

**2. Test build lokal:**
```bash
npm install
npm run build

# Check public/build folder ada
dir public\build  # Windows
ls public/build   # Linux/Mac
```

**3. Check Vercel Build Logs:**
- Lihat bagian "Building..."
- Pastikan `npm run build` berhasil
- Pastikan `public/build` ter-generate

---

## 🎯 Masalah #8: Routes Configuration (10%)

### Gejala:
- Homepage error 500
- Subpages 404
- Assets load tapi halaman error

### Penyebab:
`vercel.json` routes tidak benar.

### Solusi:

Check `vercel.json`:
```json
{
  "version": 2,
  "outputDirectory": "public",
  "functions": {
    "api/*.php": {
      "runtime": "vercel-php@0.7.3"
    }
  },
  "routes": [
    {
      "src": "/build/(.*)",
      "dest": "/public/build/$1"
    },
    {
      "src": "/(.*\\.(?:css|js|png|jpg|jpeg|gif|svg|ico|ttf|woff|woff2|eot|otf|webp|avif|txt))$",
      "dest": "/public/$1"
    },
    {
      "src": "/(.*)",
      "dest": "/api/index.php"
    }
  ]
}
```

**PENTING:**
- Route order matters!
- `/build/(.*)` harus sebelum `/(.*)`
- Assets route harus sebelum catch-all route

---

## 🎯 Masalah #9: Trust Proxies (5%)

### Gejala:
- HTTPS redirect loop
- CSRF token mismatch
- URL generation salah

### Penyebab:
Laravel tidak trust Vercel proxy.

### Solusi:

Check `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');
})
```

✅ Sudah di-set di project ini!

---

## 🎯 Masalah #10: Memory/Timeout Limit (5%)

### Gejala:
- Error: "Function invocation timeout"
- Error: "Memory limit exceeded"
- Slow response, lalu 500

### Penyebab:
Vercel free tier limits:
- Timeout: 10 seconds
- Memory: 1024 MB

### Solusi:

**1. Optimize queries:**
- Use eager loading
- Add database indexes
- Cache results

**2. Reduce dependencies:**
- Remove unused packages
- Optimize autoloader

**3. Upgrade Vercel plan** (jika perlu)

---

## 📋 Checklist Debug Lengkap

### Step 1: Enable Debug Mode
- [ ] Set `APP_DEBUG=true` di Vercel
- [ ] Redeploy
- [ ] Buka website, screenshot error message

### Step 2: Check Environment Variables
- [ ] `APP_KEY` ada dan benar format
- [ ] All cache paths set ke `/tmp`
- [ ] `SESSION_DRIVER=cookie`
- [ ] `CACHE_DRIVER=array`
- [ ] `LOG_CHANNEL=stderr`

### Step 3: Check Vercel Logs
- [ ] Deployments → View Function Logs
- [ ] Screenshot error message
- [ ] Identify error type

### Step 4: Check Build Logs
- [ ] `composer install` berhasil?
- [ ] `npm run build` berhasil?
- [ ] `public/build` ter-generate?

### Step 5: Test Database (jika pakai)
- [ ] Railway database running?
- [ ] Credentials benar?
- [ ] `DB_CONNECTION=pgsql`?
- [ ] Migrations sudah dijalankan?

### Step 6: Test Local
- [ ] `npm run build` berhasil lokal?
- [ ] `php artisan serve` work lokal?
- [ ] Test dengan production settings

---

## 🚀 Solusi Tercepat (Quick Fix)

**Jika bingung, ikuti ini step-by-step:**

### 1. Generate APP_KEY (2 menit)
```bash
php artisan key:generate --show
```
Copy output.

### 2. Set Minimal Environment Variables di Vercel (5 menit)

Add variables ini di Vercel Settings → Environment Variables:

```
APP_KEY = base64:xxx (dari step 1)
APP_ENV = production
APP_DEBUG = true
APP_URL = https://jastiphype.vercel.app

APP_CONFIG_CACHE = /tmp/config.php
APP_EVENTS_CACHE = /tmp/events.php
APP_PACKAGES_CACHE = /tmp/packages.php
APP_ROUTES_CACHE = /tmp/routes.php
APP_SERVICES_CACHE = /tmp/services.php
VIEW_COMPILED_PATH = /tmp

SESSION_DRIVER = cookie
CACHE_DRIVER = array
QUEUE_CONNECTION = sync
LOG_CHANNEL = stderr
FILESYSTEM_DISK = public
```

### 3. Redeploy (2 menit)
- Deployments → Latest → Redeploy
- Tunggu selesai

### 4. Check Website (1 menit)
- Refresh https://jastiphype.vercel.app
- Jika masih error, lihat error message detail
- Screenshot dan analisa

### 5. Check Function Logs (2 menit)
- Deployments → View Function Logs
- Lihat error message
- Identify masalah spesifik

---

## 📞 Prioritas Masalah

Berdasarkan probabilitas:

1. **APP_KEY tidak di-set** (80%) ← **CHECK INI DULU!**
2. Cache paths tidak writable (60%)
3. Session driver tidak compatible (50%)
4. Database connection failed (40%)
5. Environment variables tidak terbaca (30%)
6. Composer dependencies missing (20%)
7. Node.js build failed (15%)
8. Routes configuration (10%)
9. Trust proxies (5%)
10. Memory/timeout limit (5%)

---

## 🎯 Next Steps

1. **Set APP_KEY** di Vercel (PRIORITAS #1!)
2. **Set cache paths** ke `/tmp`
3. **Set SESSION_DRIVER** ke `cookie`
4. **Redeploy**
5. **Check logs** untuk error spesifik
6. **Fix** berdasarkan error message

---

**Kemungkinan besar masalahnya:** APP_KEY belum di-set atau cache paths tidak writable.

**Estimasi fix:** 10-15 menit jika ikuti step-by-step di atas.

Good luck! 🚀
