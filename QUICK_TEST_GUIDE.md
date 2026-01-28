# Quick Test Guide - Payment Method Fix

## 🚀 Cara Test Cepat

### 1. Test QRIS (Yang Tadi Bermasalah)

**Steps:**
1. Buka browser: `http://localhost/checkout`
2. Isi form checkout
3. **Pilih payment method: QRIS**
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke Midtrans Snap
- ✅ Muncul **QR Code QRIS**
- ✅ Tidak ada error "No payment channels available"
- ✅ QR Code bisa di-scan dengan e-wallet apapun

**Jika Masih Error:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Cek log
tail -f storage/logs/laravel.log
```

---

### 2. Test Bank Transfer (BNI)

**Steps:**
1. Checkout page
2. Pilih **Virtual Account** → Expand
3. Pilih **BNI**
4. Submit

**Expected Result:**
- ✅ Hanya muncul **BNI Virtual Account**
- ✅ Tidak ada BCA, Mandiri, atau bank lain

---

### 3. Test E-Wallet (GoPay)

**Steps:**
1. Checkout page
2. Pilih **E-Wallet** → Expand
3. Pilih **GoPay**
4. Submit

**Expected Result:**
- ✅ Hanya muncul **GoPay**
- ✅ Muncul QR Code atau deeplink GoPay

---

## 🔍 Cara Verifikasi

### Cek Database
```sql
SELECT 
    order_number,
    payment_method,
    payment_detail,
    created_at
FROM orders
ORDER BY created_at DESC
LIMIT 5;
```

**Expected untuk QRIS:**
```
order_number    | payment_method | payment_detail | created_at
----------------|----------------|----------------|-------------------
ORD-69786E...   | qris           | qris           | 2026-01-27 08:00:00
```

### Cek Log Laravel
```bash
tail -f storage/logs/laravel.log | grep "Mapping payment"
```

**Expected output untuk QRIS:**
```
[INFO] Mapping payment to Snap {"method":"qris","detail":"qris"}
[INFO] QRIS mapped to GoPay/ShopeePay acquirer {"result":["gopay","shopeepay"]}
```

### Cek Browser Console
Buka Developer Tools (F12) → Console

**Expected:**
```javascript
Payment method changed to: qris
Payment detail: qris
```

---

## ❌ Troubleshooting

### Error: "No payment channels available"

**Penyebab**: Cache belum di-clear atau payment method belum aktif di Midtrans

**Solusi**:
```bash
php artisan cache:clear
php artisan config:clear
```

Cek Midtrans Dashboard:
1. Login ke [Midtrans Dashboard](https://dashboard.sandbox.midtrans.com)
2. Settings → Payment Methods
3. Pastikan **GoPay** dan **ShopeePay** aktif

---

### Error: "Payment detail tidak tersimpan"

**Solusi**:
```bash
# Jalankan migration
php artisan migrate

# Verifikasi kolom ada
php artisan tinker
>>> Schema::hasColumn('orders', 'payment_detail')
# Harus return: true
```

---

### Error: "Masih muncul semua payment method"

**Penyebab**: `enabled_payments` tidak terkirim ke Midtrans

**Solusi**:
1. Cek log untuk memastikan mapping jalan:
```bash
tail -f storage/logs/laravel.log | grep "Mapping"
```

2. Pastikan fungsi `mapPaymentToSnap()` tidak di-comment di CheckoutController

3. Verifikasi dengan script:
```bash
php verify-payment-fix.php
```

---

## 📋 Checklist Test

Gunakan checklist ini untuk memastikan semua berfungsi:

- [ ] **QRIS**: Muncul QR Code, tidak ada error
- [ ] **BCA VA**: Hanya muncul BCA
- [ ] **BNI VA**: Hanya muncul BNI
- [ ] **BRI VA**: Hanya muncul BRI
- [ ] **Mandiri**: Hanya muncul Mandiri Bill
- [ ] **GoPay**: Hanya muncul GoPay
- [ ] **ShopeePay**: Hanya muncul ShopeePay
- [ ] **Dana**: Muncul QR Code via GoPay
- [ ] **Indomaret**: Hanya muncul Indomaret
- [ ] **Alfamart**: Hanya muncul Alfamart

**Additional Checks:**
- [ ] Database menyimpan `payment_detail` dengan benar
- [ ] Log menampilkan mapping yang sesuai
- [ ] Tidak ada error di browser console
- [ ] Tidak ada error di Laravel log

---

## 🎯 Success Criteria

Test dianggap **BERHASIL** jika:

1. ✅ QRIS menampilkan QR Code (tidak error lagi)
2. ✅ Setiap payment method hanya menampilkan yang dipilih
3. ✅ Database menyimpan `payment_method` dan `payment_detail`
4. ✅ Log menampilkan mapping yang benar
5. ✅ Tidak ada error di console atau log

---

## 📞 Jika Masih Ada Masalah

1. **Jalankan verification script**:
```bash
php verify-payment-fix.php
```

2. **Cek semua test**:
```bash
php test-payment-mapping.php
```

3. **Lihat log detail**:
```bash
tail -100 storage/logs/laravel.log
```

4. **Cek dokumentasi**:
- `QRIS_FIX_EXPLANATION.md` - Penjelasan fix QRIS
- `PAYMENT_METHOD_FIX.md` - Dokumentasi lengkap
- `SUMMARY_PAYMENT_FIX.md` - Summary perbaikan

---

**Last Updated**: 27 Januari 2026
**Status**: Ready for Testing
**All Tests**: ✅ PASSED
