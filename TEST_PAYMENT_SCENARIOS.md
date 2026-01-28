# Test Scenarios - Payment Method Selection

## 🧪 Skenario Testing Manual

### Scenario 1: QRIS Payment
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **QRIS**
3. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Hanya menampilkan **QR Code QRIS**
- ✅ Tidak ada opsi payment lain
- ✅ Database: `payment_method='qris'`, `payment_detail='qris'`

**Log yang diharapkan:**
```
[INFO] Mapping payment to Snap {"method":"qris","detail":"qris"}
[INFO] Order created successfully with Snap {"order_number":"ORD-...","token":"..."}
```

---

### Scenario 2: BNI Virtual Account
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **Virtual Account**
3. Expand dan pilih: **BNI**
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Hanya menampilkan **BNI Virtual Account**
- ✅ Tidak ada BCA, Mandiri, atau bank lain
- ✅ Database: `payment_method='bank_transfer'`, `payment_detail='bni'`

**Log yang diharapkan:**
```
[INFO] Mapping payment to Snap {"method":"bank_transfer","detail":"bni"}
[INFO] Bank transfer mapped {"result":["bni_va"]}
```

---

### Scenario 3: BCA Virtual Account
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **Virtual Account**
3. Expand dan pilih: **BCA** (default)
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Hanya menampilkan **BCA Virtual Account**
- ✅ Database: `payment_method='bank_transfer'`, `payment_detail='bca'`

---

### Scenario 4: Mandiri Bill Payment
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **Virtual Account**
3. Expand dan pilih: **Mandiri**
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Menampilkan **Mandiri Bill Payment** (bukan VA)
- ✅ Database: `payment_method='bank_transfer'`, `payment_detail='mandiri'`

**Note:** Mandiri menggunakan `echannel` (Bill Payment), bukan Virtual Account

---

### Scenario 5: GoPay E-Wallet
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **E-Wallet**
3. Expand dan pilih: **GoPay**
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Hanya menampilkan **GoPay**
- ✅ Muncul QR Code atau deeplink GoPay
- ✅ Database: `payment_method='ewallet'`, `payment_detail='gopay'`

---

### Scenario 6: ShopeePay E-Wallet
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **E-Wallet**
3. Expand dan pilih: **ShopeePay**
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Hanya menampilkan **ShopeePay**
- ✅ Database: `payment_method='ewallet'`, `payment_detail='shopeepay'`

---

### Scenario 7: Dana E-Wallet (via QRIS)
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **E-Wallet**
3. Expand dan pilih: **Dana**
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Menampilkan **QRIS** (Dana tidak support direct, harus via QRIS)
- ✅ Database: `payment_method='ewallet'`, `payment_detail='dana'`

**Note:** Dana menggunakan QRIS karena Midtrans tidak support direct Dana integration

---

### Scenario 8: Indomaret
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **Convenience Store**
3. Expand dan pilih: **Indomaret**
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Hanya menampilkan **Indomaret**
- ✅ Muncul payment code untuk dibayar di Indomaret
- ✅ Database: `payment_method='convenience_store'`, `payment_detail='indomaret'`

---

### Scenario 9: Alfamart
**Steps:**
1. Buka halaman checkout
2. Pilih payment method: **Convenience Store**
3. Expand dan pilih: **Alfamart**
4. Klik "PLACE ORDER"

**Expected Result:**
- ✅ Redirect ke halaman payment
- ✅ Hanya menampilkan **Alfamart**
- ✅ Database: `payment_method='convenience_store'`, `payment_detail='alfamart'`

---

## 🔍 Cara Verifikasi

### 1. Cek Browser Console
Buka Developer Tools (F12) → Console

Expected logs:
```javascript
Payment method changed to: bank_transfer
Payment detail: bni
```

### 2. Cek Network Tab
Developer Tools → Network → Filter: XHR

Cari request ke `/checkout/process`, klik dan lihat **Form Data**:
```
payment_method: bank_transfer
payment_detail: bni
```

