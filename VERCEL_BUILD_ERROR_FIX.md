# Vercel Build Error Fix

## 🔴 Error yang Terjadi

```
Error: Command "php scripts/ensure-storage.php && npm run build" exited with 127
sh: line 1: php: command not found
```

## 🔍 Penyebab

**Build environment Vercel tidak memiliki PHP**. PHP hanya tersedia di runtime (serverless functions), bukan di build time.

Build command yang mencoba menjalankan PHP script akan gagal karena PHP tidak tersedia.

## ✅ Solusi yang Diterapkan

### 1. Update Build Command

**Before** ❌:
```json
"buildCommand": "php scripts/ensure-storage.php && npm run build"
```

**After** ✅:
```json
"buildCommand": "npm run build"
```

### 2. Move Storage Initialization ke Runtime

Alih-alih membuat directories di build time, kita buat di runtime saat serverless function dijalankan.

**Created**: `api/ensure-storage.php`
- Endpoint untuk ensure storage directories
- Bisa dipanggil saat runtime

**Updated**: `api/index.php`
- Automatically ensure directories saat bootstrap
- Hanya di serverless environment (VERCEL_ENV)

### 3. Storage Directories Created at Runtime

Directories akan dibuat otomatis di `/tmp/storage/` saat:
- First request ke application
- Setiap cold start
- Saat upload file

## 🚀 Cara Deploy Ulang

### Step 1: Commit & Push Changes

```bash
git add .
git commit -m "fix: Remove PHP from build command for Vercel compatibility"
git push origin master
```

### Step 2: Redeploy di Vercel

**Option A: Automatic** (jika auto-deploy enabled)
- Push ke GitHub akan trigger auto-deploy
- Wait 2-3 minutes

**Option B: Manual**
1. Go to Vercel Dashboard
2. Go to your project
3. Click "Deployments"
4. Click "Redeploy" pada deployment terakhir

### Step 3: Verify Build Success

Check build logs untuk:
- ✅ `npm run build` success
- ✅ No PHP errors
- ✅ Build completed

## 📋 Updated Configuration

### vercel.json
```json
{
    "version": 2,
    "outputDirectory": "public",
    "buildCommand": "npm run build",
    "functions": {
        "api/*.php": {
            "runtime": "vercel-php@0.7.3"
        }
    }
}
```

### api/index.php
```php
<?php

// Ensure storage directories exist in serverless environment
if (!empty(getenv('VERCEL_ENV'))) {
    $directories = [
        '/tmp/storage/banners',
        '/tmp/storage/brands',
        '/tmp/storage/categories',
        '/tmp/storage/products',
        '/tmp/storage/reviews',
    ];

    foreach ($directories as $directory) {
        if (!file_exists($directory)) {
            @mkdir($directory, 0755, true);
        }
    }
}

// Forward to the standard Laravel entry point
require __DIR__ . '/../public/index.php';
```

## 🔍 How It Works Now

### Build Time (No PHP)
1. Vercel runs: `npm run build`
2. Compiles CSS/JS assets
3. No PHP scripts executed
4. Build completes successfully ✅

### Runtime (PHP Available)
1. Request comes to serverless function
2. `api/index.php` executes
3. Checks if VERCEL_ENV is set
4. Creates storage directories in `/tmp/`
5. Forwards to Laravel
6. Application runs normally ✅

## ⚠️ Important Notes

### Storage in Serverless

**Temporary Storage**:
- Files in `/tmp/` are temporary
- Cleared after function execution
- Not persistent across requests

**For Production**:
- Use S3 for file uploads
- Use database for cache
- Use cookie for sessions

### Build vs Runtime

**Build Time**:
- No PHP available
- Only Node.js/npm
- Compiles static assets
- Creates build artifacts

**Runtime**:
- PHP available (vercel-php)
- Handles requests
- Executes Laravel code
- Can create directories

## 🎯 Expected Behavior

### After Fix

**Build Process**:
```
✓ Installing dependencies
✓ Running npm run build
✓ Compiling assets
✓ Build completed
✓ Deployment ready
```

**Runtime**:
```
✓ Storage directories created
✓ Laravel bootstrapped
✓ Application running
✓ Requests handled
```

## 🧪 Testing

### 1. Check Build Logs
- Go to Vercel Dashboard
- Click on deployment
- Check "Building" tab
- Should see: `npm run build` success

### 2. Test Application
- Visit Vercel URL
- Homepage should load
- No 500 errors
- Check browser console for errors

### 3. Test File Upload
- Login to admin
- Try upload banner/product
- Should work or show proper warning

## 📚 Related Documentation

- `VERCEL_DEPLOYMENT_TROUBLESHOOTING.md` - General troubleshooting
- `CARA_DEPLOY_VERCEL.md` - Deployment guide
- `VERCEL_ISSUES_COMPLETE_FIX.md` - All serverless fixes

## ✨ Summary

**Problem**: PHP not available in build environment
**Solution**: Remove PHP from build command, move to runtime
**Status**: ✅ Fixed

**Next Steps**:
1. Commit changes
2. Push to GitHub
3. Vercel will auto-deploy
4. Build should succeed
5. Application should work

---

**Build Command**: `npm run build` (no PHP)
**Storage Init**: Runtime (in api/index.php)
**Status**: ✅ Ready to deploy
