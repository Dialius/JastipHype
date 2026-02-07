# 🔍 LANGKAH MANUAL DIAGNOSTIC - Gambar Tidak Muncul

## ⚠️ SITUASI SAAT INI

Gambar masih belum muncul di production meskipun sudah:
- ✅ Fix configuration
- ✅ Fix CategoryController
- ✅ Create migration scripts
- ✅ Update deployment workflow

**Kemungkinan masalah:**
1. Migration script belum jalan di production
2. File masih di `storage/app/public` belum di-copy ke `public_html/uploads`
3. Database path salah
4. Permissions salah

## 🎯 LANGKAH DIAGNOSTIC MANUAL

### OPSI 1: Via SSH (RECOMMENDED)

#### Step 1: Login SSH
```bash
ssh u909490256@srv1001.hstgr.io -p 65002
```

#### Step 2: Masuk ke folder project
```bash
cd /home/u909490256/domains/jastiphype.shop
```

#### Step 3: Jalankan diagnostic script
```bash
php diagnose-production.php
```

Script ini akan cek:
- ✅ Configuration (filesystems.php)
- ✅ Folder structure (public_html/uploads)
- ✅ Database paths
- ✅ File existence
- ✅ URL generation

#### Step 4: Baca hasil dan ikuti instruksi

Script akan memberikan:
- List masalah yang ditemukan
- Recommended fixes
- Quick fix commands

---

### OPSI 2: Via Browser (ALTERNATIVE)

#### Step 1: Akses diagnostic script
```
https://jastiphype.shop/diagnose-production.php
```

#### Step 2: Baca hasil diagnostic

#### Step 3: Login SSH untuk fix
```bash
ssh u909490256@srv1001.hstgr.io -p 65002
cd /home/u909490256/domains/jastiphype.shop
```

---

## 🔧 COMMON FIXES

### Fix 1: Jalankan Migration Script
```bash
php migrate-images-to-public.php
```

Ini akan copy semua file dari `storage/app/public` ke `public_html/uploads`.

### Fix 2: Fix Permissions
```bash
chmod -R 755 public_html/uploads/
```

### Fix 3: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
```

### Fix 4: Manual Copy (Jika Migration Script Gagal)
```bash
# Copy products
cp -r storage/app/public/products/* public_html/uploads/products/

# Copy categories
cp -r storage/app/public/categories/* public_html/uploads/categories/

# Copy brands
cp -r storage/app/public/brands/* public_html/uploads/brands/

# Copy banners
cp -r storage/app/public/banners/* public_html/uploads/banners/
```

### Fix 5: Create Folders (Jika Belum Ada)
```bash
mkdir -p public_html/uploads/products
mkdir -p public_html/uploads/categories
mkdir -p public_html/uploads/brands
mkdir -p public_html/uploads/banners
```

---

## 🧪 TESTING

### Test 1: Cek File Exists
```bash
ls -la public_html/uploads/products/
ls -la public_html/uploads/categories/
ls -la public_html/uploads/brands/
ls -la public_html/uploads/banners/
```

### Test 2: Cek Database
```bash
php artisan tinker
```

Lalu jalankan:
```php
DB::table('product_images')->limit(3)->get(['id','image_path']);
DB::table('categories')->whereNotNull('image')->limit(3)->get(['name','image']);
DB::table('brands')->whereNotNull('logo_path')->limit(3)->get(['name','logo_path']);
```

### Test 3: Test URL Langsung
Buka di browser:
```
https://jastiphype.shop/uploads/products/[nama-file].jpg
```

Ganti `[nama-file].jpg` dengan nama file yang ada di database.

### Test 4: Cek Permissions
```bash
ls -ld public_html/uploads
ls -ld public_html/uploads/products
```

Harus: `drwxr-xr-x` (755)

---

## 📊 EXPECTED RESULTS

### Jika Berhasil:
```
✅ public_html/uploads/products: 7 files
✅ public_html/uploads/categories: 4 files
✅ public_html/uploads/brands: 2 files
✅ public_html/uploads/banners: 2 files
✅ All files accessible via URL
✅ Images showing on website
```

### Jika Masih Gagal:
Laporkan hasil dari:
1. Output `php diagnose-production.php`
2. Output `ls -la public_html/uploads/products/`
3. Output database query
4. Screenshot browser console (F12 → Console)

---

## 🆘 TROUBLESHOOTING SPECIFIC ISSUES

### Issue: "public_html/uploads not found"
**Fix:**
```bash
mkdir -p public_html/uploads/{products,categories,brands,banners}
chmod -R 755 public_html/uploads
```

### Issue: "Files in storage/app/public but not in public_html/uploads"
**Fix:**
```bash
php migrate-images-to-public.php
```

### Issue: "Permission denied"
**Fix:**
```bash
chmod -R 755 public_html/uploads
chown -R u909490256:u909490256 public_html/uploads
```

### Issue: "Database paths are absolute"
**Fix:** Perlu update database (kompleks, diskusikan dulu)

### Issue: "Configuration wrong"
**Fix:**
```bash
# Edit config/filesystems.php
nano config/filesystems.php

# Change:
'root' => public_path('uploads'),
'url' => env('APP_URL').'/uploads',

# Then:
php artisan config:clear
php artisan config:cache
```

---

## 📝 CHECKLIST

Setelah menjalankan fixes, cek:

- [ ] Folder `public_html/uploads` exists
- [ ] Subfolders (products, categories, brands, banners) exists
- [ ] Files ada di dalam subfolders
- [ ] Permissions 755 untuk folders
- [ ] Permissions 644 untuk files
- [ ] Database paths relatif (tanpa `/` di depan)
- [ ] URL generation correct (`/uploads/` bukan `/storage/`)
- [ ] Cache cleared
- [ ] Test URL langsung works
- [ ] Images showing on website

---

## 🎯 NEXT STEPS

1. **Jalankan diagnostic:** `php diagnose-production.php`
2. **Baca hasil** dan identifikasi masalah
3. **Jalankan fixes** sesuai rekomendasi
4. **Test** via URL langsung
5. **Refresh website** dan cek gambar
6. **Report hasil** jika masih ada masalah

---

**Dibuat:** 2026-02-08  
**Status:** READY FOR MANUAL DIAGNOSTIC  
**Estimasi:** 10-15 menit untuk diagnostic + fix
