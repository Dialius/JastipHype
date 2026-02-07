# 🚀 Quick Start - Test Gambar

## ⚡ Testing Cepat (5 Menit)

### 1. Start Server
```bash
php artisan serve
```

### 2. Buka Test Page
```
http://localhost:8000/test-storage.php
```

**✅ Expected:** Gambar test muncul

### 3. Buka Homepage
```
http://localhost:8000/
```

**✅ Expected:** Semua gambar (banner, products, categories) muncul

### 4. Check Browser Console
- Tekan **F12**
- Tab **Console** → Tidak ada error merah
- Tab **Network** → Filter "Img" → Semua status **200**

---

## 🔧 Jika Gambar Tidak Muncul

### Quick Fix:
```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

Restart server:
```bash
# Ctrl+C
php artisan serve
```

Clear browser cache: **Ctrl+Shift+R**

---

## 📚 Dokumentasi Lengkap

- **Cara Test**: `CARA_TEST_GAMBAR.md`
- **Summary**: `SUMMARY_PERBAIKAN_GAMBAR.md`
- **Technical**: `STORAGE_FIX_WINDOWS.md`
- **Checklist**: `TESTING_CHECKLIST.md`

---

## ✅ Success Criteria

- ✅ Test page shows image
- ✅ Homepage shows all images
- ✅ No errors in console
- ✅ All images status 200

---

**Status**: ✅ Ready for Testing
**Time**: ~5 minutes
