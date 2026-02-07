# 🔍 Debug Masalah Gambar

## ✅ Yang Sudah Benar

1. **Helper Functions** - Semua sudah loaded dan berfungsi:
   - ✅ `image_url()` 
   - ✅ `product_image_url()`
   - ✅ `brand_logo_url()`
   - ✅ `banner_image_url()`
   - ✅ `category_image_url()`

2. **Storage Directory** - Sudah ada dan berisi gambar:
   - ✅ `storage/app/public/products/`
   - ✅ `storage/app/public/brands/`
   - ✅ `storage/app/public/banners/`
   - ✅ `storage/app/public/categories/`
   - ✅ `storage/app/public/reviews/`

3. **Helper Output** - URL yang dihasilkan sudah benar:
   - Input: `products/test.jpg`
   - Output: `https://jastiphype.shop/storage/products/test.jpg`

4. **Placeholder** - Fallback sudah benar:
   - Output: `https://jastiphype.shop/images/placeholder-product.svg`

## ⚠️ Masalah yang Ditemukan

### 1. Storage Link di Windows
**Status:** `public/storage` ada sebagai directory biasa, BUKAN symlink

**Solusi yang Diterapkan:**
```bash
# Copy semua file dari storage/app/public ke public/storage
robocopy "storage\app\public" "public\storage" /MIR
```

**Catatan:** Di Windows development, ini OK. Tapi untuk production (Linux), harus pakai symlink.

### 2. Database Connection
**Status:** MySQL tidak running saat test

**Solusi:** Start MySQL service di Laragon

## 🧪 Test Results

```
Testing Image Helpers...

1. Checking if helpers are loaded:
   ✅ image_url() exists
   ✅ product_image_url() exists
   ✅ brand_logo_url() exists
   ✅ banner_image_url() exists
   ✅ category_image_url() exists

2. Testing image_url() with sample path:
   Input: products/test.jpg
   Output: https://jastiphype.shop/storage/products/test.jpg

3. Testing with null path (should return placeholder):
   Output: https://jastiphype.shop/images/placeholder-product.svg

4. Checking storage configuration:
   Default disk: local
   Public disk root: D:\APPS\laragon\www\jastiphype\storage\app/public
   Public disk URL: https://jastiphype.shop/storage

5. Checking if storage link exists:
   ❌ Storage link does NOT exist (but directory exists)

6. Checking actual storage directories:
   ✅ storage/app/public exists
   Subdirectories: banners, brands, categories, products, reviews
```

## 📋 Checklist untuk User

Silakan cek hal-hal berikut:

### 1. Cek Browser Console
```
1. Buka halaman yang gambarnya tidak muncul
2. Tekan F12 (Developer Tools)
3. Tab "Console" - ada error?
4. Tab "Network" - filter "Img" - status code berapa?
   - 200 = OK
   - 404 = File tidak ditemukan
   - 403 = Permission denied
```

### 2. Cek URL Gambar
```
1. Klik kanan pada gambar yang tidak muncul
2. "Inspect Element" atau "Inspect"
3. Lihat atribut src=""
4. Copy URL tersebut
5. Paste di browser baru
6. Apa yang muncul?
```

### 3. Cek File Exists
```bash
# Cek apakah file benar-benar ada
dir public\storage\products
dir public\storage\brands
dir public\storage\banners
```

### 4. Cek Permission (jika di Linux/Production)
```bash
# Pastikan web server bisa akses
chmod -R 755 storage/
chmod -R 755 public/storage/
```

## 🔧 Solusi Berdasarkan Error

### Error 404 (File Not Found)
**Penyebab:** File gambar tidak ada di `public/storage/`

**Solusi:**
```bash
# Windows
robocopy "storage\app\public" "public\storage" /MIR

# Linux/Mac
php artisan storage:link
```

### Error 403 (Forbidden)
**Penyebab:** Permission issue

**Solusi:**
```bash
# Linux/Mac
chmod -R 755 storage/
chmod -R 755 public/storage/
chown -R www-data:www-data storage/
chown -R www-data:www-data public/storage/
```

### Gambar Tidak Muncul Tapi Tidak Ada Error
**Penyebab:** Path di database salah

**Solusi:** Cek database
```sql
-- Cek path gambar di database
SELECT id, name, logo_path FROM brands LIMIT 5;
SELECT id, image_path FROM product_images LIMIT 5;

-- Path harus seperti ini:
-- ✅ brands/logo-123.jpg
-- ❌ /storage/brands/logo-123.jpg
-- ❌ storage/brands/logo-123.jpg
-- ❌ public/storage/brands/logo-123.jpg
```

## 🚀 Next Steps

1. **Start MySQL** di Laragon
2. **Buka browser** dan test halaman:
   - Homepage: `http://jastiphype.test/`
   - Products: `http://jastiphype.test/products`
   - Admin Products: `http://jastiphype.test/admin/products`
   - Admin Brands: `http://jastiphype.test/admin/brands`

3. **Cek Console** (F12) untuk error

4. **Screenshot** error jika masih ada masalah

5. **Kirim info:**
   - URL halaman yang bermasalah
   - Screenshot console error
   - Screenshot network tab (filter: Img)
   - Contoh URL gambar yang tidak muncul
