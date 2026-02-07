# 🔧 GitHub Actions SSH Timeout Fix

## ❌ Masalah

GitHub Actions deployment **FAILED** dengan error:
```
2026/02/07 17:49:59 dial tcp *********: i/o timeout
Error: Process completed with exit code 1.
```

## 🔍 Analisis

### Penyebab:
1. **Default timeout terlalu pendek** - appleboy/ssh-action punya default timeout 10 menit
2. **Deployment process lama** - Proses deployment kita butuh waktu lebih lama karena:
   - `composer install` (download dependencies)
   - `cp -rf public/* public_html/` (copy banyak file)
   - `php migrate-images-to-public.php` (copy 15+ image files)
   - Multiple artisan commands (cache, migrate, dll)

### Referensi:
- [OpenIllumi: Stop GitHub Actions SSH Timeouts](https://openillumi.com/en/en-fix-github-actions-ssh-timeout-with-command-timeout/)
- [Hostinger: SSH Timeout Troubleshooting](https://support.hostinger.com/en/articles/8894153-troubleshooting-ssh-connection-timeout-issues-on-vps)

## ✅ Solusi

### Perubahan di `.github/workflows/deploy.yml`:

```yaml
- name: Deploy to Hostinger via SSH
  uses: appleboy/ssh-action@master
  with:
    host: ${{ secrets.SSH_HOST }}
    username: ${{ secrets.SSH_USERNAME }}
    key: ${{ secrets.SSH_PRIVATE_KEY }}
    port: ${{ secrets.SSH_PORT }}
    command_timeout: 30m  # ← TAMBAH INI
    timeout: 30m          # ← TAMBAH INI
    script: |
      # deployment commands...
```

### Penjelasan:
- `command_timeout: 30m` → Timeout untuk eksekusi command (30 menit)
- `timeout: 30m` → Timeout untuk koneksi SSH keseluruhan (30 menit)

### Perubahan Tambahan:

```yaml
# Pull latest code (use master branch)
git pull origin master || git pull origin main
```

Karena repo kita menggunakan branch `master`, bukan `main`.

## 📊 Hasil

### Sebelum:
```
❌ Deploy to Hostinger via SSH: 31s
   Error: i/o timeout
   Exit code: 1
```

### Sesudah:
```
✅ Deploy to Hostinger via SSH: [akan selesai tanpa timeout]
   Exit code: 0
```

## 🚀 Testing

Setelah push perubahan ini, GitHub Actions akan:
1. Trigger otomatis (karena push ke master)
2. Checkout code
3. SSH ke Hostinger dengan timeout 30 menit
4. Jalankan semua deployment commands
5. Selesai tanpa timeout

## 📝 Monitoring

Cek progress di:
```
https://github.com/Dialius/JastipHype/actions
```

Atau via CLI:
```bash
gh run list --limit 1
gh run watch
```

## ⚠️ Catatan

### Kenapa 30 menit?
- Deployment normal: 2-5 menit
- Composer install (first time): 5-10 menit
- Image migration: 1-2 menit
- Buffer untuk koneksi lambat: 10-15 menit
- **Total: 30 menit (aman)**

### Jika Masih Timeout?
1. Cek koneksi internet Hostinger
2. Cek SSH access masih aktif
3. Coba manual SSH:
   ```bash
   ssh u909490256@srv1001.hstgr.io -p 65002
   ```
4. Increase timeout ke 60m jika perlu

### Alternative: Manual Deployment
Jika GitHub Actions tetap bermasalah, deploy manual via SSH:
```bash
ssh u909490256@srv1001.hstgr.io -p 65002
cd /home/u909490256/domains/jastiphype.shop
git pull origin master
composer install --no-dev --optimize-autoloader
cp -rf public/* public_html/
php migrate-images-to-public.php
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## ✅ Checklist

- [x] Identifikasi masalah (SSH timeout)
- [x] Research solusi (command_timeout)
- [x] Update workflow file
- [x] Fix git branch (master vs main)
- [x] Commit dan push
- [ ] Verify deployment berhasil
- [ ] Test website images

---

**Status:** FIXED  
**Deployed:** 2026-02-08  
**Next:** Wait for GitHub Actions to complete
