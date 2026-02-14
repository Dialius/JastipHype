# ⚠️ CRITICAL SETUP INFORMATION - JANGAN HAPUS!

## 🚨 PENTING: Struktur Folder Hostinger

### ❌ KESALAHAN UMUM
Banyak developer mengira domain di Hostinger point ke `/home/u909490256/public_html` (folder global).

### ✅ FAKTA SEBENARNYA
Domain `jastiphype.shop` di-point ke:
```
/home/u909490256/domains/jastiphype.shop/public_html
```

Ini adalah folder `public_html` di DALAM project folder, bukan folder global!

---

## 📁 Struktur Folder yang HARUS Dipertahankan

```
/home/u909490256/domains/jastiphype.shop/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/                       ← Laravel public folder (ORIGINAL)
│   ├── build/                    ← Vite compiled assets
│   ├── images/
│   ├── index.php                 ← Original Laravel index.php
│   └── .htaccess                 ← Original Laravel .htaccess
│
├── public_html/                  ← Domain document root (HOSTINGER)
│   ├── index.php                 ← Modified dengan path relatif (../)
│   ├── .htaccess                 ← Copy dari public/.htaccess
│   ├── images/                   ← Copy dari public/images/
│   ├── build -> ../public/build  ← SYMLINK (bukan copy!)
│   └── storage -> ../storage/app/public  ← SYMLINK (bukan copy!)
│
├── resources/
├── routes/
├── storage/
└── vendor/
```

---

## 🔑 Key Points (JANGAN DILANGGAR!)

### 1. Path Relatif di index.php
File `public_html/index.php` HARUS menggunakan path relatif:

```php
// ✅ BENAR
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// ❌ SALAH
require '/home/u909490256/domains/jastiphype.shop/vendor/autoload.php';
```

### 2. Symbolic Links (Bukan Copy!)
Folder `build/` dan `storage/` HARUS di-symlink:

```bash
# ✅ BENAR
cd public_html
ln -s ../public/build build
ln -s ../storage/app/public storage

# ❌ SALAH
cp -r public/build public_html/build
```

**Alasan:**
- Hemat disk space
- Auto-update saat build baru
- Tidak perlu sync manual

### 3. Deployment HARUS Sync
Setiap deployment HARUS:
1. Sync `public/` ke `public_html/` (exclude build & storage)
2. Update `index.php` dengan path relatif
3. Recreate symbolic links

```bash
# Sync files
rsync -av --delete --exclude='build' --exclude='storage' public/ public_html/

# Update index.php
cat > public_html/index.php << 'EOF'
<?php
require __DIR__.'/../vendor/autoload.php';
// ... (path relatif)
EOF

# Recreate symlinks
cd public_html
rm -rf build storage
ln -s ../public/build build
ln -s ../storage/app/public storage
```

---

## 🚨 Apa yang Terjadi Jika Dilanggar?

### Jika index.php menggunakan absolute path:
```
❌ Website return 403 Forbidden
❌ "This Page Does Not Exist" dari Hostinger
❌ Laravel tidak bisa diakses
```

### Jika build/storage di-copy (bukan symlink):
```
❌ Assets tidak update saat build baru
❌ Disk space penuh (duplikasi file)
❌ Harus manual sync setiap kali
```

### Jika tidak sync saat deployment:
```
❌ Perubahan code tidak muncul di website
❌ Assets lama masih digunakan
❌ Bug yang sudah diperbaiki masih muncul
```

---

## 🔧 Troubleshooting

### Problem: Website return 403 Forbidden

**Solusi:**
```bash
ssh -p 65002 u909490256@153.92.9.187
cd /home/u909490256/domains/jastiphype.shop
bash fix-domain-root.sh
```

### Problem: Assets tidak update

**Solusi:**
```bash
cd /home/u909490256/domains/jastiphype.shop
npm run build
cd public_html
rm -rf build
ln -s ../public/build build
```

### Problem: Storage files tidak bisa diakses

**Solusi:**
```bash
cd /home/u909490256/domains/jastiphype.shop
php artisan storage:link
cd public_html
rm -rf storage
ln -s ../storage/app/public storage
```

---

## 📝 Files yang TIDAK BOLEH Dihapus

1. ✅ `fix-domain-root.sh` - Script untuk fix 403 Forbidden
2. ✅ `CRITICAL_SETUP_INFO.md` - File ini (dokumentasi penting)
3. ✅ `SOLUTION_SUMMARY.md` - Dokumentasi solusi lengkap
4. ✅ `HOSTINGER_SETUP_GUIDE.md` - Panduan setup & troubleshooting
5. ✅ `.github/workflows/deploy-hostinger.yml` - Workflow deployment

**Jika dihapus:** Website bisa return 403 Forbidden dan sulit untuk diperbaiki!

---

## 📞 Jika Ada Masalah

1. **Baca file ini dulu!** (CRITICAL_SETUP_INFO.md)
2. **Cek SOLUTION_SUMMARY.md** untuk detail solusi
3. **Jalankan fix-domain-root.sh** jika 403 Forbidden
4. **Cek logs:** `tail -f storage/logs/laravel.log`

---

## ✅ Checklist Sebelum Modifikasi

Sebelum mengubah apapun yang berkaitan dengan deployment:

- [ ] Sudah baca file CRITICAL_SETUP_INFO.md ini
- [ ] Sudah paham struktur folder Hostinger
- [ ] Sudah paham kenapa harus pakai path relatif
- [ ] Sudah paham kenapa harus pakai symlink
- [ ] Sudah backup file yang akan diubah
- [ ] Sudah test di local dulu

**Jika ragu, JANGAN UBAH!**

---

## 🎯 Summary

| Item | Value | Catatan |
|------|-------|---------|
| Domain | jastiphype.shop | Addon domain di Hostinger |
| Document Root | `/domains/jastiphype.shop/public_html` | Di DALAM project folder |
| Laravel Public | `/domains/jastiphype.shop/public` | Original Laravel public |
| index.php Path | Path relatif (`../`) | HARUS relatif, bukan absolute |
| build/ | Symlink ke `../public/build` | HARUS symlink, bukan copy |
| storage/ | Symlink ke `../storage/app/public` | HARUS symlink, bukan copy |
| Deployment | Sync + Update + Recreate | Setiap push ke master |

---

**Last Updated**: 14 Februari 2026  
**Status**: ✅ CRITICAL - DO NOT DELETE  
**Severity**: 🚨 HIGH - Jika dilanggar, website akan down!
