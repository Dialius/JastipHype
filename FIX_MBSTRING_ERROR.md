# 🔧 FIX: Call to undefined function mb_split()

## 🎯 MASALAH
Error: `Call to undefined function Illuminate\Support\mb_split()`

**Penyebab**: PHP mbstring extension tidak aktif di server Hostinger.

---

## ✅ SOLUSI 1: Aktifkan mbstring via hPanel (RECOMMENDED)

### Langkah-langkah:

1. **Login ke hPanel** Hostinger
2. **Advanced** → **PHP Configuration** (atau **Select PHP Version**)
3. Pilih website: **jastiphype.shop**
4. Cari section **PHP Extensions**
5. **Centang/Enable** extension berikut:
   - ✅ **mbstring**
   - ✅ **pdo_mysql** (pastikan juga aktif)
   - ✅ **openssl**
   - ✅ **tokenizer**
   - ✅ **xml**
   - ✅ **ctype**
   - ✅ **json**
   - ✅ **bcmath**
   - ✅ **fileinfo**
   - ✅ **zip**

6. **Save/Apply Changes**
7. **Tunggu 1-2 menit** untuk perubahan diterapkan
8. **Refresh website**: https://jastiphype.shop

---

## ✅ SOLUSI 2: Via .htaccess (Jika Opsi 1 Tidak Ada)

Tambahkan ini di file `.htaccess` di root directory:

```apache
<IfModule mod_php.c>
    php_value extension mbstring.so
</IfModule>
```

### Cara tambahkan via SSH:

```bash
cd /home/u909490256/domains/jastiphype.shop

# Backup .htaccess dulu
cp .htaccess .htaccess.backup

# Tambahkan mbstring extension
cat >> .htaccess << 'EOF'

# Enable mbstring extension
<IfModule mod_php.c>
    php_value extension mbstring.so
</IfModule>
EOF

echo "✓ .htaccess updated"
```

---

## ✅ SOLUSI 3: Via php.ini (Alternatif)

Jika Hostinger mengizinkan custom php.ini:

```bash
cd /home/u909490256/domains/jastiphype.shop

# Buat php.ini
cat > php.ini << 'EOF'
extension=mbstring.so
extension=pdo_mysql.so
extension=openssl.so
extension=tokenizer.so
extension=xml.so
extension=ctype.so
extension=json.so
extension=bcmath.so
extension=fileinfo.so
extension=zip.so
EOF

echo "✓ php.ini created"
```

---

## 🔍 VERIFIKASI

Setelah aktifkan mbstring, cek apakah sudah aktif:

```bash
# Via SSH
php -m | grep mbstring

# Atau buat file info.php di public_html
echo "<?php phpinfo(); ?>" > public_html/info.php
```

Lalu buka: https://jastiphype.shop/info.php

Cari "mbstring" - harus ada dan status "enabled".

**PENTING**: Hapus file info.php setelah cek:
```bash
rm public_html/info.php
```

---

## 🚀 SETELAH MBSTRING AKTIF

Jalankan ini untuk clear cache:

```bash
cd /home/u909490256/domains/jastiphype.shop

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache

# Test
php artisan --version
```

Lalu coba akses: https://jastiphype.shop

---

## 📋 EXTENSION YANG DIBUTUHKAN LARAVEL

Pastikan semua ini aktif di PHP Configuration:

| Extension | Status | Keterangan |
|-----------|--------|------------|
| mbstring | ✅ WAJIB | String manipulation |
| pdo_mysql | ✅ WAJIB | Database connection |
| openssl | ✅ WAJIB | Encryption |
| tokenizer | ✅ WAJIB | Parsing PHP code |
| xml | ✅ WAJIB | XML processing |
| ctype | ✅ WAJIB | Character type checking |
| json | ✅ WAJIB | JSON handling |
| bcmath | ✅ WAJIB | Precision math |
| fileinfo | ✅ WAJIB | File type detection |
| zip | ⚠️ Optional | Zip archives |
| gd | ⚠️ Optional | Image manipulation |
| curl | ⚠️ Optional | HTTP requests |

---

## 🎯 LANGKAH CEPAT

1. **hPanel** → **Advanced** → **PHP Configuration**
2. **Enable mbstring extension**
3. **Save**
4. **Tunggu 1-2 menit**
5. **Refresh** https://jastiphype.shop

Seharusnya website langsung jalan! 🚀

---

## 🆘 JIKA MASIH ERROR

Kirim output dari:

```bash
# Cek PHP version
php -v

# Cek loaded extensions
php -m

# Cek Laravel requirements
php artisan about
```

