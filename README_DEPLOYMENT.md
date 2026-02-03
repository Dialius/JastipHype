# 🚀 JastipHype - Deployment Guide

Deploy aplikasi JastipHype ke **Vercel** (hosting) dengan database **Railway** (PostgreSQL).

---

## 📖 Dokumentasi Lengkap

| File | Untuk Apa |
|------|-----------|
| **[DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md)** | 📝 Ringkasan cepat (baca ini dulu!) |
| **[DEPLOY_CHECKLIST.md](DEPLOY_CHECKLIST.md)** | ✅ Checklist step-by-step |
| **[VERCEL_DEPLOYMENT.md](VERCEL_DEPLOYMENT.md)** | 📚 Panduan lengkap detail |
| **[VERCEL_ENV_VARIABLES.md](VERCEL_ENV_VARIABLES.md)** | 🔑 Environment variables reference |
| **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** | 🔧 Solusi masalah umum |

---

## ⚡ Quick Start (20 menit)

### 1. Setup Database Railway (5 menit)

```
1. Buka https://railway.app
2. New Project → Provision PostgreSQL
3. Copy credentials dari Variables tab
```

### 2. Deploy ke Vercel (10 menit)

```
1. Push code ke GitHub
2. Buka https://vercel.com
3. Import repository
4. Add environment variables (lihat VERCEL_ENV_VARIABLES.md)
5. Deploy!
```

### 3. Setup Database Schema (5 menit)

```bash
# Update .env dengan Railway credentials
php artisan migrate --force
php artisan db:seed --force
```

---

## 🔑 Environment Variables Minimal

```env
APP_KEY=base64:xxx                    # php artisan key:generate --show
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

**Lengkapnya:** Lihat [VERCEL_ENV_VARIABLES.md](VERCEL_ENV_VARIABLES.md)

---

## 🛠️ Helper Scripts

### Windows
```bash
deploy-vercel.bat
```

### Linux/Mac
```bash
chmod +x deploy-vercel.sh
./deploy-vercel.sh
```

---

## ✅ Verifikasi Deployment

Setelah deploy, test:

- [ ] Homepage load
- [ ] Register/Login works
- [ ] Browse products
- [ ] Add to cart
- [ ] Checkout (Midtrans sandbox)
- [ ] Admin panel access

---

## 🐛 Masalah Umum

| Error | Solusi |
|-------|--------|
| Build failed | Lihat [TROUBLESHOOTING.md](TROUBLESHOOTING.md#-build-errors) |
| Database error | Lihat [TROUBLESHOOTING.md](TROUBLESHOOTING.md#-database-errors) |
| 500 error | Set `APP_DEBUG=true`, check Vercel logs |
| Assets not loading | Run `npm run build`, verify `public/build` exists |

**Lengkapnya:** Lihat [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

---

## 📞 Butuh Bantuan?

1. **Baca dokumentasi** (pilih sesuai kebutuhan):
   - Baru mulai? → [DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md)
   - Mau step-by-step? → [DEPLOY_CHECKLIST.md](DEPLOY_CHECKLIST.md)
   - Ada error? → [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

2. **Check logs:**
   - Vercel: Dashboard → Deployments → View Function Logs
   - Railway: Dashboard → Database → Logs

3. **Test locally** dengan production settings

---

## 🎯 Arsitektur Deployment

```
┌─────────────────┐
│   GitHub Repo   │
└────────┬────────┘
         │
         ↓
┌─────────────────┐      ┌──────────────────┐
│     Vercel      │ ←───→│  Railway (DB)    │
│  (Hosting)      │      │  PostgreSQL      │
└─────────────────┘      └──────────────────┘
         │
         ↓
┌─────────────────┐
│   Your Domain   │
│  (Optional)     │
└─────────────────┘
```

**Kenapa setup ini?**
- ✅ Vercel: Hosting gratis, auto-deploy dari GitHub, CDN global
- ✅ Railway: PostgreSQL gratis, mudah setup, reliable
- ✅ Serverless: Auto-scale, no server management
- ✅ Free tier: Cukup untuk development & testing

---

## 🎉 Next Steps Setelah Deploy

1. **Test semua fitur** (gunakan checklist)
2. **Setup custom domain** (optional)
3. **Enable production mode** untuk Midtrans
4. **Setup monitoring** (Vercel Analytics, Sentry)
5. **Optimize performance** (caching, CDN)

---

## 📊 Estimasi Biaya

### Free Tier (Cukup untuk testing)
- Vercel: Free (100GB bandwidth/month)
- Railway: Free ($5 credit/month)
- **Total: $0/month** ✨

### Production (Recommended)
- Vercel Pro: $20/month
- Railway: ~$5-10/month
- **Total: ~$25-30/month**

---

## 🔒 Security Checklist

- [ ] `APP_DEBUG=false` di production
- [ ] `APP_KEY` unique dan secure
- [ ] Database credentials aman (tidak di-commit)
- [ ] HTTPS enabled (auto di Vercel)
- [ ] CSRF protection enabled (default Laravel)
- [ ] Rate limiting configured
- [ ] Input validation di semua forms

---

## 📈 Performance Tips

1. **Database:**
   - Add indexes untuk queries sering dipakai
   - Use eager loading untuk relationships
   - Cache query results

2. **Assets:**
   - Optimize images (compress, WebP)
   - Use Vercel Image Optimization
   - Enable browser caching

3. **Code:**
   - Use route caching: `php artisan route:cache`
   - Use config caching: `php artisan config:cache`
   - Optimize autoloader: `composer dump-autoload -o`

---

## 🚀 Ready to Deploy?

**Mulai dari sini:**
1. Baca [DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md) (5 menit)
2. Ikuti [DEPLOY_CHECKLIST.md](DEPLOY_CHECKLIST.md) (20 menit)
3. Jika ada masalah, cek [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

**Good luck!** 🎉

---

*Last updated: February 2026*
