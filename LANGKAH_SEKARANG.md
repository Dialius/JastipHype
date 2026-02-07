# 🚀 LANGKAH-LANGKAH YANG HARUS DILAKUKAN SEKARANG

## ⚡ QUICK START - Ikuti Langkah Ini:

### STEP 1: Buka Terminal/CMD/PowerShell

Di Windows, tekan `Win + R`, ketik `cmd`, Enter.

### STEP 2: Login ke SSH Hostinger

Copy-paste command ini:

```bash
ssh u909490256@srv1001.hstgr.io -p 65002
```

Masukkan password SSH Anda.

### STEP 3: Masuk ke Folder Project

```bash
cd /home/u909490256/domains/jastiphype.shop
```

### STEP 4: Jalankan Diagnostic Script

```bash
php diagnose-production.php
```

**Script ini akan:**
- ✅ Cek konfigurasi
- ✅ Cek folder structure
- ✅ Cek database paths
- ✅ Cek file existence
- ✅ Memberikan rekomendasi fix

### STEP 5: Baca Hasil Diagnostic

Script akan menampilkan:
- ❌ Masalah yang ditemukan
- ✅ Recommended fixes
- 📝 Quick fix commands

### STEP 6: Jalankan Fix yang Direkomendasikan

Kemungkinan besar Anda perlu jalankan:

