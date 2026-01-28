# SHOP Link Diagnostic

## Issue
User melaporkan saat masuk ke bagian SHOP, halaman hilang.

## Checks Performed

### 1. Routes ✅
```bash
php artisan route:list --name=products
```
Result:
- GET /products → products.index ✅
- GET /products/{slug} → products.show ✅

### 2. Controller ✅
- File exists: `app/Http/Controllers/ProductController.php` ✅

### 3. Navigation Links ✅
Desktop menu (line 118):
```html
<a href="{{ route('products.index') }}">SHOP</a>
```

Mobile menu (line 512):
```html
<a href="{{ route('products.index') }}">SHOP</a>
```

### 4. No Duplicates Found
- Checked for duplicate SHOP links
- None found in current version

## Possible Issues

### Issue 1: Cache Problem
**Solution:**
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Issue 2: Products Table Empty
**Check:**
```sql
SELECT COUNT(*) FROM products;
```

If 0, run seeder:
```bash
php artisan db:seed --class=ProductSeeder
```

### Issue 3: Missing View File
**Check:**
```bash
# Should exist
resources/views/products/index.blade.php
```

### Issue 4: JavaScript Error
**Check browser console (F12)**
- Look for JavaScript errors
- Check network tab for failed requests

### Issue 5: Alpine.js Conflict
**Check if Alpine.js is loaded:**
```html
<!-- Should be in layout -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

## Quick Fix Steps

### Step 1: Clear All Cache
```bash
php artisan optimize:clear
```

### Step 2: Check Database
```bash
php artisan tinker
>>> App\Models\Product::count()
```

### Step 3: Test Route Directly
```
http://localhost:8000/products
```

### Step 4: Check Logs
```bash
tail -f storage/logs/laravel.log
```

## Debug Mode

Enable debug to see errors:
```env
# .env
APP_DEBUG=true
```

Then visit `/products` and check error message.

## Common Solutions

### Solution 1: Re-seed Database
```bash
php artisan migrate:fresh --seed
```

### Solution 2: Rebuild Assets
```bash
npm run build
```

### Solution 3: Check Permissions
```bash
chmod -R 775 storage bootstrap/cache
```

## Testing Checklist

- [ ] Can access homepage?
- [ ] Can access other pages?
- [ ] Only SHOP page has issue?
- [ ] Error in browser console?
- [ ] Error in Laravel log?
- [ ] Products exist in database?
- [ ] View file exists?

## Need More Info

Please provide:
1. Screenshot of error (if any)
2. Browser console errors (F12)
3. Laravel log errors
4. What happens when you click SHOP?
   - Blank page?
   - 404 error?
   - 500 error?
   - Redirect somewhere?

## Status

Waiting for more details about the issue.
