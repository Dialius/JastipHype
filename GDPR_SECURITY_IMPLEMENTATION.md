# 🔒 GDPR & Security Implementation Guide

## 📋 Overview

Implementasi lengkap GDPR Compliance dan Security improvements untuk JastipHype, termasuk:
- Cookie consent management
- Privacy policy & terms
- Data export & deletion
- Security monitoring
- IP blocking
- Login attempt tracking

---

## 🚀 Installation

### 1. Run Migrations

```bash
php artisan migrate
```

Ini akan membuat tables:
- `cookie_consents` - Tracking cookie preferences
- `data_export_requests` - User data export requests
- `data_deletion_requests` - User data deletion requests
- `login_attempts` - Login attempt tracking
- `security_events` - Security event logging
- `blocked_ips` - IP blocking management
- `user_sessions` - Active session tracking

### 2. Register Commands

Commands sudah otomatis terdaftar. Verifikasi dengan:

```bash
php artisan list gdpr
php artisan list security
```

### 3. Schedule Commands (Optional)

Tambahkan ke `app/Console/Kernel.php` atau `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('gdpr:process-exports')->hourly();
Schedule::command('gdpr:cleanup-exports')->daily();
Schedule::command('security:cleanup')->daily();
```

---

## 🎯 Features

### 1. Cookie Consent Banner

**Lokasi:** Otomatis muncul di semua halaman

**Fitur:**
- Accept all cookies
- Reject non-essential cookies
- Custom cookie preferences
- 4 kategori cookies: Necessary, Functional, Analytics, Marketing

**Customization:**
Edit `resources/views/components/cookie-consent.blade.php`

### 2. Privacy Policy

**URL:** `/gdpr/privacy-policy`

**Konten:**
- Informasi yang dikumpulkan
- Penggunaan data
- Hak pengguna (GDPR)
- Keamanan data
- Cookie policy
- Retensi data

**Edit:** `resources/views/gdpr/privacy-policy.blade.php`

### 3. Cookie Policy

**URL:** `/gdpr/cookie-policy`

**Konten:**
- Penjelasan cookies
- Jenis cookies yang digunakan
- Cara mengelola cookies
- Link ke browser settings

**Edit:** `resources/views/gdpr/cookie-policy.blade.php`

### 4. GDPR Dashboard

**URL:** `/gdpr/dashboard` (requires login)

**Fitur:**
- Request data export
- Download exported data
- Request data deletion
- View request history
- GDPR rights information

**Screenshot:**
```
┌─────────────────────────────────────┐
│  📥 Export Data    │  🗑️ Delete Data │
│  ─────────────────────────────────  │
│  Request export    │  Request delete │
│  Download history  │  View status    │
└─────────────────────────────────────┘
```

### 5. Data Export

**Process:**
1. User requests export via dashboard
2. System creates export request (status: pending)
3. Cron job processes request (hourly)
4. User receives email with download link
5. Link expires after 7 days

**Data Included:**
- Personal information
- Order history
- Reviews
- Wishlist
- Cart items
- Activity logs
- Security events

**Format:** JSON

**Manual Processing:**
```bash
php artisan gdpr:process-exports
```

### 6. Data Deletion

**Process:**
1. User requests deletion via dashboard
2. Admin reviews request
3. Admin approves/rejects
4. If approved, admin processes deletion
5. User data is anonymized/deleted

**What Gets Deleted:**
- User profile
- Reviews
- Wishlist
- Cart
- Activity logs
- Security events

**What Gets Anonymized:**
- Orders (kept for legal/accounting)
  - Name → "Deleted User"
  - Email → "deleted@example.com"
  - Phone → null
  - Address → "Address Deleted"

### 7. Security Monitoring

**Features:**
- Login attempt tracking
- Failed login detection
- Automatic IP blocking (after 5 failed attempts)
- Security event logging
- Session management

**Events Tracked:**
- password_change
- email_change
- profile_update
- 2fa_enabled
- suspicious_activity

### 8. IP Blocking

**Automatic Blocking:**
- 5+ failed login attempts in 15 minutes
- Blocked for 60 minutes

**Manual Blocking:**
Admin can block IPs via admin panel with:
- Custom reason
- Custom duration
- Permanent block option

**Unblocking:**
- Automatic (when expires)
- Manual (admin action)

---

## 🛠️ Admin Features

### Security Dashboard

**URL:** `/admin/security` (admin only)

**Features:**
- Recent security events
- Failed login attempts
- Blocked IPs list
- Security statistics
- Block/unblock IPs

### GDPR Management

**URL:** `/admin/gdpr` (admin only)

**Features:**
- View all export requests
- View all deletion requests
- Approve/reject deletion requests
- Process approved deletions
- Add admin notes

---

## 🔧 Configuration

### Environment Variables

```env
# GDPR Settings
GDPR_EXPORT_EXPIRY_DAYS=7
GDPR_RETENTION_PERIOD_YEARS=5

# Security Settings
SECURITY_MAX_LOGIN_ATTEMPTS=5
SECURITY_LOGIN_LOCKOUT_MINUTES=60
SECURITY_BLOCK_DURATION_MINUTES=60
```

### Customization

**Cookie Categories:**
Edit `resources/views/components/cookie-consent.blade.php`:
```javascript
function saveCookieConsent(necessary, functional, analytics, marketing) {
    // Add your custom logic here
}
```

**Security Rules:**
Edit `app/Services/SecurityService.php`:
```php
public function checkFailedLoginAttempts(string $email): int
{
    // Customize threshold
    return DB::table('login_attempts')
        ->where('email', $email)
        ->where('successful', false)
        ->where('attempted_at', '>', now()->subMinutes(15))
        ->count();
}
```

