# Deploy Translation Changes

## Current Status
✅ Changes committed and pushed to GitHub  
⚠️ Changes NOT yet deployed to live server  
🔄 Manual deployment required

## Verification Results
The website is currently showing Indonesian text. You need to deploy the changes manually via SSH.

## SSH Deployment Steps

### 1. Connect to Server
```bash
ssh u909490256@jastiphype.shop -p 65002
```

### 2. Navigate to Project Directory
```bash
cd /home/u909490256/domains/jastiphype.shop
```

### 3. Check Current Status
```bash
# Check current branch and commit
git status
git log -1 --oneline

# Should show: 6f08031b Translate Indonesian content to English
```

### 4. Pull Latest Changes
```bash
git pull origin master
```

Expected output:
```
Updating 8943a116..6f08031b
Fast-forward
 resources/views/admin-bootstrap-backup/banners/create.blade.php       | 4 ++--
 resources/views/admin-bootstrap-backup/banners/edit.blade.php         | 4 ++--
 resources/views/admin-bootstrap-backup/categories/images.blade.php    | 20 ++++++++++----------
 resources/views/admin-bootstrap-backup/products/create.blade.php      | 12 ++++++------
 resources/views/admin/banners/create.blade.php                        | 4 ++--
 resources/views/admin/categories/images.blade.php                     | 16 ++++++++--------
 resources/views/admin/products/create.blade.php                       | 4 ++--
 resources/views/components/cookie-consent.blade.php                   | 24 ++++++++++++------------
 resources/views/gdpr/cookie-policy.blade.php                          | 68 +++++++++++++++++++++++++++++++++++++++++---------------------------
 resources/views/gdpr/privacy-policy.blade.php                         | 62 ++++++++++++++++++++++++++++++++++++++++----------------------
 10 files changed, 162 insertions(+), 162 deletions(-)
```

### 5. Clear All Caches
```bash
# Clear application cache
php artisan cache:clear

# Clear view cache (IMPORTANT for Blade templates)
php artisan view:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear
```

### 6. Optimize for Production
```bash
# Cache config for better performance
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### 7. Verify Deployment
```bash
# Check if files were updated
grep -n "Last updated" resources/views/gdpr/privacy-policy.blade.php | head -1

# Should show: 11:        <p class="text-gray-600 mb-4">Last updated: {{ date('d F Y') }}</p>
```

### 8. Test Website
Visit these URLs to verify:
- https://jastiphype.shop/gdpr/privacy-policy
- https://jastiphype.shop/gdpr/cookie-policy

Look for English text:
- ✅ "Last updated" (not "Terakhir diperbarui")
- ✅ "Cookie Details" (not "Detail Cookies")
- ✅ "Accept All" (not "Terima Semua")
- ✅ "Essential Cookies" (not "Cookies Penting")

## Quick One-Liner Deployment

If you want to do everything in one command:

```bash
cd /home/u909490256/domains/jastiphype.shop && git pull origin master && php artisan cache:clear && php artisan view:clear && php artisan config:clear && php artisan route:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && echo "✅ Deployment complete!"
```

## Troubleshooting

### If changes don't appear after cache clear:

1. **Check file permissions:**
```bash
ls -la resources/views/gdpr/
```

2. **Manually verify file content:**
```bash
head -20 resources/views/gdpr/privacy-policy.blade.php
```

3. **Check Laravel logs:**
```bash
tail -50 storage/logs/laravel.log
```

4. **Restart PHP-FPM (if available):**
```bash
# This might require sudo or may not be available on shared hosting
killall -9 php-fpm
```

### If git pull fails:

```bash
# Check for local changes
git status

# If there are conflicts, stash local changes
git stash

# Pull again
git pull origin master

# Reapply stashed changes if needed
git stash pop
```

## Files Changed

The following files were translated from Indonesian to English:

1. `resources/views/gdpr/privacy-policy.blade.php` - Privacy policy page
2. `resources/views/gdpr/cookie-policy.blade.php` - Cookie policy page
3. `resources/views/components/cookie-consent.blade.php` - Cookie consent banner
4. `resources/views/admin/categories/images.blade.php` - Admin category images
5. `resources/views/admin/products/create.blade.php` - Admin product creation
6. `resources/views/admin/banners/create.blade.php` - Admin banner creation
7. Bootstrap backup versions of the above admin files

## Expected Result

After deployment, all user-facing text should be in English, making the website more accessible to international users.

## Need Help?

If you encounter any issues:
1. Check the Laravel logs: `tail -50 storage/logs/laravel.log`
2. Verify Git status: `git status && git log -1`
3. Contact support with the error message
