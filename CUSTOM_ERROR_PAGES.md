# Custom Error Pages - Complete Guide

## Overview
Custom error pages yang sudah dibuat untuk memberikan user experience yang lebih baik saat terjadi error.

## Error Pages yang Tersedia

### 1. **404 - Page Not Found** ✅
**File:** `resources/views/errors/404.blade.php`

**Features:**
- Floating animation icon
- "Page Not Found" message
- Links: Home, Browse Products
- Contact support link

**When it appears:**
- URL tidak ditemukan
- Route tidak ada
- Resource sudah dihapus

---

### 2. **403 - Forbidden** ✅
**File:** `resources/views/errors/403.blade.php`

**Features:**
- Lock icon illustration
- "Access Forbidden" message
- Links: Home, My Account (if logged in) / Login (if guest)

**When it appears:**
- User tidak punya permission
- Akses admin tanpa role admin
- Protected route

---

### 3. **419 - Page Expired** ✅
**File:** `resources/views/errors/419.blade.php`

**Features:**
- Clock icon illustration
- "Page Expired" message
- Refresh button
- Auto-reload functionality

**When it appears:**
- CSRF token expired
- Session timeout
- Form submission setelah lama idle

---

### 4. **429 - Too Many Requests** ✅
**File:** `resources/views/errors/429.blade.php`

**Features:**
- Lightning icon illustration
- "Too Many Requests" message
- **Countdown timer** (60 seconds)
- Auto-reload setelah countdown selesai

**When it appears:**
- Rate limiting triggered
- Terlalu banyak request dalam waktu singkat
- API throttling

---

### 5. **500 - Internal Server Error** ✅
**File:** `resources/views/errors/500.blade.php`

**Features:**
- Warning icon with pulse animation
- "Internal Server Error" message
- Try Again & Home buttons
- **Debug info** (hanya di development mode)

**When it appears:**
- PHP error
- Database connection error
- Uncaught exception
- Server crash

---

### 6. **503 - Service Unavailable** ✅
**File:** `resources/views/errors/503.blade.php`

**Features:**
- Spinning gear icon
- "Service Unavailable" message
- Maintenance status badge
- Try Again button

**When it appears:**
- Maintenance mode (`php artisan down`)
- Server overload
- Deployment in progress

---

## Design Features

### Common Elements:
✅ **Logo** - JastipHype logo di setiap page
✅ **Gradient Background** - Warna berbeda per error type
✅ **Responsive** - Mobile-friendly
✅ **Animations** - Smooth transitions & effects
✅ **Clear CTAs** - Action buttons yang jelas
✅ **Consistent Styling** - Tailwind CSS

### Color Schemes:
- **404** - Gray (neutral)
- **403** - Yellow/Orange (warning)
- **419** - Blue/Indigo (info)
- **429** - Purple/Pink (rate limit)
- **500** - Red/Orange (error)
- **503** - Gray (maintenance)

### Animations:
- **404** - Float animation
- **419** - None (static)
- **429** - Countdown timer
- **500** - Pulse animation
- **503** - Spinning gear

---

## Testing

### Test 404:
```
Visit: https://jastiphype.vercel.app/non-existent-page
```

### Test 403:
```php
// In route or controller
abort(403);
```

### Test 419:
```
1. Open a form page
2. Wait 2+ hours (session expires)
3. Submit form
```

### Test 429:
```
Make 60+ requests in 1 minute to same endpoint
```

### Test 500:
```php
// In controller
throw new \Exception('Test error');
```

### Test 503:
```bash
php artisan down
# Visit any page
php artisan up
```

---

## Customization

### Change Logo:
```blade
<img src="{{ asset('images/logo/YOUR_LOGO.png') }}" alt="Your Brand">
```

### Change Colors:
```html
<!-- Example: Change 404 from gray to blue -->
<body class="bg-gradient-to-br from-blue-50 to-indigo-50">
```

### Add More Links:
```blade
<a href="{{ route('your.route') }}" class="...">
    Your Link
</a>
```

### Disable Debug Info (500 page):
```php
// In .env
APP_DEBUG=false
```

---

## Benefits

✅ **Better UX** - User-friendly error messages
✅ **Brand Consistency** - Logo & styling match main site
✅ **Clear Actions** - Users know what to do next
✅ **Professional** - Looks polished & complete
✅ **SEO Friendly** - Proper HTTP status codes
✅ **Mobile Responsive** - Works on all devices
✅ **Animations** - Engaging & modern
✅ **Helpful** - Contact support links

---

## Laravel Configuration

Error pages automatically work with Laravel's exception handling. No additional configuration needed!

### How it works:
1. Laravel detects error (404, 500, etc.)
2. Looks for `resources/views/errors/{code}.blade.php`
3. Renders custom page if exists
4. Falls back to default if not found

### Exception Handler:
Located at: `app/Exceptions/Handler.php`

You can customize error handling there if needed.

---

## Production Checklist

Before deploying:

- [ ] Set `APP_DEBUG=false` in production `.env`
- [ ] Test all error pages
- [ ] Verify logo displays correctly
- [ ] Check all links work
- [ ] Test on mobile devices
- [ ] Verify animations work
- [ ] Check countdown timer (429)
- [ ] Test maintenance mode (503)

---

## Notes

- Error pages use **Tailwind CDN** for styling (no build required)
- All pages are **standalone** (no layout dependencies)
- **Animations** are CSS-only (no JavaScript except countdown)
- **Responsive** by default
- **Accessible** with proper semantic HTML

---

## Support

If you need to customize further or add more error pages, follow the same pattern:

1. Create `resources/views/errors/{code}.blade.php`
2. Use Tailwind CDN for styling
3. Add logo, icon, message, and CTAs
4. Test thoroughly

Happy error handling! 🎨
