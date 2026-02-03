# ✅ Deployment Checklist - Vercel + Railway

Ikuti checklist ini step-by-step untuk deploy JastipHype ke Vercel dengan database Railway.

---

## 📋 Pre-Deployment (Local)

- [ ] **Install dependencies**
  ```bash
  composer install
  npm install
  ```

- [ ] **Build assets**
  ```bash
  npm run build
  ```
  ✅ Pastikan folder `public/build` terbuat

- [ ] **Generate APP_KEY**
  ```bash
  php artisan key:generate --show
  ```
  📝 Copy output (starts with `base64:`)

- [ ] **Test local build**
  ```bash
  php artisan serve
  ```
  🌐 Buka http://localhost:8000 dan pastikan berjalan

---

## 🗄️ Setup Database Railway

- [ ] **Buat akun Railway**
  - Buka https://railway.app
  - Sign up dengan GitHub

- [ ] **Provision PostgreSQL**
  - Klik **New Project**
  - Pilih **Provision PostgreSQL**
  - Tunggu database dibuat (~1 menit)

- [ ] **Copy database credentials**
  - Klik database → **Variables** tab
  - Copy credentials berikut:
    - `PGHOST` → DB_HOST
    - `PGPORT` → DB_PORT (biasanya 5432)
    - `PGDATABASE` → DB_DATABASE
    - `PGUSER` → DB_USERNAME
    - `PGPASSWORD` → DB_PASSWORD

- [ ] **Test koneksi database (optional)**
  ```bash
  # Update .env dengan Railway credentials
  php artisan migrate --force
  ```

---

## 🚀 Deploy ke Vercel

### Step 1: Push ke GitHub

- [ ] **Commit semua perubahan**
  ```bash
  git add .
  git commit -m "Prepare for Vercel deployment"
  git push origin main
  ```

### Step 2: Import ke Vercel

- [ ] **Buat akun Vercel**
  - Buka https://vercel.com
  - Sign up dengan GitHub

- [ ] **Import repository**
  - Klik **Add New** → **Project**
  - Pilih repository JastipHype
  - Klik **Import**

### Step 3: Configure Project

- [ ] **Project Settings**
  - Framework Preset: **Other**
  - Build Command: `npm run build`
  - Output Directory: `public`
  - Install Command: `npm install`

### Step 4: Environment Variables

- [ ] **Add required variables** (copy dari VERCEL_ENV_VARIABLES.md)
  
  **Application:**
  ```
  APP_NAME=JastipHype
  APP_ENV=production
  APP_KEY=base64:xxx (dari step generate APP_KEY)
  APP_DEBUG=false
  APP_URL=https://your-project.vercel.app
  ```

  **Database (dari Railway):**
  ```
  DB_CONNECTION=pgsql
  DB_HOST=xxx.railway.app
  DB_PORT=5432
  DB_DATABASE=railway
  DB_USERNAME=postgres
  DB_PASSWORD=xxx
  ```

  **Session & Cache:**
  ```
  SESSION_DRIVER=cookie
  SESSION_LIFETIME=120
  CACHE_DRIVER=array
  QUEUE_CONNECTION=sync
  FILESYSTEM_DISK=public
  ```

- [ ] **Add optional variables** (recommended)
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=sandbox.smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=xxx
  MAIL_PASSWORD=xxx
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=noreply@jastiphype.com
  MAIL_FROM_NAME=JastipHype
  
  MIDTRANS_SERVER_KEY=SB-Mid-server-xxx
  MIDTRANS_CLIENT_KEY=SB-Mid-client-xxx
  MIDTRANS_IS_PRODUCTION=false
  MIDTRANS_IS_SANITIZED=true
  MIDTRANS_IS_3DS=true
  
  RAJAONGKIR_API_KEY=xxx
  ```

### Step 5: Deploy

- [ ] **Klik Deploy**
  - Tunggu build selesai (~2-5 menit)
  - Cek build logs jika ada error

- [ ] **Verify deployment**
  - Klik **Visit** untuk buka website
  - Pastikan homepage muncul

---

## 🗃️ Setup Database Schema

- [ ] **Update .env lokal dengan Railway credentials**
  ```env
  DB_CONNECTION=pgsql
  DB_HOST=xxx.railway.app
  DB_PORT=5432
  DB_DATABASE=railway
  DB_USERNAME=postgres
  DB_PASSWORD=xxx
  ```

- [ ] **Run migrations**
  ```bash
  php artisan migrate --force
  ```

- [ ] **Seed database (optional)**
  ```bash
  php artisan db:seed --force
  ```

---

## ✅ Post-Deployment Testing

- [ ] **Test homepage**
  - Buka website Vercel
  - Pastikan homepage load dengan benar

- [ ] **Test authentication**
  - Register akun baru
  - Login dengan akun tersebut
  - Logout

- [ ] **Test products**
  - Browse products page
  - View product detail
  - Search products

- [ ] **Test cart**
  - Add product to cart
  - Update quantity
  - Remove from cart

- [ ] **Test checkout (sandbox)**
  - Proceed to checkout
  - Fill shipping info
  - Test Midtrans payment (sandbox)

- [ ] **Test admin panel**
  - Login sebagai admin
  - Check dashboard
  - Test CRUD operations

---

## 🔧 Optional Enhancements

- [ ] **Setup custom domain**
  - Vercel Dashboard → Settings → Domains
  - Add your custom domain

- [ ] **Enable production Midtrans**
  - Get production credentials
  - Update MIDTRANS_IS_PRODUCTION=true

- [ ] **Setup real email**
  - Configure SMTP (Gmail, SendGrid, etc)
  - Update MAIL_* variables

- [ ] **Setup monitoring**
  - Enable Vercel Analytics
  - Setup Sentry for error tracking

- [ ] **Optimize performance**
  - Enable Vercel Edge Caching
  - Setup CDN for images

---

## 🐛 Troubleshooting

### Build Failed
- [ ] Check Vercel build logs
- [ ] Verify `npm run build` works locally
- [ ] Check `vercel.json` configuration

### Database Connection Error
- [ ] Verify Railway database is running
- [ ] Check all DB_* variables in Vercel
- [ ] Test connection from local

### 500 Internal Server Error
- [ ] Set APP_DEBUG=true temporarily
- [ ] Check Vercel Function Logs
- [ ] Verify APP_KEY is set correctly

### Assets Not Loading
- [ ] Check `public/build` folder exists
- [ ] Verify build completed successfully
- [ ] Check browser console for errors

---

## 📞 Need Help?

- 📖 **Detailed Guide**: `VERCEL_DEPLOYMENT.md`
- 🔑 **Environment Variables**: `VERCEL_ENV_VARIABLES.md`
- 📝 **Quick Summary**: `DEPLOYMENT_SUMMARY.md`

---

## 🎉 Success!

Jika semua checklist ✅, selamat! Website Anda sudah live di Vercel dengan database Railway.

**Next Steps:**
1. Share URL dengan tim/client
2. Monitor performance di Vercel Dashboard
3. Setup custom domain jika diperlukan
4. Enable production mode untuk payment gateway

---

**Estimasi Total Waktu: 20-30 menit** ⏱️

Good luck! 🚀
