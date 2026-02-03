# 🔧 Troubleshooting Guide - Vercel + Railway

Panduan mengatasi masalah umum saat deployment ke Vercel dengan database Railway.

---

## 🚨 Build Errors

### ❌ Error: "No Output Directory named 'dist' found"

**Penyebab:** Vercel mencari folder `dist` tapi Laravel Vite menghasilkan `public/build`

**Solusi:** ✅ Sudah diperbaiki di `vercel.json`
```json
{
  "outputDirectory": "public"
}
```

**Verifikasi:**
```bash
npm run build
# Check folder public/build ada
```

---

### ❌ Error: "Build failed - npm install"

**Penyebab:** Dependencies tidak terinstall dengan benar

**Solusi:**
```bash
# Hapus node_modules dan package-lock.json
rm -rf node_modules package-lock.json

# Install ulang
npm install

# Test build
npm run build
```

---

### ❌ Error: "Vite build failed"

**Penyebab:** Error di JavaScript/CSS files

**Solusi:**
1. Check error message di build logs
2. Fix syntax errors di `resources/js` atau `resources/css`
3. Test locally:
```bash
npm run build
```

---

## 🗄️ Database Errors

### ❌ Error: "SQLSTATE[08006] Connection refused"

**Penyebab:** Tidak bisa connect ke Railway database

**Solusi:**
1. **Verify Railway database running:**
   - Buka Railway dashboard
   - Check database status (harus "Active")

2. **Check environment variables di Vercel:**
   ```
   DB_CONNECTION=pgsql (bukan mysql!)
   DB_HOST=containers-us-west-xxx.railway.app
   DB_PORT=5432
   DB_DATABASE=railway
   DB_USERNAME=postgres
   DB_PASSWORD=xxx
   ```

3. **Test koneksi dari local:**
   ```bash
   # Update .env dengan Railway credentials
   php artisan migrate --force
   ```

---

### ❌ Error: "SQLSTATE[42P01] Table doesn't exist"

**Penyebab:** Migrations belum dijalankan

**Solusi:**
```bash
# Update .env lokal dengan Railway credentials
DB_CONNECTION=pgsql
DB_HOST=xxx.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=xxx

# Run migrations
php artisan migrate --force

# Seed data (optional)
php artisan db:seed --force
```

---

### ❌ Error: "Too many connections"

**Penyebab:** Railway free tier memiliki limit koneksi

**Solusi:**
1. **Optimize database connections:**
   ```env
   DB_POOL_MIN=2
   DB_POOL_MAX=10
   ```

2. **Restart Railway database:**
   - Railway Dashboard → Database → Settings → Restart

---

## 🔑 Authentication Errors

### ❌ Error: "Invalid APP_KEY"

**Penyebab:** APP_KEY tidak di-set atau salah format

**Solusi:**
```bash
# Generate APP_KEY
php artisan key:generate --show

# Copy output (harus start dengan base64:)
# Contoh: base64:abcd1234...

# Add ke Vercel environment variables
APP_KEY=base64:abcd1234...

# Redeploy
```

---

### ❌ Error: "Session not working / Keep logging out"

**Penyebab:** Session driver tidak compatible dengan Vercel serverless

**Solusi:**
```env
# Di Vercel environment variables
SESSION_DRIVER=cookie (bukan database atau file!)
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

# Pastikan APP_URL benar
APP_URL=https://your-project.vercel.app
```

---

### ❌ Error: "CSRF token mismatch"

**Penyebab:** Session/cookie issues

**Solusi:**
1. **Check APP_URL:**
   ```env
   APP_URL=https://your-exact-domain.vercel.app
   ```

2. **Clear browser cookies**

3. **Check session settings:**
   ```env
   SESSION_DRIVER=cookie
   SESSION_SECURE_COOKIE=true (untuk HTTPS)
   ```

---

## 🎨 Asset Loading Errors

### ❌ Error: "Assets not loading (404 on CSS/JS)"

**Penyebab:** Build assets tidak ada atau routing salah

**Solusi:**
1. **Verify build berhasil:**
   ```bash
   npm run build
   # Check public/build folder ada
   ```

2. **Check vercel.json routes:**
   ```json
   {
     "src": "/build/(.*)",
     "dest": "/build/$1"
   }
   ```

