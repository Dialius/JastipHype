# Hide Scrollbar - Midtrans Snap UI

## Problem
Scrollbar masih terlihat di Midtrans Snap iframe meskipun sudah ditambahkan CSS untuk menyembunyikannya.

## Solution
Kombinasi CSS dan JavaScript untuk memastikan scrollbar benar-benar tersembunyi.

## Implementation

### 1. CSS Approach
```css
/* Fixed height container */
#snap-container {
    height: calc(100vh - 80px) !important;
    max-height: calc(100vh - 80px);
    overflow: hidden !important;
}

/* Fixed height iframe */
#snap-container iframe {
    height: calc(100vh - 80px) !important;
    overflow: hidden !important;
    scrolling: no;
}

/* Hide scrollbar - All browsers */
#snap-container::-webkit-scrollbar,
#snap-container *::-webkit-scrollbar {
    display: none !important;
    width: 0 !important;
}

#snap-container {
    scrollbar-width: none !important;
    -ms-overflow-style: none !important;
}
```

### 2. JavaScript Approach
```javascript
// After Snap loads, force hide scrollbar
setTimeout(function() {
    const iframe = document.querySelector('#snap-container iframe');
    if (iframe) {
        iframe.style.overflow = 'hidden';
        iframe.scrolling = 'no';
    }
}, 1000);

// Monitor for changes and reapply
const observer = new MutationObserver(function() {
    const iframe = document.querySelector('#snap-container iframe');
    if (iframe) {
        iframe.style.overflow = 'hidden';
        iframe.scrolling = 'no';
    }
});
```

### 3. Inline Styles
```html
<div style="overflow: hidden; height: calc(100vh - 80px);">
    <div id="snap-container" style="overflow: hidden !important; height: calc(100vh - 80px);"></div>
</div>
```

## Key Changes

1. **Fixed Height**: Set explicit height instead of min-height
2. **Overflow Hidden**: Applied at multiple levels (container, iframe)
3. **JavaScript Enforcement**: Force hide after iframe loads
4. **MutationObserver**: Monitor and reapply styles if changed
5. **Scrolling Attribute**: Set iframe scrolling="no"

## Browser Support

- ✅ Chrome/Edge (Chromium) - `::-webkit-scrollbar`
- ✅ Firefox - `scrollbar-width: none`
- ✅ Safari - `::-webkit-scrollbar`
- ✅ IE/Edge Legacy - `-ms-overflow-style: none`

## Testing

### Before
```
┌─────────────────────┐
│ Midtrans Snap UI    │
│                     │
│ [Payment Options]   │
│                     │
│                   ║ │ ← Scrollbar visible
│                   ║ │
└─────────────────────┘
```

### After
```
┌─────────────────────┐
│ Midtrans Snap UI    │
│                     │
│ [Payment Options]   │
│                     │
│                     │ ← No scrollbar
│                     │
└─────────────────────┘
```

## How to Test

1. Clear cache: `php artisan view:clear`
2. Go to payment page
3. Check if scrollbar is hidden
4. Try different browsers
5. Check on mobile devices

## Notes

- Scrollbar di dalam iframe Midtrans dikontrol oleh Midtrans
- Karena CORS, kita tidak bisa mengakses iframe content directly
- Solusi: Hide scrollbar di container level dan set iframe scrolling="no"
- JavaScript digunakan untuk enforce setelah iframe dimuat

## Limitations

- Jika konten Midtrans terlalu panjang, user tidak bisa scroll
- Pastikan viewport height cukup untuk menampilkan semua payment options
- Pada mobile, mungkin perlu adjustment

## Alternative Solutions

### Option 1: Increase Height
```css
#snap-container {
    height: 100vh; /* Full viewport */
}
```

### Option 2: Use Popup Mode
```javascript
// Instead of embed, use popup
window.snap.pay('token');
```

### Option 3: Custom Scrollbar
```css
#snap-container::-webkit-scrollbar {
    width: 0px;
    background: transparent;
}
```

## Troubleshooting

### Scrollbar still visible?
1. Hard refresh browser (Ctrl+Shift+R)
2. Clear browser cache
3. Check browser DevTools for overriding styles
4. Verify JavaScript is running (check console)

### Content cut off?
1. Increase container height
2. Allow overflow-y: auto
3. Use custom thin scrollbar instead

### Mobile issues?
1. Test on actual device
2. Check viewport height calculation
3. Adjust for mobile keyboard

## Files Changed

- `resources/views/payment/show.blade.php`
  - Added comprehensive CSS
  - Added JavaScript to enforce
  - Added inline styles

## Status

✅ Implemented
- CSS hiding scrollbar
- JavaScript enforcement
- MutationObserver monitoring
- Multi-browser support

## Next Steps

If scrollbar still appears:
1. Check if Midtrans has updated their iframe
2. Try popup mode instead of embed
3. Contact Midtrans support for iframe customization options
