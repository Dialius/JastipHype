# 🔧 LANGKAH PERBAIKAN SISTEM GAMBAR

## 🎯 MASALAH
Gambar yang diupload tidak muncul karena:
- File masuk ke `storage/app/private/` (tidak bisa diakses public)
- Seharusnya masuk ke `public/uploads/` (bisa diakses public)

## ✅ SOLUSI SUDAH DIBUAT

Saya sudah membuat:
1. ✅ Fix konfigurasi `.env.hostinger` → `FILESYSTEM_DISK=public`
2. ✅ Script `migrate-private-to-public.php` → copy file dari private ke public
3. ✅ Script `deploy-fix-images.sh` → deployment otomatis
4. ✅ Script `fix-database-paths.php` → fix path di database
5. ✅ Update GitHub Actions → deployment otomatis

## 📋 YANG HARUS ANDA LAKUKAN

### OPSI 1: Otomatis via SSH (RECOMMENDED)

```bash
# 1. Login SSH
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002

# 2. Masuk folder project
cd /home/u909490256/domains/jastiphype.shop

# 3. Pull code terbaru
git pull origin master

# 4. Jalankan script (SATU PERINTAH INI SAJA!)
bash deploy-fix-images.sh
```

Script ini akan otomatis:
- ✅ Backup .env lama
- ✅ Copy .env.hostinger ke .env
- ✅ Clear cache
- ✅ Buat folder uploads
- ✅ Copy semua file dari storage/app/private ke public/uploads
- ✅ Set permissions
- ✅ Verifikasi hasil

### OPSI 2: Manual (jika script gagal)

```bash
# Login SSH
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002
cd /home/u909490256/domains/jastiphype.shop

# Pull code
git pull origin master

# Update .env
cp .env.hostinger .env

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Buat folder
mkdir -p public/uploads/{products,brands,categories,banners}
mkdir -p public_html/uploads/{products,brands,categories,banners}

# Copy file
php migrate-private-to-public.php

# Sync ke public_html
rsync -av --delete public/uploads/ public_html/uploads/

# Fix database
php fix-database-paths.php
```

## 🧪 TESTING

Setelah menjalankan script:

1. **Cek file sudah ada**:
   ```bash
   ls -lh public_html/uploads/categories/
   ls -lh public_html/uploads/banners/
   ```

2. **Test upload baru**:
   - Login ke admin panel
   - Upload gambar category baru
   - Cek apakah muncul di website

3. **Cek file masuk ke folder yang benar**:
   ```bash
   # File HARUS masuk ke sini (bukan storage/app/private/)
   ls -lh public/uploads/categories/
   ```

## ✅ HASIL YANG DIHARAPKAN

Setelah selesai:
- ✅ Gambar lama (categories, banners) sudah muncul
- ✅ Upload baru masuk ke `public/uploads/`
- ✅ Gambar bisa diakses: `https://jastiphype.shop/uploads/categories/[file]`
- ✅ Tidak ada lagi file masuk ke `storage/app/private/`

## 🚨 JIKA MASIH BERMASALAH

### Gambar masih belum muncul?

```bash
# 1. Cek .env
grep FILESYSTEM_DISK .env
# Harus output: FILESYSTEM_DISK=public

# 2. Cek file ada
ls -lh public_html/uploads/categories/

# 3. Clear cache lagi
php artisan config:clear
php artisan config:cache

# 4. Cek permissions
chmod -R 755 public/uploads
chmod -R 755 public_html/uploads
```

### Upload baru masih masuk ke storage/app/private/?

```bash
# .env belum terupdate, ulangi:
cp .env.hostinger .env
php artisan config:clear
php artisan config:cache
```

## 📞 BANTUAN

Jika ada error saat menjalankan script, kirim output error-nya ke saya.

Contoh:
```bash
bash deploy-fix-images.sh 2>&1 | tee deploy-log.txt
cat deploy-log.txt
```

---

**PENTING**: Jangan hapus file di `storage/app/private/` sampai yakin sistem sudah normal!
