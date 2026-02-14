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
                return [
                    'type' => 'qris',
                    'qr_string' => $data['qr_string'] ?? null,
                    'acquirer' => $data['acquirer'] ?? 'gopay',
                ];
                
            case 'gopay':
                return [
                    'type' => 'gopay',
                    'deeplink' => $data['actions'][0]['url'] ?? $this->deeplink_redirect,
                    'qr_code' => $data['actions'][1]['url'] ?? $this->qr_code_url,
                ];
                
            case 'shopeepay':
                return [
                    'type' => 'shopeepay',
                    'deeplink' => $data['actions'][0]['url'] ?? $this->deeplink_redirect,
                ];
                
            case 'bank_transfer':
                $bank = $data['va_numbers'][0]['bank'] ?? 'bca';
                return [
                    'type' => 'bank_transfer',
                    'bank' => strtoupper($bank),
                    'va_number' => $data['va_numbers'][0]['va_number'] ?? $this->payment_code,
                ];
                
            case 'echannel':
                return [
                    'type' => 'echannel',
                    'bank' => 'MANDIRI',
                    'bill_key' => $data['bill_key'] ?? null,
                    'biller_code' => $data['biller_code'] ?? null,
                ];
                
            case 'cstore':
                return [
                    'type' => 'cstore',
                    'store' => strtoupper($data['store'] ?? 'indomaret'),
                    'payment_code' => $data['payment_code'] ?? $this->payment_code,
                ];
                
            default:
                return [
                    'type' => 'unknown',
                    'data' => $data,
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
