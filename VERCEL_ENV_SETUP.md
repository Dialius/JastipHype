# 🚀 VERCEL ENVIRONMENT VARIABLES SETUP

## ✅ CODE FIX SUDAH SELESAI!

File `app/Providers/AppServiceProvider.php` sudah di-update dan di-push ke GitHub.

Vercel akan otomatis redeploy dalam 2-3 menit.

---

## 🔑 LANGKAH SELANJUTNYA: Set Environment Variables

### Step 1: Generate APP_KEY

Buka terminal dan jalankan:

```bash
php artisan key:generate --show
```

**Output contoh:**
```
base64:abcd1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890=
```

**COPY SEMUA** (termasuk "base64:")

---

### Step 2: Buka Vercel Dashboard

1. Buka: https://vercel.com/dialius-projects/jastiphype
2. Klik **Settings** (di menu atas)
3. Klik **Environment Variables** (di sidebar kiri)

---

### Step 3: Add Environment Variables

Klik **Add New** dan masukkan satu per satu:

#### 🔴 CRITICAL (WAJIB SET DULU!)

| Name | Value |
|------|-------|
| `APP_KEY` | `base64:xxx` (dari Step 1) |
| `VIEW_COMPILED_PATH` | `/tmp` |
| `APP_CONFIG_CACHE` | `/tmp/config.php` |
| `APP_SERVICES_CACHE` | `/tmp/services.php` |
| `SESSION_DRIVER` | `cookie` |
| `CACHE_DRIVER` | `array` |
| `LOG_CHANNEL` | `stderr` |

#### 🟡 PENTING (Recommended)

| Name | Value |
|------|-------|
| `APP_NAME` | `JastipHype` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://jastiphype.vercel.app` |
| `APP_EVENTS_CACHE` | `/tmp/events.php` |
| `APP_PACKAGES_CACHE` | `/tmp/packages.php` |
| `APP_ROUTES_CACHE` | `/tmp/routes.php` |
| `QUEUE_CONNECTION` | `sync` |
| `FILESYSTEM_DISK` | `public` |

#### 🟢 OPTIONAL (Untuk sementara set false dulu)

| Name | Value |
|------|-------|
| `SESSION_LIFETIME` | `120` |

---

### Step 4: Pilih Environment

Untuk setiap variable, pilih environment:
- ✅ **Production** (WAJIB)
- ✅ **Preview** (Optional)
- ✅ **Development** (Optional)

**Recommended:** Centang semua (Production, Preview, Development)

---

### Step 5: Save & Redeploy

1. Setelah add semua variables, klik **Save**
2. Kembali ke **Deployments** tab
3. Klik deployment terbaru
4. Klik **Redeploy** button
5. Tunggu 2-5 menit

---

## 🎯 Cara Cepat (Copy-Paste)

Jika Vercel support bulk import, copy ini:

```env
APP_NAME=JastipHype
APP_ENV=production
APP_KEY=base64:xxx
APP_DEBUG=false
APP_URL=https://jastiphype.vercel.app

VIEW_COMPILED_PATH=/tmp
APP_CONFIG_CACHE=/tmp/config.php
APP_EVENTS_CACHE=/tmp/events.php
APP_PACKAGES_CACHE=/tmp/packages.php
APP_ROUTES_CACHE=/tmp/routes.php
APP_SERVICES_CACHE=/tmp/services.php

SESSION_DRIVER=cookie
SESSION_LIFETIME=120
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
FILESYSTEM_DISK=public
```

**JANGAN LUPA:** Ganti `APP_KEY=base64:xxx` dengan hasil dari `php artisan key:generate --show`

---

## 📋 Checklist

- [ ] Generate APP_KEY: `php artisan key:generate --show`
- [ ] Buka Vercel Dashboard → Settings → Environment Variables
- [ ] Add `APP_KEY` (CRITICAL!)
- [ ] Add `VIEW_COMPILED_PATH=/tmp` (CRITICAL!)
- [ ] Add `APP_CONFIG_CACHE=/tmp/config.php` (CRITICAL!)
- [ ] Add `APP_SERVICES_CACHE=/tmp/services.php` (CRITICAL!)
- [ ] Add `SESSION_DRIVER=cookie` (CRITICAL!)
- [ ] Add `CACHE_DRIVER=array` (CRITICAL!)
- [ ] Add `LOG_CHANNEL=stderr` (CRITICAL!)
- [ ] Add semua variables lainnya (recommended)
- [ ] Save semua
- [ ] Redeploy di Vercel
- [ ] Tunggu 2-5 menit
- [ ] Test website: https://jastiphype.vercel.app

---

## 🐛 Troubleshooting

### Jika masih error setelah set env vars:

1. **Verify APP_KEY format:**
   - Harus include prefix `base64:`
   - Contoh: `base64:abcd1234...`

2. **Verify VIEW_COMPILED_PATH:**
   - Harus `/tmp` (bukan `/tmp/views`)

3. **Check Vercel logs:**
   - Deployments → View Function Logs
   - Lihat error message

4. **Try set APP_DEBUG=true:**
   - Temporary untuk lihat error detail
   - Jangan lupa set kembali ke `false` setelah fix

---

## ✅ Setelah Website Jalan

1. **Set APP_DEBUG=false** (jika masih true)
2. **Test semua fitur:**
   - Homepage
   - Product pages
   - Cart
   - Checkout (jika ada)
   - Admin panel (jika ada)

3. **Setup Database Railway** (optional):
   - Jika perlu database, setup Railway PostgreSQL
   - Add `DB_*` environment variables ke Vercel

---

## 🎉 Summary

**Yang sudah selesai:**
- ✅ Code fix di `AppServiceProvider.php`
- ✅ Commit dan push ke GitHub
- ✅ Vercel auto-deploy (tunggu 2-3 menit)

**Yang perlu dilakukan:**
- ⏳ Generate APP_KEY
- ⏳ Set environment variables di Vercel
- ⏳ Redeploy
- ⏳ Test website

**Estimasi waktu:** 10-15 menit

---

**Good luck!** 🚀

Jika ada error setelah set env vars, screenshot error messagenya dan kasih tau saya!
