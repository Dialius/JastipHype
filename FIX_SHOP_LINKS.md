# Fix Shop Links - Replace Hardcoded URLs

## Problem
Beberapa button dan card masih menggunakan hardcoded `/shop` URL yang tidak berfungsi.

## Solution
Replace semua hardcoded URLs dengan Laravel route helpers.

## Changes Made

### 1. Footer Links (`resources/views/layouts/footer.blade.php`)

**Before:**
```html
<li><a href="/shop">All Products</a></li>
<li><a href="/new-arrivals">New Arrivals</a></li>
<li><a href="/limited-editions">Limited Editions</a></li>
<li><a href="/brands">Brands</a></li>
```

**After:**
```html
<li><a href="{{ route('products.index') }}">All Products</a></li>
<li><a href="{{ route('products.index', ['sort' => 'newest']) }}">New Arrivals</a></li>
<li><a href="{{ route('products.index', ['availability' => ['limited']]) }}">Limited Editions</a></li>
<li><a href="{{ route('products.index') }}">Brands</a></li>
```

### 2. Home Page Buttons (`resources/views/home/index.blade.php`)

**Before:**
```html
<a href="/shop">Shop Now</a>
<a href="/limited-editions">View Collection</a>
```

**After:**
```html
<a href="{{ route('products.index') }}">Shop Now</a>
<a href="{{ route('products.index', ['availability' => ['limited']]) }}">View Collection</a>
```

### 3. Category Cards (`resources/views/home/index.blade.php`)

**Before:**
```html
<a href="/shop?category={{ $category->slug }}">
```

**After:**
```html
<a href="{{ route('products.index', ['category' => $category->slug]) }}">
```

### 4. New Arrivals Link (`resources/views/home/index.blade.php`)

**Before:**
```html
<a href="/shop?sort=newest">View All →</a>
```

**After:**
```html
<a href="{{ route('products.index', ['sort' => 'newest']) }}">View All →</a>
```

## Summary of Fixed Links

| Location | Old URL | New Route |
|----------|---------|-----------|
| Footer - All Products | `/shop` | `route('products.index')` |
| Footer - New Arrivals | `/new-arrivals` | `route('products.index', ['sort' => 'newest'])` |
| Footer - Limited | `/limited-editions` | `route('products.index', ['availability' => ['limited']])` |
| Footer - Brands | `/brands` | `route('products.index')` |
| Home - Shop Now | `/shop` | `route('products.index')` |
| Home - View Collection | `/limited-editions` | `route('products.index', ['availability' => ['limited']])` |
| Home - Categories | `/shop?category=X` | `route('products.index', ['category' => X])` |
| Home - View All | `/shop?sort=newest` | `route('products.index', ['sort' => 'newest'])` |

## Benefits

### 1. Consistency
- All links use Laravel route helpers
- Easier to maintain
- No broken links

### 2. Flexibility
- Routes can be changed in `routes/web.php`
- URLs automatically update everywhere
- No need to search/replace URLs

### 3. Type Safety
- Route names are checked
- Typos caught early
- IDE autocomplete support

## Route Reference

### Main Products Route
```php
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
```

### Usage Examples
```blade
{{-- All products --}}
<a href="{{ route('products.index') }}">Shop</a>

{{-- With filters --}}
<a href="{{ route('products.index', ['sort' => 'newest']) }}">New Arrivals</a>
<a href="{{ route('products.index', ['category' => 'sneakers']) }}">Sneakers</a>
<a href="{{ route('products.index', ['availability' => ['limited']]) }}">Limited</a>

{{-- Multiple filters --}}
<a href="{{ route('products.index', ['category' => 'sneakers', 'sort' => 'newest']) }}">
    New Sneakers
</a>
```

## Testing

### Test All Fixed Links

1. **Footer Links**
   - Click "All Products" → Should go to products page
   - Click "New Arrivals" → Should show newest products
   - Click "Limited Editions" → Should show limited products
   - Click "Brands" → Should go to products page

2. **Home Page Buttons**
   - Click "Shop Now" → Should go to products page
   - Click "View Collection" → Should show limited products

3. **Category Cards**
   - Click any category → Should filter by that category

4. **New Arrivals Section**
   - Click "View All →" → Should show newest products

### Verification Checklist

- [ ] All footer links work
- [ ] Home page buttons work
- [ ] Category cards work
- [ ] New Arrivals link works
- [ ] No 404 errors
- [ ] Filters apply correctly

## Files Changed

1. `resources/views/layouts/footer.blade.php` - 4 links fixed
2. `resources/views/home/index.blade.php` - 4 links fixed

Total: **8 hardcoded URLs replaced**

## Status: ✅ Fixed

All hardcoded `/shop` URLs have been replaced with proper Laravel routes.

## Quick Test

```bash
# Clear cache
php artisan view:clear

# Test
1. Go to homepage
2. Click "Shop Now" button
3. Should go to products page ✅
4. Go to footer
5. Click "All Products"
6. Should go to products page ✅
```

## Notes

- All existing "Start Shopping" and "Continue Shopping" buttons already use correct routes
- No changes needed for cart, profile, or payment pages
- All product detail links already correct
