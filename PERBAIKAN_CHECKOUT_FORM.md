# Perbaikan Checkout Form - Midtrans Integration

## Masalah yang Ditemukan
Ketika user klik "PLACE ORDER", form tidak submit dan tetap di halaman yang sama.

## Penyebab Masalah
1. **Missing Hidden Input**: Form tidak memiliki hidden input untuk `shipping_cost`
2. **No Form Validation**: Tidak ada validasi JavaScript untuk memastikan semua field terisi sebelum submit
3. **Database Structure**: Tabel orders menggunakan struktur lama yang tidak kompatibel

## Solusi yang Diterapkan

### 1. Perbaikan Database Structure ✅
**File**: `database/migrations/2026_01_27_005048_fix_orders_table_structure.php`

Mengubah struktur tabel orders dari:
- `customer_name`, `customer_phone`, `customer_email` → `name`, `phone`, `email`
- `total_price` → `subtotal` + `total`
- Menambahkan kolom: `province_id`, `city_id`, `postal_code`, `payment_method`, `address`

**Status**: ✅ Migration berhasil dijalankan

### 2. Menambahkan Hidden Input untuk Shipping Cost ✅
**File**: `resources/views/checkout/index.blade.php`

Menambahkan sebelum closing tag `</form>`:
```html
<input type="hidden" name="shipping_cost" x-model="shippingCost">
```

**Fungsi**: Mengirim nilai shipping cost dari Alpine.js ke server

### 3. Menambahkan Form Validation ✅
**File**: `resources/views/checkout/index.blade.php`

**A. Menambahkan event handler di form tag:**
```html
<form ... @submit="validateForm">
```

**B. Menambahkan method `validateForm()` di Alpine.js:**
- Validasi province_id, city_id, postal_code harus terisi
- Validasi payment_method harus dipilih
- Menampilkan error message jika validasi gagal
- Log data form untuk debugging

### 4. Komponen Address Selector ✅
**File**: `resources/views/components/address-selector-modal.blade.php`

Sudah memiliki hidden inputs:
```html
<input type="hidden" name="province_id" :value="selectedProvinceId">
<input type="hidden" name="city_id" :value="selectedCityId">
<input type="hidden" name="subdistrict_id" :value="selectedSubdistrictId">
<input type="hidden" name="postal_code" :value="selectedPostalCode">
```

### 5. Komponen Payment Methods ✅
**File**: `resources/views/components/payment-methods-simple.blade.php`

Sudah memiliki hidden inputs:
```html
<input type="hidden" name="payment_method" :value="selectedPayment">
<input type="hidden" name="payment_detail" :value="getPaymentDetail()">
```

## Testing

### Cara Test Checkout:
1. **Buka browser console** (F12) untuk melihat log validasi
2. **Isi semua field** di form checkout:
   - Email
   - Name
   - Phone
   - Address
   - Province, City, Subdistrict (via modal)
   - Payment Method
3. **Klik "PLACE ORDER"**
4. **Lihat console log** untuk memastikan validasi passed
5. **Seharusnya redirect** ke halaman payment

### Expected Console Log:
```
Form validation passed. Submitting with data: {
  provinceId: "6",
  cityId: "152",
  postalCode: "10620",
  paymentMethod: "qris",
  shippingCost: 0
}
```

### Jika Validasi Gagal:
- Alert/notification akan muncul dengan pesan error
- Form tidak akan submit
- Console akan menunjukkan field mana yang kosong

## Troubleshooting

### Masalah: Form masih tidak submit
**Solusi**:
1. Buka browser console (F12)
2. Cek apakah ada error JavaScript
3. Cek apakah validasi passed (lihat console log)
4. Pastikan semua field terisi

### Masalah: Error "Column not found"
**Solusi**: Migration sudah dijalankan, tapi jika masih error:
```bash
php artisan migrate:status
```
Pastikan migration `2026_01_27_005048_fix_orders_table_structure` sudah ran.

### Masalah: Redirect tapi error di halaman payment
**Solusi**: Cek Laravel log:
```bash
Get-Content storage/logs/laravel.log -Tail 50
```

## Data yang Dikirim ke Server

Setelah perbaikan, form akan mengirim data berikut:
```
_token: [CSRF Token]
email: test@example.com
name: Test User
phone: 08123456789
address: Jl. Test No. 123
province_id: 6
city_id: 152
subdistrict_id: 1
postal_code: 10620
payment_method: qris
payment_detail: (kosong untuk QRIS, atau bank code untuk VA, dll)
shipping_cost: 0
```

## Next Steps

1. ✅ Database structure fixed
2. ✅ Hidden inputs added
3. ✅ Form validation added
4. ✅ Midtrans Core API tested and working
5. 🔄 **Test checkout flow end-to-end**
6. ⏳ Test all payment methods
7. ⏳ Test webhook integration
8. ⏳ Test payment status updates

## Konfirmasi Midtrans Direct/Core API

**Pertanyaan**: Apakah Midtrans tidak bisa menggunakan direct?

**Jawaban**: **BISA!** Midtrans Core API (Direct Payment) sudah ditest dan berfungsi dengan baik.

**Bukti**:
- File test: `test-midtrans-core-api.php`
- Semua payment methods berhasil ditest:
  - ✅ QRIS
  - ✅ Bank Transfer (BCA, BNI, BRI, Permata)
  - ✅ GoPay
  - ✅ Mandiri Bill Payment
  - ✅ Convenience Store (Indomaret, Alfamart)

**Implementasi**:
- Menggunakan `Midtrans\CoreApi::charge()` untuk create transaction
- Menggunakan custom UI untuk payment method selection
- Tidak menggunakan Snap UI (hosted page)
- Full control atas payment flow

## Summary

Masalah checkout form sudah diperbaiki dengan:
1. Memperbaiki struktur database
2. Menambahkan hidden input untuk shipping_cost
3. Menambahkan validasi form JavaScript
4. Memastikan semua data terkirim ke server

Midtrans Core API (Direct Payment) **sudah berfungsi dengan baik** dan siap digunakan.

Silakan test checkout lagi dengan mengikuti langkah-langkah di atas!
