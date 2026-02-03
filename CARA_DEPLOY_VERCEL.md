# 🚀 Cara Deploy ke Vercel - Step by Step

## 📌 Kenapa Tidak Masuk ke Vercel?

Kemungkinan besar: **Vercel belum terhubung dengan GitHub repository Anda**

Push ke GitHub ✅ berhasil, tapi Vercel perlu di-setup manual untuk connect ke repository.

## ✅ Solusi: Setup Vercel (5-10 Menit)

### Step 1: Buka Vercel Dashboard

1. Buka browser, go to: **https://vercel.com**
2. Click **"Login"** atau **"Sign Up"**
3. Pilih **"Continue with GitHub"**
4. Login dengan GitHub account Anda
5. Authorize Vercel untuk access GitHub

### Step 2: Import Project dari GitHub

1. Di Vercel dashboard, click **"Add New..."** (tombol di kanan atas)
2. Pilih **"Project"**
3. Anda akan lihat halaman "Import Git Repository"
4. Cari repository **"JastipHype"** atau **"Dialius/JastipHype"**

**Jika repository tidak muncul**:
- Click **"Adjust GitHub App Permissions"**
- Atau click **"Add GitHub Account"**
- Pilih account **"Dialius"**
- Berikan akses ke repository **"JastipHype"**
- Kembali ke Vercel, refresh page
- Repository sekarang harus muncul

5. Click **"Import"** pada repository JastipHype

### Step 3: Configure Project Settings

Anda akan lihat halaman "Configure Project". Isi seperti ini:

```
Project Name: jastiphype (atau nama lain yang Anda mau)

Framework Preset: Other

Root Directory: ./ (default, jangan diubah)

Build and Output Settings:
├─ Build Command: php scripts/ensure-storage.php && npm run build
├─ Output Directory: public
└─ Install Command: composer install && npm install
```

**JANGAN DEPLOY DULU!** Lanjut ke Step 4 untuk add environment variables.

### Step 4: Add Environment Variables (PENTING!)

Sebelum deploy, Anda HARUS add environment variables:

1. Scroll ke bawah ke section **"Environment Variables"**
2. Click **"Add"** atau expand section

**Copy-paste variables ini satu per satu**:

#### Required Variables (Wajib)

```env
APP_NAME=JastipHype
APP_KEY=base64:your-app-key-from-env-file
APP_ENV=production
APP_DEBUG=false
APP_URL=https://jastiphype.vercel.app

DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-user
DB_PASSWORD=your-database-password

SESSION_DRIVER=cookie
CACHE_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-gmail-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jastiphype.com
MAIL_FROM_NAME=JastipHype

MIDTRANS_SERVER_KEY=your-midtrans-server-key
MIDTRANS_CLIENT_KEY=your-midtrans-client-key
MIDTRANS_ENVIRONMENT=production
MIDTRANS_MERCHANT_ID=your-merchant-id

RAJAONGKIR_API_KEY=your-rajaongkir-api-key
RAJAONGKIR_ORIGIN_CITY=152
```

**Cara Add**:
1. Untuk setiap variable:
   - Name: `APP_NAME`
   - Value: `JastipHype`
   - Environment: Pilih **Production**, **Preview**, **Development** (check semua)
   - Click **"Add"**
2. Ulangi untuk semua variables
3. **PENTING**: Ganti `your-xxx` dengan nilai sebenarnya dari file `.env` Anda

**Tips**: 
- Buka file `.env` lokal Anda
- Copy value dari sana
- Paste ke Vercel

### Step 5: Deploy!

1. Setelah semua environment variables ditambahkan
2. Click **"Deploy"** (tombol biru besar)
3. Vercel akan mulai build process
4. Tunggu 5-10 menit

**Anda akan lihat**:
- Building... (sedang build)
- Progress bar
- Build logs (bisa di-expand untuk lihat detail)

### Step 6: Check Build Status

**Jika Build Success** ✅:
- Anda akan lihat "Congratulations!" message
- Ada link ke deployed application
- Click link untuk test application

**Jika Build Failed** ❌:
- Click **"View Build Logs"**
- Lihat error message
- Common errors:
  - Missing environment variables → Add di Settings
  - Database connection failed → Check DB credentials
  - Composer install failed → Check composer.json

### Step 7: Test Application

1. Click pada Vercel URL (contoh: `https://jastiphype.vercel.app`)
2. Test:
   - Homepage loads ✅
   - Products display ✅
   - Login works ✅
   - Admin panel accessible ✅

## 🔄 Auto-Deploy untuk Push Selanjutnya

Setelah setup awal, setiap kali Anda push ke GitHub:

```bash
git add .
git commit -m "your message"
git push origin master
```

Vercel akan **otomatis** detect dan deploy! 🎉

## 📋 Checklist

### Pre-Deploy
- [x] Code pushed ke GitHub ✅
- [ ] Vercel account created
- [ ] GitHub connected to Vercel
- [ ] Project imported
- [ ] Environment variables added
- [ ] Build command configured

### Post-Deploy
- [ ] Build completed successfully
- [ ] Application accessible
- [ ] Homepage loads
- [ ] Database connected
- [ ] No errors in logs

## 🆘 Troubleshooting

### Problem: Repository tidak muncul di Vercel
**Solution**:
1. Click "Adjust GitHub App Permissions"
2. Berikan akses ke repository JastipHype
3. Refresh page

### Problem: Build failed
**Solution**:
1. Check build logs
2. Verify environment variables
3. Check database connection
4. Redeploy

### Problem: 500 Error setelah deploy
**Solution**:
1. Check Vercel logs: `vercel logs`
2. Verify database credentials
3. Check APP_KEY is set
4. Check all required env vars

### Problem: Static assets tidak load
**Solution**:
1. Check Output Directory = `public`
2. Run `npm run build` locally untuk test
3. Check vercel.json routes configuration

## 📞 Need Help?

### Quick Links
- **Vercel Dashboard**: https://vercel.com/dashboard
- **GitHub Repo**: https://github.com/Dialius/JastipHype
- **Vercel Docs**: https://vercel.com/docs

### Check These Files
- `VERCEL_DEPLOYMENT_TROUBLESHOOTING.md` - Detailed troubleshooting
- `VERCEL_QUICK_FIX.md` - Quick reference
- `FINAL_CHECKLIST.md` - Complete checklist

## ✨ Summary

**What You Need to Do**:
1. ✅ Login ke Vercel dengan GitHub
2. ✅ Import repository "JastipHype"
3. ✅ Configure build settings
4. ✅ Add ALL environment variables (paling penting!)
5. ✅ Click Deploy
6. ✅ Wait 5-10 minutes
7. ✅ Test application

**Expected Time**: 10-15 minutes
**Difficulty**: Easy (just follow steps)

---

**Start Here**: https://vercel.com/new

**Good luck! 🚀**
