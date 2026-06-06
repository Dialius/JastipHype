<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'payment_code',
        'transaction_status',
        'fraud_status',
        'transaction_time',
        'settlement_time',
        'expiry_time',
        'payment_data',
        'qr_code_url',
        'deeplink_redirect',
        'pdf_url',
        // Legacy fields
        'bank_name',
        'account_name',
        'account_number',
        'payment_proof',
    ];

    protected $casts = [
        'payment_data' => 'array',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expiry_time' => 'datetime',
    ];

    /**
     * Get the order that owns the payment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return in_array($this->transaction_status, ['pending', 'unpaid']);
    }

    /**
     * Check if payment is successful
     */
    public function isSuccess()
    {
        return in_array($this->transaction_status, ['settlement', 'capture', 'paid']);
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire', 'failed']);
    }

    /**
     * Get payment instructions based on payment type
     */
    public function getInstructions()
    {
        $data = $this->payment_data;
        
        switch ($this->payment_type) {
            case 'qris':
                $qrUrl = $this->qr_code_url;
                if (!$qrUrl && isset($data['actions'])) {
                    foreach ($data['actions'] as $action) {
                        if (isset($action['name']) && $action['name'] === 'generate-qr-code') {
                            $qrUrl = $action['url'];
                            break;
                        }
                    }
                }
                if (!$qrUrl && isset($data['qr_string'])) {
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($data['qr_string']);
                }
                
                $deeplink = $this->deeplink_redirect;
                if (!$deeplink && isset($data['actions'])) {
                    foreach ($data['actions'] as $action) {
                        if (isset($action['name']) && $action['name'] === 'deeplink-redirect') {
                            $deeplink = $action['url'];
                            break;
                        }
                    }
                }

                return [
                    'type' => 'qris',
                    'title' => 'QRIS (Scan QR Code)',
                    'qr_url' => $qrUrl,
                    'deeplink' => $deeplink,
                    'acquirer' => $data['acquirer'] ?? 'gopay',
                    'steps' => [
                        'Buka aplikasi e-wallet pilihan Anda (GoPay, OVO, DANA, LinkAja, ShopeePay) atau aplikasi mobile banking.',
                        'Pilih opsi Pindai / Scan QR.',
                        'Arahkan kamera ke kode QR yang tertera di atas.',
                        'Periksa jumlah tagihan dan nama merchant (JastipHype) di layar.',
                        'Konfirmasi dan masukkan PIN transaksi Anda untuk menyelesaikan pembayaran.'
                    ]
                ];
                
            case 'gopay':
                $qrUrl = $this->qr_code_url;
                $deeplink = $this->deeplink_redirect;
                
                if (isset($data['actions'])) {
                    foreach ($data['actions'] as $action) {
                        if (isset($action['name'])) {
                            if ($action['name'] === 'generate-qr-code') {
                                $qrUrl = $action['url'];
                            } elseif ($action['name'] === 'deeplink-redirect') {
                                $deeplink = $action['url'];
                            }
                        }
                    }
                }
                
                return [
                    'type' => 'gopay',
                    'title' => 'GoPay',
                    'qr_url' => $qrUrl,
                    'deeplink' => $deeplink,
                    'steps' => [
                        'Jika menggunakan Smartphone: Klik tombol "Pay with GoPay App" untuk membayar langsung menggunakan aplikasi GoPay Anda.',
                        'Jika menggunakan PC/Laptop: Buka aplikasi GoPay atau Gojek di HP Anda.',
                        'Pilih menu "Bayar" lalu scan QR Code yang tampil di layar.',
                        'Konfirmasi pembayaran pada aplikasi GoPay/Gojek Anda.',
                        'Masukkan PIN GoPay Anda untuk menyelesaikan transaksi.'
                    ]
                ];
                
            case 'shopeepay':
                $deeplink = $this->deeplink_redirect;
                if (!$deeplink && isset($data['actions'])) {
                    foreach ($data['actions'] as $action) {
                        if (isset($action['name']) && $action['name'] === 'deeplink-redirect') {
                            $deeplink = $action['url'];
                            break;
                        }
                    }
                }
                return [
                    'type' => 'shopeepay',
                    'title' => 'ShopeePay',
                    'deeplink' => $deeplink,
                    'steps' => [
                        'Klik tombol "Pay with ShopeePay App" untuk membuka aplikasi Shopee.',
                        'Konfirmasi detail pembayaran di halaman pembayaran ShopeePay.',
                        'Masukkan PIN ShopeePay Anda untuk menyelesaikan transaksi.'
                    ]
                ];
                
            case 'bank_transfer':
                $bank = $data['va_numbers'][0]['bank'] ?? 'bca';
                $vaNumber = $data['va_numbers'][0]['va_number'] ?? $this->payment_code;
                
                $steps = [];
                if (strtolower($bank) === 'bca') {
                    $steps = [
                        'Masukkan Kartu ATM BCA & PIN Anda.',
                        'Pilih menu Transaksi Lainnya > Transfer > Ke Rekening BCA Virtual Account.',
                        'Masukkan nomor Virtual Account: ' . $vaNumber,
                        'Periksa detail transaksi. Jika sudah benar, masukkan jumlah pembayaran lalu pilih "Ya".',
                        'Ikuti instruksi selanjutnya untuk menyelesaikan transaksi.'
                    ];
                } elseif (strtolower($bank) === 'bni') {
                    $steps = [
                        'Masukkan Kartu ATM BNI & PIN Anda.',
                        'Pilih menu Menu Lain > Transfer > Virtual Account Billing.',
                        'Masukkan nomor Virtual Account: ' . $vaNumber,
                        'Periksa detail transaksi. Pilih "Ya" untuk melakukan pembayaran.',
                        'Ikuti instruksi selanjutnya untuk menyelesaikan transaksi.'
                    ];
                } elseif (strtolower($bank) === 'bri') {
                    $steps = [
                        'Masukkan Kartu ATM BRI & PIN Anda.',
                        'Pilih menu Transaksi Lain > Pembayaran > Lainnya > BRIVA.',
                        'Masukkan nomor BRIVA: ' . $vaNumber,
                        'Periksa detail transaksi. Pilih "Ya" untuk melakukan pembayaran.',
                        'Ikuti instruksi selanjutnya untuk menyelesaikan transaksi.'
                    ];
                } else {
                    $steps = [
                        'Lakukan transfer ke bank tujuan transfer Virtual Account.',
                        'Masukkan nomor Virtual Account: ' . $vaNumber,
                        'Periksa detail transaksi dan konfirmasi pembayaran.'
                    ];
                }
                
                return [
                    'type' => 'bank_transfer',
                    'title' => strtoupper($bank) . ' Virtual Account',
                    'bank' => strtoupper($bank),
                    'va_number' => $vaNumber,
                    'steps' => $steps
                ];
                
            case 'echannel':
                $billKey = $data['bill_key'] ?? null;
                $billerCode = $data['biller_code'] ?? null;
                return [
                    'type' => 'echannel',
                    'title' => 'Mandiri Bill Payment',
                    'bank' => 'MANDIRI',
                    'bill_key' => $billKey,
                    'biller_code' => $billerCode,
                    'steps' => [
                        'Masukkan Kartu ATM Mandiri & PIN Anda.',
                        'Pilih menu Bayar/Beli > Multi Payment.',
                        'Masukkan kode Biller Mandiri: ' . $billerCode,
                        'Masukkan nomor Bill Key: ' . $billKey,
                        'Periksa detail transaksi. Pilih item tagihan dan pilih "Ya" untuk membayar.',
                        'Ikuti instruksi selanjutnya untuk menyelesaikan transaksi.'
                    ]
                ];
                
            case 'cstore':
                $store = $data['store'] ?? 'indomaret';
                $paymentCode = $data['payment_code'] ?? $this->payment_code;
                return [
                    'type' => 'cstore',
                    'title' => ucfirst($store),
                    'store' => strtoupper($store),
                    'payment_code' => $paymentCode,
                    'steps' => [
                        'Datang ke gerai ' . ucfirst($store) . ' terdekat.',
                        'Katakan kepada kasir bahwa Anda ingin melakukan pembayaran "Midtrans" atau "JastipHype".',
                        'Tunjukkan kode pembayaran berikut kepada kasir: ' . $paymentCode,
                        'Bayar dengan nominal tagihan yang sesuai.',
                        'Simpan struk pembayaran sebagai bukti transaksi yang sah.'
                    ]
                ];
                
            default:
                return [
                    'type' => 'unknown',
                    'title' => 'Instructions',
                    'data' => $data,
                    'steps' => [
                        'Silakan ikuti instruksi yang dikirimkan ke email Anda untuk menyelesaikan pembayaran.'
                    ]
                ];
        }
    }

    /**
     * Get human-readable payment type name
     */
    public function getPaymentTypeName()
    {
        $names = [
            'qris' => 'QRIS',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'bank_transfer' => 'Bank Transfer',
            'echannel' => 'Mandiri Bill Payment',
            'cstore' => 'Convenience Store',
        ];

        return $names[$this->payment_type] ?? ucfirst($this->payment_type);
    }

    /**
     * Get human-readable status
     */
    public function getStatusLabel()
    {
        $labels = [
            'pending' => 'Awaiting Payment',
            'unpaid' => 'Unpaid',
            'settlement' => 'Success',
            'capture' => 'Success',
            'paid' => 'Success',
            'deny' => 'Denied',
            'cancel' => 'Cancelled',
            'expire' => 'Expired',
            'failed' => 'Failed',
        ];

        return $labels[$this->transaction_status] ?? ucfirst($this->transaction_status);
    }

    /**
     * Get status badge color
     */
    public function getStatusColor()
    {
        if ($this->isSuccess()) {
            return 'green';
        } elseif ($this->isPending()) {
            return 'yellow';
        } else {
            return 'red';
        }
    }
}
