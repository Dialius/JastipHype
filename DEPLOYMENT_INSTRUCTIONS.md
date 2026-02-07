# 🚀 Instruksi Deployment - Fix Gambar Production

## ✅ Perubahan yang Sudah Dilakukan

### 1. Konfigurasi Laravel
- ✅ `config/filesystems.php` - Ubah disk 'public' dari `storage/app/public` ke `public/uploads`
- ✅ `app/Helpers/ImageHelper.php` - Ubah URL dari `/storage/` ke `/uploads/`

### 2. Script Migrasi
- ✅ `migrate-images-to-public.php` - Script untuk copy file dari storage ke public
- ✅ `verify-images.php` - Script untuk verifikasi file sudah accessible
- ✅ `.github/workflows/deploy.yml` - Tambah step migrasi otomatis

### 3. Dokumentasi
- ✅ `IMAGE_STORAGE_FIX.md` - Dokumentasi lengkap tentang masalah dan solusi
- ✅ `DEPLOYMENT_INSTRUCTIONS.md` - File ini

## 📋 Langkah Deployment

### Step 1: Commit dan Push (SEKARANG)

```bash
git add .
git commit -m "Fix: Migrate image storage from storage/app/public to public/uploads for Hostinger compatibility

- Change filesystems.php public disk root to public/uploads
- Update ImageHelper to use /uploads/ instead of /storage/
- Add migration script to copy files from storage to public
- Update deployment workflow to run migration automatically
- Add verification script to check image accessibility

This fixes the issue where images don't show in production because
Hostinger shared hosting doesn't support symlinks."

git push origin main
```

### Step 2: Tunggu GitHub Actions Deploy (OTOMATIS)

GitHub Actions akan otomatis:
1. Pull code terbaru
2. Install dependencies
3. Copy files ke public_html
4. **Jalankan migrate-images-to-public.php** ← BARU!
5. Clear dan rebuild cache
6. Selesai

### Step 3: Verifikasi di Production

Setelah deployment selesai (cek di GitHub Actions), buka website:

```
https://jastiphype.shop
```

Cek apakah gambar muncul di:
- ✅ Homepage banners
- ✅ Product listing
- ✅ Product detail
- ✅ Category pages
- ✅ Brand pages

### Step 4: Test Upload Baru

1. Login ke admin panel
2. Upload product baru dengan gambar
3. Cek apakah gambar langsung muncul
4. Cek di server apakah file tersimpan di `public_html/uploads/`

## 🔍 Troubleshooting

### Jika Gambar Masih Belum Muncul

#### 1. Cek File di Server (SSH)

```bash
ssh u909490256@srv1001.hstgr.io -p 65002
cd /home/u909490256/domains/jastiphype.shop/public_html
ls -la uploads/
ls -la uploads/products/
ls -la uploads/categories/
ls -la uploads/brands/
ls -la uploads/banners/
```

#### 2. Cek Permissions

```bash
chmod -R 755 uploads/
```

#### 3. Jalankan Script Manual (Jika Perlu)

```bash
cd /home/u909490256/domains/jastiphype.shop
php migrate-images-to-public.php
```

#### 4. Cek URL Langsung

Buka di browser:
```
https://jastiphype.shop/uploads/products/[nama-file].jpg
```

Jika file muncul = ✅ File accessible  
Jika 404 = ❌ File tidak ada atau permission salah

#### 5. Clear Cache

```bash
cd /home/u909490256/domains/jastiphype.shop
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan config:cache
```

## 📊 Perbandingan Sebelum vs Sesudah

### SEBELUM (TIDAK BEKERJA)
```
File Location: storage/app/public/products/image.jpg
Public Access: public/storage/products/image.jpg (via symlink)
URL: https://jastiphype.shop/storage/products/image.jpg
Status: ❌ 404 Not Found (symlink tidak bekerja di Hostinger)
```

### SESUDAH (BEKERJA)
```
File Location: public/uploads/products/image.jpg
Public Access: public/uploads/products/image.jpg (direct)
URL: https://jastiphype.shop/uploads/products/image.jpg
Status: ✅ 200 OK (file langsung accessible)
```

## 🎯 Kenapa Solusi Ini Bekerja?

1. **Tidak Perlu Symlink**
   - File langsung di folder public
   - Tidak bergantung pada symlink yang disabled di Hostinger

2. **Direct Access**
   - Web server langsung serve file
   - Tidak perlu melalui PHP/Laravel

3. **Lebih Cepat**
   - Tidak ada overhead dari PHP
   - Web server (Apache/Nginx) langsung serve static files

4. **Universal**
   - Bekerja di semua hosting (shared, VPS, cloud)
   - Tidak ada dependency khusus

## ⚠️ Catatan Penting

### Database Tidak Perlu Diubah
Path di database tetap relatif:
```
products/image.jpg
categories/image.png
brands/logo.jpg
```

Helper function otomatis menambahkan prefix `uploads/`:
```php
asset('uploads/' . $path)
// Result: https://jastiphype.shop/uploads/products/image.jpg
```

### Upload Baru Otomatis Bekerja
Semua controller yang menggunakan:
```php
Storage::disk('public')->put($path, $file);
```

Akan otomatis menyimpan ke `public/uploads/` karena konfigurasi disk sudah diubah.

### Folder storage/app/public Bisa Dihapus (Opsional)
Setelah verifikasi semua gambar muncul, folder `storage/app/public` bisa dihapus untuk menghemat space.

**TAPI JANGAN DULU!** Tunggu sampai 100% yakin semua bekerja.

## 📞 Kontak

Jika ada masalah setelah deployment:
1. Cek GitHub Actions logs
2. Cek error logs di Hostinger
3. Jalankan troubleshooting steps di atas

---

**Dibuat:** 2026-02-08  
**Status:** Ready to Deploy  
**Estimasi Waktu:** 5-10 menit (termasuk deployment)
