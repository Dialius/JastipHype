# 🚨 QUICK FIX ERROR 500

## JALANKAN PERINTAH INI DI SSH:

```bash
# Login SSH
ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002

# Masuk folder
cd /home/u909490256/domains/jastiphype.shop

# Pull code terbaru
git pull origin master

# QUICK FIX (copy paste semua baris ini sekaligus):
chmod -R 775 storage bootstrap/cache && \
rm -f bootstrap/cache/*.php && \
php artisan cache:clear 2>/dev/null || true && \
php artisan config:clear 2>/dev/null || true && \
php artisan view:clear 2>/dev/null || true && \
php artisan route:clear 2>/dev/null || true && \
php artisan config:cache && \
echo "✅ Fix complete! Refresh browser now."
```

## JIKA MASIH ERROR, CEK LOG:

```bash
# Cek error log
tail -50 storage/logs/laravel.log

# Atau jalankan script check
bash check-error-log.sh
```

## KEMUNGKINAN PENYEBAB ERROR 500:

1. **Cache corrupt** → Fixed by clearing cache
2. **Permission salah** → Fixed by chmod
3. **APP_KEY tidak ada** → Check .env
4. **Composer autoload** → Run: `composer dump-autoload`
5. **Database connection** → Check DB credentials in .env

## JIKA MASIH BELUM BISA:

Kirim output dari:
```bash
tail -50 storage/logs/laravel.log
```

Atau:
```bash
php artisan --version
```
