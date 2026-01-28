# Perbaikan Payment Method Selection

## Masalah yang Ditemukan

1. **Payment method tidak sesuai**: User memilih BNI di checkout, tapi di halaman payment muncul BCA
2. **QRIS menampilkan semua metode**: User pilih QRIS, tapi muncul semua opsi pembayaran
3. **Payment detail tidak tersimpan**: Pilihan bank/e-wallet tidak disimpan ke database

## Root Cause

1. **Fungsi `mapPaymentToSnap()` di-comment** di `CheckoutController.php` (baris 127-130)
2. **Field `payment_detail` tidak ada** di tabel `orders`
3. **Component payment-methods-simple** tidak mengirim detail untuk QRIS

## Solusi yang Diterapkan

### 1. Database Migration
**File**: `database/migrations/2026_01_27_074156_add_payment_detail_to_orders_table.php`

Menambahkan kolom `payment_detail` ke tabel `orders` untuk menyimpan pilihan spesifik user (bank, e-wallet, store).

```php
$table->string('payment_detail')->nullable()->after('payment_method');
```

### 2. Update Model Order
**File**: `app/Models/Order.php`

Menambahkan `payment_detail` ke `$fillable`:

```php
protected $fillable = [
    // ... existing fields
    'payment_method',
    'payment_detail',  // ← BARU
    // ... other fields
];
```

### 3. Update CheckoutController
**File**: `app/Http/Controllers/CheckoutController.php`

#### a. Aktifkan mapping payment method
```php
// SEBELUM (di-comment):
// $enabledPayments = $this->mapPaymentToSnap(...);

// SESUDAH (aktif):
$enabledPayments = $this->mapPaymentToSnap($validated['payment_method'], $validated['payment_detail'] ?? null);
if ($enabledPayments) {
   $orderData['enabled_payments'] = $enabledPayments;
}
```

#### b. Simpan payment_detail ke database
```php
$order = Order::create([
    // ... existing fields
    'payment_method' => $validated['payment_method'],
    'payment_detail' => $validated['payment_detail'] ?? null,  // ← BARU
    // ... other fields
]);
```

#### c. Perbaiki fungsi mapPaymentToSnap()
```php
private function mapPaymentToSnap($method, $detail)
{
    switch ($method) {
        case 'qris':
            return ['qris'];  // ← Hanya QRIS
        
        case 'bank_transfer':
            $map = [
                'bca' => 'bca_va',
                'mandiri' => 'echannel',
                'bni' => 'bni_va',      // ← Mapping benar
                'bri' => 'bri_va',
                'permata' => 'permata_va'
            ];
            return isset($map[$detail]) ? [$map[$detail]] : ['bca_va'];

        case 'ewallet':
            $map = [
                'gopay' => 'gopay',
                'shopeepay' => 'shopeepay',
                'dana' => 'qris',       // ← Dana via QRIS
                'ovo' => 'qris'         // ← OVO via QRIS
            ];
            return isset($map[$detail]) ? [$map[$detail]] : ['gopay'];
        
        case 'convenience_store':
            return [$detail ?: 'indomaret'];
            
        default:
            return null;
    }
}
```

### 4. Update Payment Component
**File**: `resources/views/components/payment-methods-simple.blade.php`

Menambahkan handling untuk QRIS dan logging:

```javascript
getPaymentDetail() {
    if (this.selectedPayment === 'bank_transfer') {
        const selected = document.querySelector('input[name="bank_type"]:checked');
        return selected ? selected.value : 'bca';
    } else if (this.selectedPayment === 'ewallet') {
        const selected = document.querySelector('input[name="ewallet_type"]:checked');
        return selected ? selected.value : '';
    } else if (this.selectedPayment === 'convenience_store') {
        const selected = document.querySelector('input[name="store_type"]:checked');
        return selected ? selected.value : '';
    } else if (this.selectedPayment === 'qris') {
        return 'qris';  // ← BARU: Return 'qris' untuk QRIS
    }
    return '';
}
```

