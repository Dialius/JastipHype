# 🧪 Cara Test Checkout - Step by Step

## ✅ Yang Sudah Diperbaiki

1. ✅ **LocationController** - Sekarang menggunakan `RajaOngkirService` dengan mock data
2. ✅ **API Endpoints** - Provinces dan Cities sudah berfungsi
3. ✅ **Mock Data** - 34 provinsi dan kota-kota besar tersedia

---

## 🚀 Cara Test

### Method 1: Test API Endpoints (Recommended)

1. **Start Laravel Server**
   ```bash
   php artisan serve
   ```

2. **Buka Test Page**
   ```
   http://localhost:8000/test-shipping-api.html
   ```

3. **Test Semua Fitur:**
   - Klik "Test Provinces" → Harus muncul 34 provinsi
   - Pilih provinsi → Klik "Test Cities" → Harus muncul kota-kota
   - Isi form → Klik "Calculate Cost" → Harus muncul ongkir

---

### Method 2: Test di Checkout Page

1. **Tambah Item ke Cart**
   - Buka: http://localhost:8000/products
   - Pilih produk
   - Add to cart

2. **Buka Checkout**
   ```
   http://localhost:8000/checkout
   ```

3. **Test Shipping Calculator:**
   - Scroll ke bagian "Shipping Address"
   - Pilih **Province** → Dropdown harus terisi
   - Pilih **City** → Dropdown harus terisi
   - Tunggu sebentar → **Shipping options harus muncul**

4. **Cek Browser Console (F12):**
   - Buka Developer Tools (F12)
   - Lihat tab Console
   - Seharusnya ada log: "Provinces API Response", "Cities API Response", dll
   - Tidak boleh ada error merah

---

## 🔍 Troubleshooting

### Pilihan Shipping Tidak Muncul?

#### Cek 1: Browser Console
```
1. Tekan F12
2. Lihat tab Console
3. Cari error atau warning
4. Screenshot dan kirim ke saya
```

#### Cek 2: Network Tab
```
1. Tekan F12
2. Klik tab Network
3. Pilih province/city
4. Lihat request ke /api/location/provinces
5. Klik request → Preview → Lihat response
```

#### Cek 3: Response Format
Response harus seperti ini:
```json
{
  "rajaongkir": {
    "status": {
      "code": 200
    },
    "results": [
      {
        "province_id": "1",
        "province": "Bali"
      }
    ]
  }
}
```

---

## 📋 Checklist Test

### API Endpoints
- [ ] GET /api/location/provinces → Berhasil (34 provinsi)
- [ ] GET /api/location/cities/6 → Berhasil (5 kota Jakarta)
- [ ] POST /api/location/cost → Berhasil (muncul ongkir)

### Checkout Page
- [ ] Province dropdown terisi
- [ ] City dropdown terisi setelah pilih province
- [ ] Shipping options muncul setelah pilih city
- [ ] Bisa pilih shipping method
- [ ] Harga shipping terupdate di summary
- [ ] Tidak ada error di console

### Payment Method
- [ ] Klik E-Wallet → Garis pemisah muncul
- [ ] Klik Virtual Account → Garis pemisah muncul
- [ ] Background berubah saat dipilih

### Order Summary
- [ ] Klik "See All Items" → Smooth expand
- [ ] Klik "Show Less" → Smooth collapse
- [ ] Gradient overlay muncul saat collapsed

### Modals
- [ ] Klik "Vouchers" → Modal muncul
- [ ] Klik "Leave a message" → Modal muncul
- [ ] Bisa close dengan back arrow
- [ ] Bisa close dengan click outside
- [ ] Bisa close dengan ESC

---

## 🐛 Jika Masih Belum Muncul

### Langkah 1: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Langkah 2: Restart Server
```bash
# Stop server (Ctrl+C)
php artisan serve
```

### Langkah 3: Hard Refresh Browser
```
Ctrl + Shift + R (Windows)
Cmd + Shift + R (Mac)
```

### Langkah 4: Test API Langsung
```bash
php test-api-endpoints.php
```

Harus muncul:
```
✅ SUCCESS: Found 34 provinces
✅ SUCCESS: Found 5 cities
```

---

## 📸 Screenshot yang Dibutuhkan (Jika Masih Error)

Jika masih belum muncul, kirim screenshot:

1. **Browser Console (F12 → Console tab)**
   - Screenshot semua error/warning

2. **Network Tab (F12 → Network tab)**
   - Screenshot request ke /api/location/provinces
   - Screenshot response-nya

3. **Checkout Page**
   - Screenshot bagian shipping address
   - Screenshot dropdown province/city

---

## 💡 Expected Behavior

### Saat Pilih Province:
```
1. User pilih "DKI Jakarta"
2. Loading spinner muncul di dropdown city
3. Dropdown city terisi dengan:
   - Kota Jakarta Barat
   - Kota Jakarta Pusat
   - Kota Jakarta Selatan
   - Kota Jakarta Timur
   - Kota Jakarta Utara
```

### Saat Pilih City:
```
1. User pilih "Kota Jakarta Selatan"
2. Loading spinner muncul
3. Section "Shipping Method" muncul
4. Muncul pilihan kurir:
   - JNE - REG: Rp 9.000 (2-3 days)
   - JNE - YES: Rp 18.000 (1-1 days)
   - TIKI - REG: Rp 8.100 (3-5 days)
   - POS - Paket Kilat: Rp 7.200 (2-4 days)
```

---

## 🎯 Quick Test Commands

```bash
# Test 1: Clear everything
php artisan optimize:clear

# Test 2: Test API
php test-api-endpoints.php

# Test 3: Start server
php artisan serve

# Test 4: Open browser
# http://localhost:8000/test-shipping-api.html
```

---

## 📞 Jika Masih Bermasalah

Kirim informasi berikut:

1. Screenshot browser console (F12)
2. Screenshot network tab
3. Output dari: `php test-api-endpoints.php`
4. Screenshot checkout page

Saya akan bantu debug lebih lanjut!

---

**Status Saat Ini:**
- ✅ API Endpoints: Working (mock data)
- ✅ LocationController: Fixed
- ✅ RajaOngkirService: Working
- ⚠️ Perlu test di browser untuk konfirmasi

**Next Step:**
Test di browser dengan langkah di atas!