```bash
# Fix 1: Jalankan migration script
php migrate-images-to-public.php

# Fix 2: Fix permissions
chmod -R 755 public_html/uploads/

# Fix 3: Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### STEP 7: Verify Files Ada

```bash
ls -la public_html/uploads/products/
ls -la public_html/uploads/categories/
ls -la public_html/uploads/brands/
ls -la public_html/uploads/banners/
```

Anda harus lihat file-file gambar di sana.

### STEP 8: Test URL Langsung

Buka browser dan akses:

```
https://jastiphype.shop/uploads/products/[nama-file].jpg
```

Ganti `[nama-file].jpg` dengan nama file yang Anda lihat di step 7.

**Contoh:**
Jika Anda lihat file `2sJdoVjrR9tTg9sifbU4ZLfq11gl1GSy7eH9f2O3.jpg`, maka test:
```
https://jastiphype.shop/uploads/products/2sJdoVjrR9tTg9sifbU4ZLfq11gl1GSy7eH9f2O3.jpg
```

### STEP 9: Refresh Website

Buka:
```
https://jastiphype.shop
```

Tekan `Ctrl + F5` untuk hard refresh (clear cache browser).

---

## 🔍 JIKA MASIH BELUM BERHASIL

### Cek 1: Apakah File Ada di Folder?

```bash
cd /home/u909490256/domains/jastiphype.shop
ls -la public_html/uploads/products/
```

**Jika KOSONG atau TIDAK ADA:**
```bash
# Jalankan migration manual
php migrate-images-to-public.php
```

**Jika MASIH KOSONG:**
```bash
# Copy manual dari storage
cp -r storage/app/public/products/* public_html/uploads/products/
cp -r storage/app/public/categories/* public_html/uploads/categories/
cp -r storage/app/public/brands/* public_html/uploads/brands/
cp -r storage/app/public/banners/* public_html/uploads/banners/
```

### Cek 2: Apakah Permissions Benar?

```bash
ls -ld public_html/uploads
```

Harus: `drwxr-xr-x` (755)

**Jika SALAH:**
```bash
chmod -R 755 public_html/uploads/
```

### Cek 3: Apakah Database Path Benar?

```bash
php artisan tinker
```

Lalu ketik:
```php
DB::table('product_images')->limit(3)->get(['id','image_path']);
```

Tekan Enter.

**Path harus relatif:**
- ✅ BENAR: `products/image.jpg`
- ❌ SALAH: `/storage/products/image.jpg`
- ❌ SALAH: `https://...`

Ketik `exit` untuk keluar dari tinker.

### Cek 4: Apakah URL Generation Benar?

```bash
php artisan tinker
```

Lalu ketik:
```php
\App\Helpers\ImageHelper::getImageUrl('products/test.jpg');
```

Harus return:
```
https://jastiphype.shop/uploads/products/test.jpg
```

**Jika SALAH (masih `/storage/`):**
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

## 📊 EXPECTED OUTPUT

### Diagnostic Script Output (Normal):
```
╔══════════════════════════════════════════════════════════════╗
║          PRODUCTION IMAGE DIAGNOSTIC - JastipHype            ║
╚══════════════════════════════════════════════════════════════╝

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
CHECK 1: CONFIGURATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Default Disk: public
Public Root: /home/u909490256/domains/jastiphype.shop/public/uploads
Public URL: https://jastiphype.shop/uploads
APP_URL: https://jastiphype.shop

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
CHECK 2: FOLDER STRUCTURE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Checking: public_html/uploads
✅ EXISTS
   - products: 7 files
   - categories: 4 files
   - brands: 2 files
   - banners: 2 files

...

╔══════════════════════════════════════════════════════════════╗
║                          SUMMARY                             ║
╚══════════════════════════════════════════════════════════════╝

🎉 NO ISSUES FOUND!
```

### File Listing Output (Normal):
```bash
$ ls -la public_html/uploads/products/

total 1234
drwxr-xr-x 2 u909490256 u909490256  4096 Feb  8 10:00 .
drwxr-xr-x 6 u909490256 u909490256  4096 Feb  8 10:00 ..
-rw-r--r-- 1 u909490256 u909490256 45678 Feb  8 10:00 2sJdoVjrR9tTg9sifbU4ZLfq11gl1GSy7eH9f2O3.jpg
-rw-r--r-- 1 u909490256 u909490256 34567 Feb  8 10:00 OhjvPm0DKLgtV8LJZOSei1nB0bPt5ivFKO0sNpjn.webp
...
```

---

## 🆘 JIKA STUCK

### Kirim Output Ini:

1. **Output diagnostic:**
   ```bash
   php diagnose-production.php > diagnostic-result.txt
   cat diagnostic-result.txt
   ```

2. **Output file listing:**
   ```bash
   ls -la public_html/uploads/products/ > files-list.txt
   cat files-list.txt
   ```

3. **Output database:**
   ```bash
   php artisan tinker --execute="DB::table('product_images')->limit(3)->get(['id','image_path'])"
   ```

4. **Screenshot browser console:**
   - Buka https://jastiphype.shop
   - Tekan F12
   - Tab "Console"
   - Screenshot error yang muncul

---

## ✅ CHECKLIST FINAL

Setelah semua langkah:

- [ ] SSH login berhasil
- [ ] Diagnostic script jalan
- [ ] Migration script jalan
- [ ] Files ada di `public_html/uploads/products/`
- [ ] Permissions 755
- [ ] Database paths relatif
- [ ] URL generation benar (`/uploads/`)
- [ ] Cache cleared
- [ ] Test URL langsung works (gambar muncul)
- [ ] Website refresh (Ctrl+F5)
- [ ] Gambar muncul di website ✅

---

## 🎯 RINGKASAN SINGKAT

```bash
# 1. Login SSH
ssh u909490256@srv1001.hstgr.io -p 65002

# 2. Masuk folder
cd /home/u909490256/domains/jastiphype.shop

# 3. Diagnostic
php diagnose-production.php

# 4. Fix (kemungkinan besar ini yang perlu)
php migrate-images-to-public.php
chmod -R 755 public_html/uploads/
php artisan config:clear
php artisan cache:clear

# 5. Verify
ls -la public_html/uploads/products/

# 6. Test URL
# Buka: https://jastiphype.shop/uploads/products/[nama-file].jpg

# 7. Refresh website
# Buka: https://jastiphype.shop (Ctrl+F5)
```

---

**MULAI DARI STEP 1 SEKARANG!** 🚀

Jika ada error atau stuck di langkah manapun, screenshot dan tanya saya.