## Mapping Payment Method ke Midtrans

| User Selection | Payment Method | Payment Detail | Midtrans enabled_payments |
|---------------|----------------|----------------|---------------------------|
| QRIS | `qris` | `qris` | `['qris']` |
| BCA VA | `bank_transfer` | `bca` | `['bca_va']` |
| BNI VA | `bank_transfer` | `bni` | `['bni_va']` |
| BRI VA | `bank_transfer` | `bri` | `['bri_va']` |
| Mandiri Bill | `bank_transfer` | `mandiri` | `['echannel']` |
| Permata VA | `bank_transfer` | `permata` | `['permata_va']` |
| GoPay | `ewallet` | `gopay` | `['gopay']` |
| ShopeePay | `ewallet` | `shopeepay` | `['shopeepay']` |
| Dana | `ewallet` | `dana` | `['qris']` |
| OVO | `ewallet` | `ovo` | `['qris']` |
| Indomaret | `convenience_store` | `indomaret` | `['indomaret']` |
| Alfamart | `convenience_store` | `alfamart` | `['alfamart']` |

## Testing

File test: `test-payment-mapping.php`

```bash
php test-payment-mapping.php
```

**Result**: ✓ All 9 tests passed!

## Cara Kerja Setelah Perbaikan

1. **User memilih payment method** di checkout (misal: BNI Virtual Account)
2. **Form mengirim data**:
   - `payment_method`: `bank_transfer`
   - `payment_detail`: `bni`
3. **CheckoutController menerima dan mapping**:
   - Memanggil `mapPaymentToSnap('bank_transfer', 'bni')`
   - Return: `['bni_va']`
4. **Data dikirim ke Midtrans**:
   ```php
   $orderData['enabled_payments'] = ['bni_va'];
   ```
5. **Midtrans Snap hanya menampilkan BNI VA** di halaman payment
6. **Data tersimpan di database**:
   - `payment_method`: `bank_transfer`
   - `payment_detail`: `bni`

## Verifikasi

### 1. Cek Database
```sql
SELECT order_number, payment_method, payment_detail 
FROM orders 
ORDER BY created_at DESC 
LIMIT 5;
```

### 2. Cek Log Laravel
```bash
tail -f storage/logs/laravel.log | grep "Mapping payment"
```

Output yang diharapkan:
```
Mapping payment to Snap {"method":"bank_transfer","detail":"bni"}
Bank transfer mapped {"result":["bni_va"]}
```

### 3. Test Manual
1. Buka halaman checkout
2. Pilih payment method (misal: BNI)
3. Submit form
4. Di halaman payment Midtrans, seharusnya **hanya muncul BNI VA**
5. Cek database, `payment_detail` harus berisi `bni`

## Catatan Penting

- **QRIS**: Hanya menampilkan QR code, tidak ada pilihan lain
- **Dana & OVO**: Menggunakan QRIS karena Midtrans tidak support direct integration
- **Mandiri**: Menggunakan `echannel` (Mandiri Bill Payment), bukan VA
- **enabled_payments**: Array yang dikirim ke Midtrans untuk membatasi metode pembayaran

## Troubleshooting

### Masalah: Masih muncul semua payment method
**Solusi**: 
1. Clear cache: `php artisan cache:clear`
2. Cek log untuk memastikan `enabled_payments` terkirim
3. Pastikan Midtrans Snap API version terbaru

### Masalah: Payment detail tidak tersimpan
**Solusi**:
1. Jalankan migration: `php artisan migrate`
2. Cek `$fillable` di model Order
3. Cek form mengirim `payment_detail`

### Masalah: Error "Unknown payment method"
**Solusi**:
1. Cek spelling payment_method dan payment_detail
2. Pastikan value sesuai dengan mapping di `mapPaymentToSnap()`
3. Cek log untuk detail error
