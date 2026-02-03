# Vercel Deployment Troubleshooting

## 🔍 Kenapa Perubahan Tidak Masuk ke Vercel?

### Kemungkinan Penyebab

#### 1. Vercel Belum Terhubung dengan GitHub Repository ❌
**Gejala**: Push ke GitHub berhasil, tapi Vercel tidak auto-deploy

**Solusi**: Setup koneksi Vercel dengan GitHub

#### 2. Auto-Deploy Disabled ⚠️
**Gejala**: Vercel terhubung tapi tidak auto-deploy

**Solusi**: Enable auto-deploy di settings

#### 3. Branch Tidak Sesuai ⚠️
**Gejala**: Push ke branch lain, Vercel monitor branch berbeda

**Solusi**: Pastikan push ke branch yang benar

#### 4. Build Error 🔴
**Gejala**: Deploy triggered tapi build gagal

**Solusi**: Check build logs di Vercel

## ✅ Solusi Step-by-Step

### Option 1: Setup Vercel dari Awal (Recommended)

#### Step 1: Login ke Vercel
1. Buka https://vercel.com
2. Login dengan GitHub account Anda
3. Authorize Vercel untuk access GitHub

#### Step 2: Import Project
1. Click **"Add New..."** di dashboard
2. Pilih **"Project"**
3. Pilih **"Import Git Repository"**
4. Cari dan pilih **"Dialius/JastipHype"**
   - Jika tidak muncul, click "Adjust GitHub App Permissions"
   - Berikan akses ke repository JastipHype

#### Step 3: Configure Project
```
Framework Preset: Other
Root Directory: ./
Build Command: php scripts/ensure-storage.php && npm run build
Output Directory: public
Install Command: composer install && npm install
```

#### Step 4: Add Environment Variables
**CRITICAL**: Tambahkan semua environment variables sebelum deploy!

**Minimum Required**:
```env
APP_NAME=JastipHype
APP_KEY=base64:your-app-key-here
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.vercel.app

# Database
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-user
DB_PASSWORD=your-database-password

# Session & Cache
SESSION_DRIVER=cookie
CACHE_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync

# Storage
FILESYSTEM_DISK=public

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Payment (Midtrans)
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_ENVIRONMENT=production
MIDTRANS_MERCHANT_ID=your-merchant-id

# Shipping (RajaOngkir)
RAJAONGKIR_API_KEY=your-api-key
RAJAONGKIR_ORIGIN_CITY=152
```

**Cara Add Environment Variables**:
1. Di Vercel project settings
2. Go to **"Environment Variables"** tab
3. Add satu per satu atau bulk add
4. Pilih environment: Production, Preview, Development (pilih semua)
5. Click **"Save"**

#### Step 5: Deploy
1. Click **"Deploy"**
2. Wait for build process (5-10 minutes)
3. Check build logs jika ada error

### Option 2: Redeploy Existing Project

Jika project sudah ada di Vercel:

#### Step 1: Check Connection
1. Login ke Vercel dashboard
2. Pilih project "JastipHype"
3. Go to **Settings** → **Git**
4. Pastikan connected ke GitHub repository yang benar

#### Step 2: Trigger Manual Deploy
1. Go to **Deployments** tab
2. Click **"Redeploy"** pada deployment terakhir
3. Atau click **"Deploy"** untuk deploy ulang dari branch

#### Step 3: Check Build Logs
1. Click pada deployment yang sedang berjalan
2. Check **"Building"** logs
3. Lihat apakah ada error

### Option 3: Deploy via Vercel CLI

```bash
# Install Vercel CLI (jika belum)
npm install -g vercel

# Login
vercel login

# Link project (jika belum)
vercel link

# Deploy to preview
vercel

# Deploy to production
vercel --prod
```

## 🔍 Debugging Steps

### 1. Check GitHub Repository
```bash
# Verify latest commit
git log --oneline -5

# Check remote
git remote -v

# Verify push
git log origin/master --oneline -5
```

**Expected**: Commit `0fdb4db` should be visible

### 2. Check Vercel Dashboard

