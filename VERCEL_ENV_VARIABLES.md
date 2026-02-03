# Vercel Environment Variables - Quick Reference

Copy-paste environment variables ini ke Vercel Dashboard → Settings → Environment Variables

## ✅ Required Variables

```env
# Application
APP_NAME=JastipHype
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-project.vercel.app

# Database (Railway PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=containers-us-west-xxx.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=your-railway-password

# Session & Cache (Vercel Optimized)
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
CACHE_DRIVER=array
QUEUE_CONNECTION=sync

# File Storage
FILESYSTEM_DISK=public
```

## 🔧 Optional but Recommended

```env
# Mail (Mailtrap for testing)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jastiphype.com
MAIL_FROM_NAME=JastipHype

# Midtrans (Sandbox for testing)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# RajaOngkir
RAJAONGKIR_API_KEY=your-rajaongkir-key
```

## 📝 How to Get Each Credential

### 1. APP_KEY
```bash
# Run locally:
php artisan key:generate --show

# Copy the output (starts with base64:)
```

### 2. Railway Database Credentials
1. Go to [railway.app](https://railway.app)
2. Create project → Provision PostgreSQL
3. Click database → **Variables** tab
4. Copy: `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD`

### 3. Mailtrap (Email Testing)
1. Go to [mailtrap.io](https://mailtrap.io)
2. Sign up (free)
3. Create inbox
4. Copy SMTP credentials

### 4. Midtrans (Payment Gateway)
1. Go to [midtrans.com](https://midtrans.com)
2. Sign up
3. Dashboard → Settings → Access Keys
4. Use **Sandbox** keys for testing

### 5. RajaOngkir (Shipping API)
1. Go to [rajaongkir.com](https://rajaongkir.com)
2. Sign up
3. Get API key from dashboard

## 🚨 Important Notes

1. **Never commit** `.env` file to Git
2. **APP_DEBUG** must be `false` in production
3. **DB_CONNECTION** must be `pgsql` for Railway PostgreSQL
4. **SESSION_DRIVER** must be `cookie` for Vercel (no file/database sessions)
5. **CACHE_DRIVER** must be `array` for Vercel (no file cache)
6. **QUEUE_CONNECTION** must be `sync` for Vercel (no database queue)

## 🔄 Update Environment Variables

After adding/changing variables in Vercel:
1. Go to Deployments tab
2. Click latest deployment → **Redeploy**
3. Or push new commit to trigger auto-deploy

## ✅ Verification Checklist

- [ ] All required variables added
- [ ] APP_KEY generated and added
- [ ] Railway database credentials correct
- [ ] Test deployment successful
- [ ] Website loads without errors
- [ ] Can register/login
- [ ] Can browse products
- [ ] Payment gateway works (sandbox)
- [ ] Email sending works (if configured)

## 🐛 Common Issues

### "Database connection failed"
→ Check Railway database is running
→ Verify DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

### "Invalid APP_KEY"
→ Generate new key: `php artisan key:generate --show`
→ Must start with `base64:`

### "Session not working"
→ Ensure `SESSION_DRIVER=cookie`
→ Check `APP_URL` matches your Vercel domain

### "Assets not loading"
→ Run `npm run build` before deploy
→ Check `public/build` folder exists
→ Verify Vercel build logs

## 📞 Need Help?

Check detailed guide: `VERCEL_DEPLOYMENT.md`