### 3. Cek Database
```sql
SELECT 
    order_number,
    payment_method,
    payment_detail,
    total,
    status,
    created_at
FROM orders
ORDER BY created_at DESC
LIMIT 1;
```

Expected:
```
order_number    | payment_method  | payment_detail | total    | status  | created_at
----------------|-----------------|----------------|----------|---------|-------------------
ORD-69786D...   | bank_transfer   | bni            | 110000   | pending | 2026-01-27 07:45:00
```

### 4. Cek Laravel Log
```bash
tail -f storage/logs/laravel.log
```

Expected output saat checkout:
```
[2026-01-27 07:45:00] local.INFO: Checkout process started {...}
[2026-01-27 07:45:00] local.INFO: Validation passed {...}
[2026-01-27 07:45:00] local.INFO: Mapping payment to Snap {"method":"bank_transfer","detail":"bni"}
[2026-01-27 07:45:00] local.INFO: Bank transfer mapped {"result":["bni_va"]}
[2026-01-27 07:45:00] local.INFO: Order created successfully with Snap {"order_number":"ORD-..."}
```

### 5. Cek Midtrans Dashboard
1. Login ke Midtrans Dashboard (Sandbox)
2. Menu: **Transactions**
3. Cari order terbaru
4. Klik untuk melihat detail
5. Verifikasi **Payment Type** sesuai dengan pilihan user

---

## ❌ Common Issues & Solutions

### Issue 1: Masih muncul semua payment method
**Penyebab**: Cache belum di-clear atau enabled_payments tidak terkirim

**Solusi**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Issue 2: Payment detail tidak tersimpan
**Penyebab**: Migration belum dijalankan

**Solusi**:
```bash
php artisan migrate
```

Cek apakah kolom ada:
```sql
DESCRIBE orders;
```

### Issue 3: Error "Unknown payment method"
**Penyebab**: Typo di payment_method atau payment_detail

**Solusi**: Cek log dan pastikan value sesuai:
- `qris`, `bank_transfer`, `ewallet`, `convenience_store`
- Detail: `bca`, `bni`, `bri`, `mandiri`, `gopay`, dll

### Issue 4: Midtrans error "enabled_payments invalid"
**Penyebab**: Format enabled_payments salah atau value tidak valid

**Solusi**: Pastikan format array dan value sesuai Midtrans docs:
```php
['bni_va']  // ✅ Correct
['bni']     // ❌ Wrong
```

---

## 📊 Test Checklist

Gunakan checklist ini untuk memastikan semua skenario sudah ditest:

- [ ] Scenario 1: QRIS Payment
- [ ] Scenario 2: BNI Virtual Account
- [ ] Scenario 3: BCA Virtual Account
- [ ] Scenario 4: Mandiri Bill Payment
- [ ] Scenario 5: GoPay E-Wallet
- [ ] Scenario 6: ShopeePay E-Wallet
- [ ] Scenario 7: Dana E-Wallet (via QRIS)
- [ ] Scenario 8: Indomaret
- [ ] Scenario 9: Alfamart

**Additional Checks:**
- [ ] Database menyimpan payment_detail dengan benar
- [ ] Log menampilkan mapping yang benar
- [ ] Midtrans hanya menampilkan payment method yang dipilih
- [ ] Tidak ada error di console browser
- [ ] Tidak ada error di Laravel log

---

## 🎯 Success Criteria

Test dianggap **PASSED** jika:

1. ✅ User pilih payment method X → Midtrans hanya tampilkan X
2. ✅ Database menyimpan `payment_method` dan `payment_detail` dengan benar
3. ✅ Log menampilkan mapping yang sesuai
4. ✅ Tidak ada error di browser console atau Laravel log
5. ✅ Semua 9 skenario berfungsi dengan baik

---

**Last Updated**: 27 Januari 2026
**Status**: Ready for Testing
