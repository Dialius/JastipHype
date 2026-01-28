# Order History - Navbar Dropdown

## Update
Menambahkan menu "Order History" di dropdown user navbar.

## Changes Made

### 1. Header/Navbar (`resources/views/layouts/header.blade.php`)

**Added Order History Menu Item:**
```html
<a href="{{ route('profile.index', ['tab' => 'orders']) }}">
    <svg>...</svg>
    Order History
</a>
```

**Menu Order:**
1. My Account
2. **Order History** ← NEW
3. My Wishlist
4. Logout

### 2. Profile Page (`resources/views/profile/index.blade.php`)

**Already supports `tab` query parameter:**
```php
x-data="{ activeTab: '{{ request()->get('tab', 'profile') }}' }"
```

## User Flow

### From Navbar
```
Click user icon/name
    ↓
Dropdown opens
    ↓
Click "Order History"
    ↓
Redirect to /profile?tab=orders
    ↓
Profile page opens with Orders tab active
```

## Visual

### Dropdown Menu
```
┌─────────────────────────┐
│ Dialius                 │
│ davinza30@gmail.com     │
├─────────────────────────┤
│ 👤 My Account           │
│ 📦 Order History        │ ← NEW
│ ❤️  My Wishlist         │
├─────────────────────────┤
│ 🚪 Logout               │
└─────────────────────────┘
```

## Icon Used

Shopping bag icon:
```html
<svg>
    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
</svg>
```

## URL Structure

- **My Account**: `/profile` (default tab)
- **Order History**: `/profile?tab=orders`
- **My Wishlist**: `/wishlist`

## Testing

### Test Case 1: Click Order History
1. Login
2. Click user icon
3. Click "Order History"
4. Should redirect to profile with orders tab active

### Test Case 2: Direct URL
1. Go to `/profile?tab=orders`
2. Should open profile with orders tab active

### Test Case 3: Menu Order
1. Open dropdown
2. Verify menu order:
   - My Account
   - Order History
   - My Wishlist
   - Logout

## Styling

- Same style as other menu items
- Gray text with hover effect
- Icon on left, text on right
- Consistent spacing

## Status: ✅ Implemented

Order History menu item added to navbar dropdown!

## Quick Test

```bash
# Clear cache
php artisan view:clear

# Test
1. Login
2. Click user icon in navbar
3. Should see "Order History" menu
4. Click it
5. Should go to profile orders tab ✅
```
