# Checklist Pengecekan Setelah Restore Backup

## 1. Cek Struktur File
```bash
ssh -p 65002 u909490256@153.92.9.187 "ls -la domains/jastiphype.shop/"
ssh -p 65002 u909490256@153.92.9.187 "ls -la public_html/"
```

## 2. Cek File Penting
```bash
# Cek apakah vendor ada
ssh -p 65002 u909490256@153.92.9.187 "ls -la domains/jastiphype.shop/vendor/ | head -10"

# Cek apakah .env ada
ssh -p 65002 u909490256@153.92.9.187 "cat domains/jastiphype.shop/.env"

# Cek apakah public folder ada
ssh -p 65002 u909490256@153.92.9.187 "ls -la domains/jastiphype.shop/public/"
```

## 3. Cek Document Root
```bash
# Cek apakah public_html adalah symlink atau folder biasa
ssh -p 65002 u909490256@153.92.9.187 "ls -la | grep public_html"

# Cek isi public_html
ssh -p 65002 u909490256@153.92.9.187 "ls -la public_html/"
```

## 4. Test Website
```bash
# Test apakah website bisa diakses
ssh -p 65002 u909490256@153.92.9.187 "curl -I https://jastiphype.shop"

# Test apakah menampilkan Laravel atau halaman default
ssh -p 65002 u909490256@153.92.9.187 "curl -s https://jastiphype.shop | head -30"
```

## 5. Cek Error Log
```bash
ssh -p 65002 u909490256@153.92.9.187 "tail -50 domains/jastiphype.shop/storage/logs/laravel.log"
```

## 6. Perbaikan yang Mungkin Diperlukan

### A. Jika public_html kosong atau tidak lengkap:
```bash
# Copy file dari public Laravel ke public_html
ssh -p 65002 u909490256@153.92.9.187 "cp -r domains/jastiphype.shop/public/* public_html/"
```

### B. Jika index.php path salah:
```bash
# Update index.php untuk mengarah ke path yang benar
# (akan dibuat script khusus)
```

### C. Jika storage symlink tidak ada:
```bash
ssh -p 65002 u909490256@153.92.9.187 "cd domains/jastiphype.shop && php artisan storage:link"
```

### D. Jika cache bermasalah:
```bash
ssh -p 65002 u909490256@153.92.9.187 "cd domains/jastiphype.shop && php artisan optimize:clear"
```

## 7. Cari Solusi di Internet Jika Ada Error

Jika menemukan error, akan dicari solusinya di internet dengan keyword:
- "Laravel Hostinger [error message]"
- "PHP [error message] Hostinger"
- "Laravel document root configuration"
