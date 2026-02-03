# 🐛 Debug HTTP ERROR 500 - Vercel Deployment

## ❌ Error yang Muncul

```
This page isn't working
jastiphype.vercel.app is currently unable to handle this request.
HTTP ERROR 500
```

## 🔍 Penyebab Umum Error 500

1. **APP_KEY tidak di-set** atau salah format
2. **Environment variables** belum di-set di Vercel
3. **Database connection** gagal
4. **Cache paths** tidak writable
5. **PHP error** di aplikasi

## ✅ Langkah Debug

### 1. Enable Debug Mode (TEMPORARY!)

Di Vercel Dashboard:
1. Settings → Environment Variables
2. Edit `APP_DEBUG` → Set ke `true`
3. Deployments → Latest → **Redeploy**
4. Tunggu selesai, buka website lagi
5. **Lihat error message detail**

### 2. Check Vercel Function Logs

Di Vercel Dashboard:
1. Deployments → Latest deployment
2. Klik **View Function Logs**
3. Lihat error message di logs
4. Screenshot dan analisa

### 3. Verify Environment Variables

**WAJIB ada di Vercel Settings → Environment Variables:**

```
✅ APP_KEY = base64:xxx (PENTING!)
✅ APP_ENV = production
✅ APP_DEBUG = true (untuk debug, nanti set false)
✅ APP_URL = https://jastiphype.vercel.app

✅ APP_CONFIG_CACHE = /tmp/config.php
✅ APP_EVENTS_CACHE = /tmp/events.php
✅ APP_PACKAGES_CACHE = /tmp/packages.php
✅ APP_ROUTES_CACHE = /tmp/routes.php
✅ APP_SERVICES_CACHE = /tmp/services.php
✅ VIEW_COMPILED_PATH = /tmp

✅ DB_CONNECTION = pgsql
✅ DB_HOST = xxx.railway.app
✅ DB_PORT = 5432
✅ DB_DATABASE = railway
✅ DB_USERNAME = postgres
✅ DB_PASSWORD = xxx

✅ SESSION_DRIVER = cookie
✅ CACHE_DRIVER = array
✅ QUEUE_CONNECTION = sync
✅ LOG_CHANNEL = stderr
✅ FILESYSTEM_DISK = public
```

### 4. Generate APP_KEY (Jika Belum)

```bash
# Di local
php artisan key:generate --show

# Output contoh:
# base64:abcd1234567890...

# Copy SEMUA output (termasuk "base64:")
# Paste ke Vercel Environment Variables
```

### 5. Check Database Connection

**Pastikan Railway database running:**
1. Buka https://railway.app
2. Check database status = "Active"
3. Verify credentials masih sama

**Test koneksi dari local:**
```bash
# Update .env lokal dengan Railway credentials
DB_CONNECTION=pgsql
DB_HOST=xxx.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=xxx

# Test koneksi
php artisan migrate --pretend
```

## 🔧 Solusi Berdasarkan Error

### Error: "No application encryption key has been specified"

**Solusi:**
1. Generate APP_KEY: `php artisan key:generate --show`
2. Copy output (dengan "base64:")
3. Add ke Vercel Environment Variables
4. Redeploy

### Error: "SQLSTATE[08006] Connection refused"

**Solusi:**
1. Check Railway database running
2. Verify DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
3. Pastikan DB_CONNECTION = pgsql (bukan mysql!)

### Error: "Class 'X' not found"

**Solusi:**
1. Check composer dependencies
2. Vercel build logs → pastikan `composer install` berhasil
3. Redeploy dengan clear cache

### Error: "The stream or file could not be opened"

**Solusi:**
1. Pastikan semua cache paths di-set:
   - APP_CONFIG_CACHE=/tmp/config.php
   - VIEW_COMPILED_PATH=/tmp
   - dll
2. Redeploy

## 📋 Checklist Debug

- [ ] APP_KEY sudah di-generate dan di-set di Vercel
- [ ] Semua environment variables sudah di-set
- [ ] Railway database running
- [ ] Database credentials benar
- [ ] APP_DEBUG=true untuk lihat error detail
- [ ] Check Vercel Function Logs
- [ ] Screenshot error message
- [ ] Migrations sudah dijalankan (optional untuk test)

## 🚨 PENTING

**Setelah debug selesai:**
1. Set `APP_DEBUG=false` kembali
2. Redeploy
3. Jangan biarkan debug mode di production!

## 📞 Next Steps

1. **Enable APP_DEBUG=true** di Vercel
2. **Redeploy**
3. **Buka website** → lihat error message detail
4. **Screenshot error** dan analisa
5. **Check Function Logs** di Vercel
6. **Fix error** berdasarkan message
7. **Set APP_DEBUG=false** kembali

---

**Kemungkinan besar:** APP_KEY belum di-set atau environment variables belum lengkap.

**Solusi tercepat:** Set semua environment variables di Vercel Dashboard, lalu redeploy.
