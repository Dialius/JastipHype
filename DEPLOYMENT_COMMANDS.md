# 🚀 Deployment Commands untuk Hostinger

## 📋 Command yang Harus Dijalankan di SSH Server

Setelah code ter-deploy ke Hostinger (via GitHub Actions atau manual), jalankan command berikut di SSH:

### 1. Login ke SSH

```bash
ssh u909490256@srv1001.hstgr.io
# Masukkan password SSH Anda
```

### 2. Masuk ke Directory Project

```bash
cd domains/jastiphype.shop/public_html
```

### 3. Clear All Caches

```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

**Kenapa?** Agar Laravel load file dan route yang baru.

### 4. Optimize untuk Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Kenapa?** Meningkatkan performa di production dengan cache compiled files.

### 5. Set Permissions (Jika Perlu)

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
```

**Kenapa?** Memastikan Laravel bisa write ke storage dan cache.

### 6. Test Storage Route

```bash
php artisan route:list | grep storage
```

**Expected Output:**
```
GET|HEAD  storage/{path}  storage.serve
```

---

## 🎯 Quick Command (Copy-Paste Semua)

Untuk kemudahan, copy-paste command ini sekaligus:

```bash
cd domains/jastiphype.shop/public_html && \
php artisan cache:clear && \
php artisan route:clear && \
php artisan config:clear && \
php artisan view:clear && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
chmod -R 755 storage bootstrap/cache && \
chmod -R 775 storage/logs && \
echo "✅ Deployment commands completed!"
```

---

## 🔍 Verification Commands

Setelah menjalankan command di atas, verify dengan:

### 1. Check Route Registered

```bash
php artisan route:list --path=storage
```

**Expected:**
```
GET|HEAD  storage/{path}  storage.serve
```

### 2. Check Storage Files

```bash
ls -la storage/app/public/products/
```

**Expected:** Harus ada file gambar

### 3. Check Permissions

```bash
ls -la storage/
```

**Expected:** Folder harus writable (755 atau 775)

### 4. Test Image URL

Buka browser dan test:
```
https://jastiphype.shop/storage/products/[nama-file]
```

Ganti `[nama-file]` dengan file yang ada di storage.

---

## 🔄 Kapan Harus Menjalankan Command Ini?

### Setiap Kali Deploy Code Baru:
- ✅ Clear cache (cache:clear, route:clear, config:clear, view:clear)
- ✅ Rebuild cache (config:cache, route:cache, view:cache)

### Hanya Sekali (Setup Awal):
- ✅ Set permissions (chmod)

### Jika Ada Masalah:
- ✅ Clear semua cache
- ✅ Check route list
- ✅ Check permissions

---

## 🤖 Otomatis via GitHub Actions

Idealnya, command ini bisa ditambahkan ke GitHub Actions workflow. Mari saya cek file deploy.yml Anda:

### Update `.github/workflows/deploy.yml`

Tambahkan step ini setelah deployment:

```yaml
- name: Clear and Cache Laravel
  run: |
    ssh ${{ secrets.SSH_USER }}@${{ secrets.SSH_HOST }} << 'EOF'
      cd domains/jastiphype.shop/public_html
      php artisan cache:clear
      php artisan route:clear
      php artisan config:clear
      php artisan view:clear
      php artisan config:cache
      php artisan route:cache
      php artisan view:cache
      echo "✅ Laravel optimized for production"
    EOF
```

**Note:** Ini opsional. Jika GitHub Actions sudah handle deployment, tambahkan step ini.

---

## 📝 Troubleshooting

### Error: "Command not found"

Pastikan Anda di directory yang benar:
```bash
pwd
# Expected: /home/u909490256/domains/jastiphype.shop/public_html
```

### Error: "Permission denied"

Run dengan sudo atau fix permissions:
```bash
chmod -R 755 storage bootstrap/cache
```

### Error: "Route not found"

Clear dan rebuild cache:
```bash
php artisan route:clear
php artisan route:cache
php artisan route:list --path=storage
```

### Gambar Masih 404

1. Check file exists:
   ```bash
   ls -la storage/app/public/products/
   ```

2. Check route:
   ```bash
   php artisan route:list --path=storage
   ```

3. Check permissions:
   ```bash
   ls -la storage/app/public/
   ```

4. Test direct access:
   ```bash
   curl -I https://jastiphype.shop/storage/products/[filename]
   ```

---

## ✅ Checklist Deployment

- [ ] Login ke SSH
- [ ] Masuk ke directory project
- [ ] Clear all caches
- [ ] Rebuild caches
- [ ] Set permissions (jika perlu)
- [ ] Verify route registered
- [ ] Test image URL di browser
- [ ] Check homepage images
- [ ] Check product page images

---

## 🎯 Expected Results

Setelah menjalankan semua command:

1. ✅ Route `/storage/{path}` terdaftar
2. ✅ Cache ter-rebuild untuk production
3. ✅ Permissions correct
4. ✅ Images accessible via URL
5. ✅ Website menampilkan semua gambar

---

## 📞 Jika Masih Ada Masalah

1. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. Check web server error log:
   ```bash
   tail -f /var/log/apache2/error.log
   # atau
   tail -f /var/log/nginx/error.log
   ```

3. Test dengan curl:
   ```bash
   curl -v https://jastiphype.shop/storage/products/[filename]
   ```

4. Screenshot error dan laporkan

---

**Dibuat**: 7 Februari 2026
**Status**: ✅ Ready to Execute
**Estimasi Waktu**: 2-3 menit