---

## 📊 Database Schema

### cookie_consents
```sql
- id
- user_id (nullable)
- session_id (nullable)
- necessary (boolean)
- functional (boolean)
- analytics (boolean)
- marketing (boolean)
- ip_address
- user_agent
- created_at
- updated_at
```

### data_export_requests
```sql
- id
- user_id
- status (pending|processing|completed|failed)
- file_path (nullable)
- completed_at (nullable)
- expires_at (nullable)
- created_at
- updated_at
```

### data_deletion_requests
```sql
- id
- user_id
- status (pending|approved|processing|completed|rejected)
- reason (nullable)
- admin_notes (nullable)
- approved_by (nullable)
- approved_at (nullable)
- completed_at (nullable)
- created_at
- updated_at
```

### security_events
```sql
- id
- user_id (nullable)
- event_type
- ip_address
- user_agent
- metadata (json)
- created_at
- updated_at
```

### login_attempts
```sql
- id
- email
- ip_address
- successful (boolean)
- user_agent
- attempted_at
```

### blocked_ips
```sql
- id
- ip_address (unique)
- reason
- blocked_by (nullable)
- expires_at (nullable)
- created_at
- updated_at
```

---

## 🔐 Security Best Practices

### 1. Password Requirements

Implemented in `SecurityService::validatePasswordStrength()`:
- Minimum 8 characters
- At least 1 uppercase letter
- At least 1 lowercase letter
- At least 1 number
- At least 1 special character

### 2. Password Breach Check

Uses HaveIBeenPwned API to check if password has been compromised:
```php
$securityService->isPasswordCompromised($password);
```

### 3. Rate Limiting

Applied to sensitive routes:
- Login: 5 attempts per 15 minutes
- Password reset: 3 attempts per hour
- API endpoints: 60 requests per minute

### 4. CSRF Protection

Enabled by default on all POST/PUT/DELETE requests.

### 5. XSS Prevention

- Input sanitization
- Output escaping (Blade templates)
- Content Security Policy headers

---

## 📝 Usage Examples

### Check if User Consented to Analytics

```php
$consent = CookieConsent::where('user_id', auth()->id())
    ->latest()
    ->first();

if ($consent && $consent->analytics) {
    // Load analytics scripts
}
```

### Log Security Event

```php
use App\Services\SecurityService;

$securityService = app(SecurityService::class);
$securityService->logSecurityEvent(
    auth()->id(),
    'password_change',
    ['old_password_hash' => $oldHash]
);
```

### Check if IP is Blocked

```php
use App\Services\SecurityService;

$securityService = app(SecurityService::class);
if ($securityService->isIpBlocked(request()->ip())) {
    abort(403, 'Your IP has been blocked');
}
```

### Export User Data Programmatically

```php
use App\Services\GdprService;

$gdprService = app(GdprService::class);
$request = $gdprService->exportUserData($user);

// Check status
if ($request->status === 'completed') {
    $filePath = $request->file_path;
}
```

---

## 🧪 Testing

### Test Cookie Consent

1. Visit homepage
2. Cookie banner should appear after 1 second
3. Click "Pengaturan" to customize
4. Toggle preferences and save
5. Refresh page - banner should not appear

### Test Data Export

1. Login as user
2. Visit `/gdpr/dashboard`
3. Click "Request Data Export"
4. Run: `php artisan gdpr:process-exports`
5. Refresh dashboard - download link should appear
6. Click download to get JSON file

### Test Data Deletion

1. Login as user
2. Visit `/gdpr/dashboard`
3. Click "Request Data Deletion"
4. Login as admin
5. Visit `/admin/gdpr`
6. Approve deletion request
7. Click "Process Deletion"
8. User account should be deleted

### Test IP Blocking

1. Try to login with wrong password 5 times
2. IP should be automatically blocked
3. Try to login again - should see 403 error
4. Wait 60 minutes or unblock via admin panel

---

## 🚨 Troubleshooting

### Cookie Banner Not Showing

Check:
1. Cookie consent component included in layout
2. JavaScript not blocked by browser
3. No existing cookie consent cookie

### Export Not Processing

Check:
1. Cron job is running
2. Storage directory is writable
3. Run manually: `php artisan gdpr:process-exports`
4. Check logs: `storage/logs/laravel.log`

### IP Blocking Not Working

Check:
1. Middleware registered in `bootstrap/app.php`
2. Database table exists
3. IP address is correct (check behind proxy)

---

## 📚 Resources

- [GDPR Official Site](https://gdpr.eu/)
- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Cookie Consent Best Practices](https://www.cookiebot.com/en/gdpr-cookies/)

---

## 📞 Support

Jika ada pertanyaan atau masalah:
- Email: info@jastiphype.shop
- GitHub Issues: [Create Issue](https://github.com/Dialius/JastipHype/issues)

---

## ✅ Checklist

- [x] Database migrations
- [x] Cookie consent banner
- [x] Privacy policy page
- [x] Cookie policy page
- [x] GDPR dashboard
- [x] Data export functionality
- [x] Data deletion functionality
- [x] Security monitoring
- [x] IP blocking
- [x] Login attempt tracking
- [x] Admin security dashboard
- [x] Admin GDPR management
- [x] Console commands
- [x] Middleware registration
- [x] Documentation

---

<p align="center">
  <sub>© 2026 JastipHype. All rights reserved.</sub>
</p>
