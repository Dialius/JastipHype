# Vercel 500 Error Fix

## 🔴 Error: 500 SERVER ERROR

### Kemungkinan Penyebab

#### 1. Helper Functions Not Autoloaded ⚠️
**Gejala**: 500 error setelah push helper functions

**Penyebab**: Composer autoload belum di-regenerate di Vercel

**Solusi**: Vercel perlu run `composer dump-autoload` saat build

#### 2. Database Connection Failed ⚠️
**Gejala**: 500 error di semua pages

**Penyebab**: Database credentials tidak valid atau database tidak accessible

**Solusi**: Verify database credentials di Vercel environment variables

#### 3. Missing Environment Variables ⚠️
**Gejala**: 500 error, APP_KEY missing

**Penyebab**: Required environment variables tidak di-set

**Solusi**: Add all required env vars di Vercel dashboard

## ✅ Solusi Step-by-Step

### Solution 1: Ensure Composer Autoload in Build

Vercel seharusnya otomatis run `composer install` yang akan generate autoload.

**Verify di Vercel Build Logs**:
1. Go to Vercel Dashboard
2. Click on latest deployment
3. Check "Building" tab
4. Look for:
   ```
   Running "composer install"
   Generating optimized autoload files
   ```

**If not running**, update `vercel.json`:
```json
{
    "buildCommand": "composer install --no-dev --optimize-autoloader && npm run build"
}
```

### Solution 2: Check Vercel Logs

**View Runtime Logs**:
```bash
# Using Vercel CLI
vercel logs

# Or in Vercel Dashboard
# Go to: Deployments → Click deployment → Runtime Logs
```

**Look for**:
- PHP errors
- Database connection errors
- Missing class errors
- Helper function errors

### Solution 3: Verify Environment Variables

**Required Variables**:
```env
APP_KEY=base64:your-key
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.vercel.app

DB_CONNECTION=mysql
DB_HOST=your-host
DB_DATABASE=your-database
DB_USERNAME=your-user
DB_PASSWORD=your-password

SESSION_DRIVER=cookie
CACHE_DRIVER=database
QUEUE_CONNECTION=sync
```

**Check in Vercel**:
1. Go to Project Settings
2. Environment Variables tab
3. Verify all variables are set
4. Redeploy if you added/changed variables

### Solution 4: Test Specific Endpoints

**Test Different URLs**:
```
https://your-domain.vercel.app/          → Homepage
https://your-domain.vercel.app/products  → Products page
https://your-domain.vercel.app/api/test  → API test
```

**If some work and some don't**:
- Issue is specific to certain routes/controllers
- Check those specific files for errors

**If all return 500**:
- Issue is in bootstrap/initialization
- Check APP_KEY, database connection, autoload

### Solution 5: Temporary Rollback

**If you need to rollback**:
1. Go to Vercel Dashboard
2. Deployments tab
3. Find previous working deployment
4. Click "..." → "Promote to Production"

This will rollback to previous version while you fix the issue.

### Solution 6: Debug Mode (Temporary)

**Enable debug to see error details**:

1. In Vercel Environment Variables, set:
   ```
   APP_DEBUG=true
   ```

2. Redeploy

3. Visit site to see detailed error

4. **IMPORTANT**: Set back to `false` after debugging!

## 🔍 Common Errors & Solutions

### Error: "Class 'App\Helpers\ImageHelper' not found"

**Cause**: Autoload not generated

**Solution**:
```bash
# In vercel.json, ensure build command includes:
"buildCommand": "composer install --optimize-autoloader && npm run build"
```

### Error: "Call to undefined function image_url()"

**Cause**: Helper file not loaded

**Solution**:
1. Verify `composer.json` has:
   ```json
   "autoload": {
       "files": ["app/Helpers/helpers.php"]
   }
   ```

2. Ensure `composer dump-autoload` runs in build

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Cause**: Database not accessible

**Solution**:
1. Check database host is correct
2. Verify database allows connections from Vercel IPs
3. Check database credentials
4. Test connection from Vercel:
   ```php
   // Create test endpoint
   Route::get('/test-db', function() {
       try {
           DB::connection()->getPdo();
           return 'Database connected!';
       } catch (\Exception $e) {
           return 'Database error: ' . $e->getMessage();
       }
   });
   ```

### Error: "No application encryption key has been specified"

**Cause**: APP_KEY not set

**Solution**:
1. Generate key locally:
   ```bash
   php artisan key:generate --show
   ```

2. Copy the output (starts with `base64:`)

3. Add to Vercel Environment Variables:
   ```
   APP_KEY=base64:your-generated-key
   ```

4. Redeploy

## 🧪 Testing Steps

### 1. Test Locally First

```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Test helper functions
php artisan tinker
>>> image_url('test.jpg')
>>> category_image_url(App\Models\Category::first())

# Start server
php artisan serve

# Test in browser
http://localhost:8000
```

### 2. Check Build Logs

1. Go to Vercel Dashboard
2. Latest deployment
3. "Building" tab
4. Look for errors

### 3. Check Runtime Logs

1. Go to Vercel Dashboard
2. Latest deployment
3. "Runtime Logs" tab
4. Look for PHP errors

### 4. Test Endpoints

```bash
# Test homepage
curl https://your-domain.vercel.app/

# Test API
curl https://your-domain.vercel.app/api/test

# Test specific route
curl https://your-domain.vercel.app/products
```

## 📋 Debugging Checklist

- [ ] Build completed successfully (no build errors)
- [ ] Composer autoload generated
- [ ] All environment variables set
- [ ] APP_KEY is set
- [ ] Database credentials correct
- [ ] Database accessible from Vercel
- [ ] Helper files in correct location
- [ ] composer.json autoload configured
- [ ] No syntax errors in new files
- [ ] Previous deployment worked (for comparison)

## 🚀 Quick Fix Commands

```bash
# Local testing
composer dump-autoload
php artisan config:clear
php artisan view:clear
php artisan serve

# Check for syntax errors
php -l app/Helpers/ImageHelper.php
php -l app/Helpers/helpers.php

# Test helper functions
php artisan tinker --execute="echo image_url('test.jpg');"

# View Vercel logs
vercel logs

# Redeploy
git push origin master
```

## 📞 Need More Help?

### Check These Files
1. `storage/logs/laravel.log` (local)
2. Vercel Runtime Logs (production)
3. Vercel Build Logs (deployment)

### Provide This Info
- Error message from logs
- Which page/route causes error
- Recent changes made
- Environment (local works? Vercel fails?)

## ✨ Expected Resolution

After applying fixes:
- ✅ Build completes successfully
- ✅ No errors in runtime logs
- ✅ Homepage loads
- ✅ Images display correctly
- ✅ All routes work

---

**Current Status**: Investigating 500 error
**Latest Fix**: Removed double slash in image URLs
**Next Step**: Check Vercel logs for specific error
