# Railway Deployment Guide

## What Was Fixed

The deployment was failing because:
1. Railway was using the Dockerfile instead of Nixpacks
2. Composer wasn't available in the Nix environment when using custom nixpacks.toml

## Changes Made

1. **Renamed Dockerfile** → `Dockerfile.backup` (so Railway uses Nixpacks)
2. **Created .nixpacks.json** → Explicitly includes Composer in the build environment
3. **Updated railway.toml** → Simplified configuration for Nixpacks
4. **Set execute permissions** → Made init-app.sh executable

## How It Works Now

Railway's Nixpacks will:
- Use PHP 8.3 with Composer pre-installed
- Install Node.js 20 for asset building
- Auto-detect Laravel and run `composer install`
- Run `npm ci` to install frontend dependencies
- Run `npm run build` to compile assets with Vite (CSS & JS akan di-compile ke `public/build/`)
- Execute the init script to run migrations and cache
- Start the application with `php artisan serve`

**Penting:** Tidak perlu `npm run dev` di production! Yang penting adalah:
1. `npm run build` sudah dijalankan saat build (✓ sudah dikonfigurasi)
2. File hasil build ada di `public/build/` (✓ akan di-generate otomatis)
3. Blade templates menggunakan `@vite()` directive (✓ sudah benar)
4. `APP_ENV=production` di Railway environment variables (⚠️ pastikan ini di-set!)

## Required Environment Variables in Railway

Set these in your Railway project settings:

### Essential Variables
```
APP_NAME=JastipHype
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=YOUR_RAILWAY_MYSQL_HOST
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=YOUR_RAILWAY_MYSQL_PASSWORD

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Optional (if using these features)
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@jastiphype.com

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URL=https://your-app.railway.app/auth/google/callback

MIDTRANS_SERVER_KEY=your_midtrans_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false

RAJAONGKIR_API_KEY=your_rajaongkir_key
```

## Deployment Steps

1. **Add MySQL Database** in Railway:
   - Click "New" → "Database" → "Add MySQL"
   - Railway will automatically set DB_* environment variables

2. **Set Environment Variables**:
   - Go to your service → "Variables" tab
   - Add all required variables above
   - Generate APP_KEY: `php artisan key:generate --show`

3. **Deploy**:
   ```bash
   git add .
   git commit -m "Fix Railway deployment configuration"
   git push
   ```

4. **Monitor Deployment**:
   - Watch the build logs in Railway dashboard
   - Check for successful migration
   - Verify healthcheck passes

## Troubleshooting

### Jika design/CSS tidak muncul:
1. **Cek build logs** - Pastikan `npm run build` berhasil dijalankan
2. **Cek file manifest** - Seharusnya ada `public/build/manifest.json` setelah build
3. **Cek APP_ENV** - Harus di-set ke `production` di Railway
4. **Cek browser console** - Lihat apakah ada error 404 untuk file CSS/JS
5. **Cek APP_URL** - Harus sesuai dengan URL Railway Anda (misal: `https://your-app.railway.app`)

### If healthcheck still fails:
1. Check Railway logs for errors
2. Verify database connection variables
3. Ensure APP_KEY is set
4. Check if migrations ran successfully

### If build fails:
1. Check nixpacks.toml syntax
2. Verify composer.json and package.json are valid
3. Check Railway build logs for specific errors

### To use Docker instead:
1. Rename `Dockerfile.backup` back to `Dockerfile`
2. Delete `nixpacks.toml`
3. Fix database connection in start.sh to wait for DB
4. Update Dockerfile to use Railway's PORT environment variable

## Notes

- Railway automatically provides a PORT environment variable
- The app will be accessible at: `https://your-app.railway.app`
- Logs are available in Railway dashboard
- Database backups should be configured separately
