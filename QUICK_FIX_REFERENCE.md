# Quick Fix Reference - Upload File

## 🎯 Masalah
Upload file gagal dengan error: "Unable to create a directory at /var/task/user/storage/app/public"

## ⚡ Quick Fix

### 1. Run Setup Script
```bash
php scripts/ensure-storage.php
```

### 2. Create Storage Link (Local Only)
```bash
php artisan storage:link
```

### 3. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test Upload
Login ke admin panel dan test upload:
- Banner: `/admin/banners/create`
- Brand: `/admin/brands/create`
- Product: `/admin/products/create`
- Category: `/admin/categories/images/edit`

## 🔍 Verify Fix

### Check Directories
```bash
ls -la storage/app/public/
# Should show: banners, brands, categories, products, reviews
```

### Check Storage Link
```bash
ls -la public/storage
# Should be symlink to storage/app/public
```

### Check Logs
```bash
tail -f storage/logs/laravel.log
# Watch for upload errors
```

## 🚀 Deploy to Vercel

### 1. Commit Changes
```bash
git add .
git commit -m "Fix: Upload file functionality for serverless"
git push
```

### 2. Verify Build
Check Vercel build logs untuk:
```
Ensuring storage directories exist...
✓ Created: storage/app/public/banners
✓ Created: storage/app/public/brands
...
Storage setup complete!
```

### 3. Test Production
Login ke production admin panel dan test upload

## ⚠️ Important Notes

### Local Development
- ✅ Files stored in `storage/app/public`
- ✅ Persistent storage
- ✅ Accessible via `public/storage` symlink

### Production (Vercel)
- ⚠️ Files stored in `/tmp/storage`
- ⚠️ Temporary (cleared after function execution)
- 💡 **Recommendation**: Use S3 for production

## 🔧 Troubleshooting

### Upload Still Fails
```bash
# 1. Check permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 2. Recreate directories
php scripts/ensure-storage.php

# 3. Clear all cache
php artisan optimize:clear

# 4. Check logs
tail -f storage/logs/laravel.log
```

### Files Not Showing
```bash
# 1. Recreate storage link
rm public/storage
php artisan storage:link

# 2. Check APP_URL in .env
# Should match your domain
```

### Permission Denied
```bash
# Linux/Mac
sudo chown -R $USER:$USER storage
sudo chmod -R 755 storage

# Windows (Run as Administrator)
icacls storage /grant Users:F /t
```

## 📞 Need Help?

Check detailed documentation:
- `UPLOAD_FIX_SUMMARY.md` - Quick summary
- `STORAGE_FIX_GUIDE.md` - User guide
- `FILE_UPLOAD_COMPLETE_FIX.md` - Technical details

## ✅ Checklist

- [ ] Run `php scripts/ensure-storage.php`
- [ ] Run `php artisan storage:link`
- [ ] Clear cache
- [ ] Test upload locally
- [ ] Commit and push
- [ ] Test upload in production
- [ ] (Optional) Setup S3 for production

## 🎉 Done!

Upload functionality should now work perfectly!
