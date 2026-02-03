# 🚀 COPY-PASTE Environment Variables ke Vercel

## ⚠️ PENTING: Sudah Ada File `.env.vercel`

Saya sudah buat file `.env.vercel` yang berisi **SEMUA** environment variables yang benar untuk Vercel.

File ini **TIDAK** akan di-commit ke GitHub (sudah di-ignore) karena berisi credentials.

---

## 📋 CARA TERCEPAT: Bulk Import (Jika Vercel Support)

### Step 1: Buka File `.env.vercel`

File ini ada di root project kamu.

### Step 2: Copy Semua Isi File

Copy dari baris pertama sampai terakhir (skip comment lines yang dimulai dengan `#`).

### Step 3: Paste ke Vercel

1. Buka: https://vercel.com/dialius-projects/jastiphype
2. Settings → Environment Variables
3. Jika ada tombol **"Bulk Import"** atau **"Import from .env"**, klik itu
4. Paste semua isi `.env.vercel`
5. Pilih environment: Production, Preview, Development
6. Save

---

## 📝 CARA MANUAL: Add Satu per Satu

Jika Vercel tidak support bulk import, add manual:

### CRITICAL Variables (WAJIB!)

```
VIEW_COMPILED_PATH=/tmp
APP_CONFIG_CACHE=/tmp/config.php
APP_SERVICES_CACHE=/tmp/services.php
APP_EVENTS_CACHE=/tmp/events.php
APP_PACKAGES_CACHE=/tmp/packages.php
APP_ROUTES_CACHE=/tmp/routes.php
SESSION_DRIVER=cookie
CACHE_STORE=array
CACHE_DRIVER=array
LOG_CHANNEL=stderr
```

### Core Application

```
APP_NAME=JastipHype
APP_ENV=production
APP_KEY=base64:Ksc82+I7kMwWoOGGzSFWV/VvTND1VcXZQQG5v5FVWUI=
APP_DEBUG=false
APP_URL=https://jastiphype.vercel.app
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

### Database (Railway MySQL)

```
DB_CONNECTION=mysql
DB_HOST=caboose.proxy.rlwy.net
DB_PORT=46434
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=mOkytLFzghktXHUaMGWDvyWlHsSHCfyX
```

### Mail (Gmail SMTP)

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=putradavinza9@gmail.com
MAIL_PASSWORD=pxluzdwoqzwwdxgo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=jastiphype@gmail.com
MAIL_FROM_NAME=JastipHype
```

### Google OAuth

```
GOOGLE_CLIENT_ID=69087409346-s4p6help1912k7svmgjrfnmf6bbrq44a.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URL=https://jastiphype.vercel.app/auth/google/callback
```

### RajaOngkir

```
RAJAONGKIR_API_KEY=DL8eP5LD7c09c1638d2c213eYrBangX6
RAJAONGKIR_TYPE=starter
RAJAONGKIR_ORIGIN=151
```

### Midtrans

```
MIDTRANS_MERCHANT_ID=G689132907
MIDTRANS_CLIENT_KEY=Mid-client-DTSB6VSWG4ReInb0
MIDTRANS_SERVER_KEY=Mid-server-ezqCCqbhe-zfF83kGA1ETmVy
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### Other

```
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
SESSION_LIFETIME=120
LOG_LEVEL=error
VITE_APP_NAME=JastipHype
```

---

## ✅ Checklist

- [ ] Buka Vercel Dashboard
- [ ] Settings → Environment Variables
- [ ] Copy dari `.env.vercel` atau add manual
- [ ] Pilih environment: Production, Preview, Development
- [ ] Save semua
- [ ] Redeploy

---

## 🎯 Yang Paling Penting

**Jangan skip ini:**

1. `VIEW_COMPILED_PATH=/tmp` - **CRITICAL!**
2. `APP_CONFIG_CACHE=/tmp/config.php` - **CRITICAL!**
3. `APP_SERVICES_CACHE=/tmp/services.php` - **CRITICAL!**
4. `SESSION_DRIVER=cookie` - **CRITICAL!** (bukan `database`)
5. `CACHE_DRIVER=array` - **CRITICAL!** (bukan `database`)
6. `LOG_CHANNEL=stderr` - **CRITICAL!**

Tanpa ini, website **TIDAK AKAN JALAN** di Vercel!

---

## 🐛 Troubleshooting

### Error: "Target class [view] does not exist"

**Penyebab:** Environment variables belum di-set atau salah.

**Solusi:**
1. Verify `VIEW_COMPILED_PATH=/tmp` sudah di-set
2. Verify `APP_CONFIG_CACHE=/tmp/config.php` sudah di-set
3. Verify `SESSION_DRIVER=cookie` (bukan `database`)
4. Redeploy

### Error: "Session store not configured"

**Penyebab:** `SESSION_DRIVER` masih `database`.

**Solusi:**
1. Set `SESSION_DRIVER=cookie`
2. Redeploy

### Error: "Cache store not configured"

**Penyebab:** `CACHE_DRIVER` masih `database`.

**Solusi:**
1. Set `CACHE_DRIVER=array`
2. Set `CACHE_STORE=array`
3. Redeploy

---

## 🚀 Setelah Set Environment Variables

1. **Redeploy:**
   - Deployments → Latest → Redeploy
   - Tunggu 2-5 menit

2. **Test:**
   - Buka https://jastiphype.vercel.app
   - Jika jalan: 🎉 Selesai!
   - Jika error: Screenshot dan kasih tau saya

3. **Set APP_DEBUG=false:**
   - Jika website sudah jalan
   - Update `APP_DEBUG=false` di Vercel
   - Redeploy

---

**Estimasi waktu:** 10-15 menit

**Good luck!** 🚀