#### A. Check Project Exists
- Login ke https://vercel.com
- Lihat apakah project "JastipHype" ada di dashboard
- Jika tidak ada → Buat project baru (Option 1)

#### B. Check Git Connection
- Go to project → Settings → Git
- Pastikan connected ke: `Dialius/JastipHype`
- Pastikan branch: `master`

#### C. Check Deployments
- Go to project → Deployments
- Lihat deployment history
- Check status: Success, Failed, atau Building

#### D. Check Build Logs
- Click pada deployment terakhir
- Check logs untuk error messages
- Common errors:
  - Missing environment variables
  - Build command failed
  - Database connection failed
  - Composer install failed

### 3. Common Issues & Solutions

#### Issue: "Project not found"
**Solution**: 
- Project belum dibuat di Vercel
- Follow Option 1 untuk create new project

#### Issue: "Build failed"
**Solution**:
```bash
# Check build logs di Vercel
# Common fixes:

# 1. Missing dependencies
composer install
npm install

# 2. Missing environment variables
# Add di Vercel dashboard

# 3. Build command error
# Update build command di Vercel settings
```

#### Issue: "Environment variables not set"
**Solution**:
- Go to Vercel → Settings → Environment Variables
- Add semua required variables
- Redeploy

#### Issue: "Database connection failed"
**Solution**:
- Verify database credentials
- Check database host accessible from Vercel
- Check database firewall rules
- Allow Vercel IP addresses

#### Issue: "Auto-deploy not working"
**Solution**:
1. Go to Settings → Git
2. Check "Production Branch" setting
3. Enable "Automatically deploy" option
4. Save changes

## 📋 Verification Checklist

### Before Deploy
- [ ] Code pushed to GitHub
- [ ] Vercel account created
- [ ] GitHub connected to Vercel
- [ ] Repository accessible by Vercel
- [ ] Environment variables prepared

### During Setup
- [ ] Project imported from GitHub
- [ ] Build command configured
- [ ] Output directory set to `public`
- [ ] All environment variables added
- [ ] Production branch set to `master`

### After Deploy
- [ ] Build completed successfully
- [ ] No errors in build logs
- [ ] Application accessible via Vercel URL
- [ ] Database connected
- [ ] Static assets loading
- [ ] No 500 errors

## 🚀 Quick Deploy Guide

### Fastest Way to Deploy

1. **Go to Vercel**: https://vercel.com/new
2. **Import Git Repository**: Select "Dialius/JastipHype"
3. **Configure**:
   ```
   Framework: Other
   Build: php scripts/ensure-storage.php && npm run build
   Output: public
   ```
4. **Add Environment Variables**: Copy from `.env`
5. **Click Deploy**: Wait 5-10 minutes
6. **Done**: Access via provided URL

## 📞 Need Help?

### Check These Resources
1. **Vercel Documentation**: https://vercel.com/docs
2. **Build Logs**: Vercel Dashboard → Deployments → Click deployment
3. **GitHub Repository**: https://github.com/Dialius/JastipHype
4. **Local Documentation**: 
   - `VERCEL_QUICK_FIX.md`
   - `FINAL_CHECKLIST.md`
   - `VERCEL_ISSUES_COMPLETE_FIX.md`

### Common Commands
```bash
# Check git status
git status
git log --oneline -5

# Verify Vercel CLI
vercel --version

# Login to Vercel
vercel login

# Deploy
vercel --prod

# Check logs
vercel logs
```

## ✨ Expected Result

After successful deployment:
- ✅ Vercel URL accessible
- ✅ Homepage loads
- ✅ No build errors
- ✅ Database connected
- ✅ Auto-deploy enabled for future pushes

## 🎯 Next Steps

1. **Follow Option 1** untuk setup dari awal
2. **Add all environment variables** (paling penting!)
3. **Deploy and test**
4. **Check build logs** jika ada error
5. **Test application** setelah deploy berhasil

---

**Status**: Waiting for Vercel setup
**Repository**: https://github.com/Dialius/JastipHype ✅
**Code**: Ready ✅
**Vercel**: Need setup ⏳
