# 🚀 Deployment Instructions

## SSH Connection Timeout - Manual Deployment Required

Karena SSH connection timeout, silakan deploy manual dengan cara berikut:

## 📋 Option 1: Via SSH Terminal

1. **Login ke server:**
```bash
ssh u909490256@srv1.niagahosting.com -p 65002
```

2. **Navigate ke project directory:**
```bash
cd domains/jastiphype.shop
```

3. **Run deployment script:**
```bash
chmod +x deploy-to-production.sh
./deploy-to-production.sh
```

## 📋 Option 2: Manual Commands

Jika script tidak bisa dijalankan, run commands ini satu per satu:

```bash
# 1. Navigate to project
cd ~/domains/jastiphype.shop

# 2. Pull latest changes
git pull origin master

# 3. Run migrations
php artisan migrate --force

# 4. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 5. Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Create storage directory
mkdir -p storage/app/gdpr-exports
chmod -R 775 storage/app/gdpr-exports

# 7. Test commands
php artisan list gdpr
php artisan list security
```

## 📋 Option 3: Via cPanel File Manager

1. Login ke cPanel
2. Open Terminal
3. Run commands dari Option 2

## ✅ Verification

Setelah deployment, verify dengan:

1. **Check cookie banner:**
   - Visit: https://jastiphype.shop
   - Cookie banner should appear after 1 second

2. **Check privacy policy:**
   - Visit: https://jastiphype.shop/gdpr/privacy-policy
   - Should load without errors

3. **Check GDPR dashboard:**
   - Login as user
   - Visit: https://jastiphype.shop/gdpr/dashboard
   - Should show export/deletion options

4. **Check admin dashboards:**
   - Login as admin
   - Visit: https://jastiphype.shop/admin/gdpr
   - Visit: https://jastiphype.shop/admin/security

5. **Test commands:**
```bash
php artisan gdpr:process-exports
php artisan gdpr:cleanup-exports
php artisan security:cleanup
```

## 🔧 Setup Cron Jobs

Add these to crontab (crontab -e):

```bash
# Process GDPR exports every hour
0 * * * * cd ~/domains/jastiphype.shop && php artisan gdpr:process-exports >> /dev/null 2>&1

# Cleanup expired exports daily at midnight
0 0 * * * cd ~/domains/jastiphype.shop && php artisan gdpr:cleanup-exports >> /dev/null 2>&1

# Cleanup security logs daily at 1 AM
0 1 * * * cd ~/domains/jastiphype.shop && php artisan security:cleanup >> /dev/null 2>&1
```

## 🐛 Troubleshooting

### Migration Errors

If migrations fail:
```bash
# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check migrations table
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback
php artisan migrate --force
```

### Cache Issues

If pages show errors:
```bash
# Clear everything
php artisan optimize:clear

# Rebuild
php artisan optimize
```

### Permission Issues

If storage errors occur:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 📞 Need Help?

Jika ada masalah:
1. Check logs: `tail -f storage/logs/laravel.log`
2. Check error: `php artisan tinker`
3. Contact: info@jastiphype.shop

## 📚 Documentation

- Quick Start: `GDPR_QUICKSTART.md`
- Full Guide: `GDPR_SECURITY_IMPLEMENTATION.md`
- Security: `SECURITY_CHECKLIST.md`
- Summary: `IMPLEMENTATION_SUMMARY.md`

---

<p align="center">
  <sub>© 2026 JastipHype. All rights reserved.</sub>
</p>
