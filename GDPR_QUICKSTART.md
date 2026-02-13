# 🚀 GDPR & Security Quick Start Guide

## ⚡ 5-Minute Setup

### Step 1: Run Migrations (1 min)

```bash
php artisan migrate
```

### Step 2: Test Cookie Banner (1 min)

1. Visit homepage: `http://localhost:8000`
2. Cookie banner should appear
3. Click "Terima Semua" or "Pengaturan"

### Step 3: Test GDPR Dashboard (2 min)

1. Login as user
2. Visit: `http://localhost:8000/gdpr/dashboard`
3. Click "Request Data Export"
4. Run: `php artisan gdpr:process-exports`
5. Refresh page - download link appears

### Step 4: Setup Cron Jobs (1 min)

Add to crontab or Task Scheduler:

```bash
# Process GDPR exports every hour
0 * * * * cd /path/to/jastiphype && php artisan gdpr:process-exports

# Cleanup expired exports daily
0 0 * * * cd /path/to/jastiphype && php artisan gdpr:cleanup-exports

# Cleanup security logs daily
0 1 * * * cd /path/to/jastiphype && php artisan security:cleanup
```

---

## 📋 Quick Reference

### Important URLs

| Feature | URL | Access |
|---------|-----|--------|
| Privacy Policy | `/gdpr/privacy-policy` | Public |
| Cookie Policy | `/gdpr/cookie-policy` | Public |
| GDPR Dashboard | `/gdpr/dashboard` | User |
| Admin GDPR | `/admin/gdpr` | Admin |
| Admin Security | `/admin/security` | Admin |

### Artisan Commands

```bash
# Process pending export requests
php artisan gdpr:process-exports

# Cleanup expired exports
php artisan gdpr:cleanup-exports

# Cleanup security logs
php artisan security:cleanup
```

### Testing Checklist

- [ ] Cookie banner appears on first visit
- [ ] Cookie preferences can be customized
- [ ] Privacy policy page loads
- [ ] GDPR dashboard accessible for logged-in users
- [ ] Data export request creates pending request
- [ ] Export command processes requests
- [ ] Download link works and expires after 7 days
- [ ] Data deletion request creates pending request
- [ ] Admin can approve/reject deletion requests
- [ ] Failed login attempts are tracked
- [ ] IP gets blocked after 5 failed attempts
- [ ] Security events are logged

---

## 🔧 Common Tasks

### Add Link to Footer

Edit `resources/views/layouts/footer.blade.php`:

```html
<a href="{{ route('gdpr.privacy-policy') }}">Privacy Policy</a>
<a href="{{ route('gdpr.cookie-policy') }}">Cookie Policy</a>
<a href="{{ route('gdpr.dashboard') }}">My Data</a>
```

### Customize Cookie Banner

Edit `resources/views/components/cookie-consent.blade.php`:

```javascript
// Change delay before showing banner
setTimeout(() => {
    document.getElementById('cookieConsent').classList.remove('hidden');
}, 1000); // Change to 3000 for 3 seconds
```

### Change Export Expiry

Edit `.env`:

```env
GDPR_EXPORT_EXPIRY_DAYS=7  # Change to 14 for 14 days
```

### Customize Security Thresholds

Edit `app/Services/SecurityService.php`:

```php
// Change max failed attempts
public function checkFailedLoginAttempts(string $email): int
{
    return DB::table('login_attempts')
        ->where('email', $email)
        ->where('successful', false)
        ->where('attempted_at', '>', now()->subMinutes(15)) // Change to 30
        ->count();
}
```

---

## 🐛 Troubleshooting

### Cookie Banner Not Showing

```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Check if component exists
ls resources/views/components/cookie-consent.blade.php

# Check if included in layout
grep "cookie-consent" resources/views/layouts/app.blade.php
```

### Export Not Processing

```bash
# Check pending requests
php artisan tinker
>>> App\Models\DataExportRequest::where('status', 'pending')->count()

# Process manually
php artisan gdpr:process-exports

# Check logs
tail -f storage/logs/laravel.log
```

### IP Blocking Not Working

```bash
# Check middleware
grep "CheckIpBlocked" bootstrap/app.php

# Check blocked IPs
php artisan tinker
>>> DB::table('blocked_ips')->get()

# Test blocking
php artisan tinker
>>> app(App\Services\SecurityService::class)->blockIpTemporarily('1.2.3.4', 'Test', 60)
```

---

## 📚 Next Steps

1. **Customize Privacy Policy** - Edit `resources/views/gdpr/privacy-policy.blade.php`
2. **Add Analytics** - Integrate Google Analytics with cookie consent
3. **Email Notifications** - Setup email for export/deletion requests
4. **Admin Training** - Train admins on GDPR management
5. **Legal Review** - Have legal team review privacy policy

---

## 💡 Pro Tips

1. **Test in Incognito** - Always test cookie banner in incognito mode
2. **Monitor Logs** - Check security logs regularly for suspicious activity
3. **Backup Before Deletion** - Always backup before processing deletion requests
4. **Document Changes** - Keep changelog of privacy policy updates
5. **Regular Audits** - Audit GDPR compliance quarterly

---

## 📞 Need Help?

- 📖 Full Documentation: `GDPR_SECURITY_IMPLEMENTATION.md`
- 📧 Email: info@jastiphype.shop
- 🐛 Issues: [GitHub Issues](https://github.com/Dialius/JastipHype/issues)

---

<p align="center">
  <sub>© 2026 JastipHype. All rights reserved.</sub>
</p>
