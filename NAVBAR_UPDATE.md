# Navbar Update - SALE, NEW, REQUEST

## Perubahan yang Dilakukan

### 1. Menu Navbar Baru
Mengganti menu navbar dari:
- ~~SHOP~~ → **SALE** (merah, animasi shake)
- ~~NEW ARRIVALS~~ → **NEW**
- ~~LIMITED~~ → **REQUEST**
- BRANDS (tetap)

### 2. Styling SALE
- **Warna**: Merah (`text-red-600`)
- **Hover**: Merah lebih gelap (`hover:text-red-700`)
- **Animasi**: Shake kanan-kiri (goyang horizontal)
  - Durasi: 0.5s
  - Loop: infinite
  - Gerakan: ±3px horizontal

### 3. File yang Diubah

#### `resources/views/layouts/header.blade.php`
- Desktop navigation (line ~118-122)
- Mobile navigation (line ~512-517)
- Menambahkan class `animate-shake` dan `text-red-600` pada link SALE

#### `resources/css/app.css`
- Menambahkan keyframe animation `shake`
- Menambahkan utility class `.animate-shake`

#### `app/Http/Controllers/ProductController.php`
- Update filter untuk handle parameter `on_sale` (selain `discount`)
- Filter menampilkan produk dengan `sale_price` yang lebih rendah dari `price`

### 4. Route & Functionality

**SALE**: 
```
/products?on_sale=true
```
Menampilkan produk yang memiliki sale_price dan sedang diskon.

**NEW**: 
```
/products?sort=newest
```
Menampilkan produk terbaru (sorted by created_at DESC).

**REQUEST**: 
```
# (belum ada route, sementara link ke #)
```
Untuk fitur customer request jastip (perlu dibuat).

**BRANDS**: 
```
/products
```
Halaman semua produk (bisa difilter by brand).

### 5. CSS Animation Details

```css
@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-3px);
    }
    75% {
        transform: translateX(3px);
    }
}

.animate-shake {
    animation: shake 0.5s ease-in-out infinite;
}
```

## Testing

1. Buka website
2. Lihat navbar - link SALE harus berwarna merah dan bergoyang
3. Klik SALE - harus menampilkan produk yang sedang diskon
4. Klik NEW - harus menampilkan produk terbaru
5. REQUEST - sementara belum ada halaman (link ke #)

## Next Steps (Opsional)

1. **Buat halaman REQUEST**
   - Form untuk customer request produk
   - Input: nama produk, link, budget, dll
   - Simpan ke database atau kirim email

2. **Improve SALE filter**
   - Tambahkan badge "SALE" di product card
   - Tampilkan persentase diskon
   - Sort by discount percentage

3. **Analytics**
   - Track clicks pada menu SALE
   - Monitor conversion rate