3. **Redeploy:**
   - Push ke GitHub
   - Atau Vercel Dashboard → Redeploy

---

### ❌ Error: "Images not loading"

**Penyebab:** File storage di Vercel serverless bersifat ephemeral

**Solusi:**

**Option A: Use existing images (recommended untuk testing)**
- Images di `public/images` akan tetap ada
- Upload images via admin akan hilang setiap redeploy

**Option B: Use cloud storage (recommended untuk production)**
```env
# Setup S3/Cloudinary
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=xxx
AWS_SECRET_ACCESS_KEY=xxx
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

---

## 💳 Payment Gateway Errors

### ❌ Error: "Midtrans connection failed"

**Penyebab:** Midtrans credentials salah atau tidak di-set

**Solusi:**
1. **Verify credentials di Vercel:**
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxx (untuk sandbox)
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxx
   MIDTRANS_IS_PRODUCTION=false
   ```

2. **Get credentials:**
   - Login ke https://dashboard.midtrans.com
   - Settings → Access Keys
   - Use **Sandbox** keys untuk testing

3. **Test locally first:**
   ```bash
   # Update .env
   php artisan tinker
   >>> config('midtrans.server_key')
   ```

---

## 📧 Email Errors

### ❌ Error: "Mail sending failed"

**Penyebab:** SMTP credentials salah atau tidak di-set

**Solusi:**

**Option A: Use Mailtrap (testing)**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
MAIL_ENCRYPTION=tls
```

**Option B: Use log driver (development)**
```env
MAIL_MAILER=log
```

**Option C: Use real SMTP (production)**
```env
# Gmail example
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

---

## 🚀 Deployment Errors

### ❌ Error: "Function invocation timeout"

**Penyebab:** Request terlalu lama (Vercel limit: 10s free tier)

**Solusi:**
1. **Optimize queries:**
   - Add database indexes
   - Use eager loading
   - Cache results

2. **Upgrade Vercel plan** (jika perlu)

---

### ❌ Error: "Function size limit exceeded"

**Penyebab:** Deployment size terlalu besar

**Solusi:**
1. **Check .vercelignore:**
   ```
   /vendor
   /node_modules
   /tests
   ```

2. **Optimize dependencies:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

---

## 🔍 Debugging Tips

### Enable Debug Mode (Temporary)

```env
# Di Vercel environment variables
APP_DEBUG=true

# Redeploy
# Check error details di browser
# JANGAN LUPA set kembali ke false!
```

### Check Vercel Logs

1. Vercel Dashboard → Project
2. Deployments → Latest
3. **View Function Logs**
4. Look for errors

### Check Railway Logs

1. Railway Dashboard → Database
2. **Logs** tab
3. Look for connection errors

### Test Locally with Production Settings

```bash
# Update .env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql (Railway credentials)

# Test
php artisan serve
```

---

## 📞 Still Having Issues?

### Checklist Terakhir

- [ ] Railway database running?
- [ ] All environment variables set di Vercel?
- [ ] APP_KEY generated dan di-set?
- [ ] Migrations sudah dijalankan?
- [ ] `npm run build` berhasil?
- [ ] Browser cache cleared?

### Get Help

1. **Check documentation:**
   - `VERCEL_DEPLOYMENT.md` - Full guide
   - `VERCEL_ENV_VARIABLES.md` - Environment variables
   - `DEPLOY_CHECKLIST.md` - Step-by-step checklist

2. **Check logs:**
   - Vercel Function Logs
   - Railway Database Logs
   - Browser Console

3. **Test components individually:**
   - Database connection
   - Build process
   - Authentication
   - Payment gateway

---

## 🎯 Common Solutions Summary

| Problem | Quick Fix |
|---------|-----------|
| Build error | `npm run build` locally first |
| Database error | Check Railway credentials |
| Session issues | `SESSION_DRIVER=cookie` |
| Assets not loading | Verify `public/build` exists |
| 500 error | Set `APP_DEBUG=true`, check logs |
| CSRF error | Check `APP_URL` matches domain |
| Payment error | Verify Midtrans sandbox keys |

---

**Pro Tip:** Selalu test locally dengan production settings sebelum deploy! 🚀
