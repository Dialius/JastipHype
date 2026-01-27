<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\CoreApi;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create transaction using Core API (Direct Payment)
     * This allows you to use your own payment method selection UI
     * 
     * @param array $orderData
     * @param string $paymentType (qris, gopay, bank_transfer, etc)
     * @param array $paymentDetails (bank code, etc)
     * @return array
     */
    public function createTransaction($orderData, $paymentType, $paymentDetails = [])
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderData['order_id'],
                'gross_amount' => $orderData['gross_amount'],
            ],
            'customer_details' => [
                'first_name' => $orderData['customer']['first_name'],
                'last_name' => $orderData['customer']['last_name'] ?? '',
                'email' => $orderData['customer']['email'],
                'phone' => $orderData['customer']['phone'],
                'billing_address' => $orderData['customer']['address'] ?? null,
                'shipping_address' => $orderData['customer']['address'] ?? null,
            ],
            'item_details' => $orderData['items'],
        ];

        // Add payment type specific parameters
        switch ($paymentType) {
            case 'qris':
                $params['payment_type'] = 'qris';
                $params['qris'] = [
                    'acquirer' => 'gopay' // or 'airpay shopee'
                ];
                break;

            case 'gopay':
                $params['payment_type'] = 'gopay';
                $params['gopay'] = [
                    'enable_callback' => true,
                    'callback_url' => url('/payment/gopay/callback')
                ];
                break;

            case 'shopeepay':
                $params['payment_type'] = 'shopeepay';
                $params['shopeepay'] = [
                    'callback_url' => url('/payment/shopeepay/callback')
                ];
                break;

            case 'bank_transfer':
                $params['payment_type'] = 'bank_transfer';
                $params['bank_transfer'] = [
                    'bank' => $paymentDetails['bank'] ?? 'bca' // bca, bni, bri, permata, mandiri
                ];
                break;

            case 'echannel':
                // For Mandiri Bill Payment
                $params['payment_type'] = 'echannel';
                $params['echannel'] = [
                    'bill_info1' => 'Payment For:',
                    'bill_info2' => 'Order ' . $orderData['order_id']
                ];
                break;

            case 'cstore':
                // For convenience store (Indomaret, Alfamart)
                $params['payment_type'] = 'cstore';
                $params['cstore'] = [
                    'store' => $paymentDetails['store'] ?? 'indomaret', // indomaret or alfamart
                    'message' => 'Payment for Order ' . $orderData['order_id']
                ];
                break;

            default:
                throw new \Exception('Unsupported payment type: ' . $paymentType);
        }

        try {
            // Call Midtrans Core API
            $response = CoreApi::charge($params);
            
            return [
                'success' => true,
                'data' => $response,
                'payment_type' => $paymentType
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction status
     * 
     * @param string $orderId
     * @return array
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            
            return [
                'success' => true,
                'data' => $status
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cancel transaction
     * 
     * @param string $orderId
     * @return array
     */
    public function cancelTransaction($orderId)
    {
        try {
            $response = Transaction::cancel($orderId);
            
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Map payment method from your UI to Midtrans payment type
     * 
     * @param string $paymentMethod
     * @param array $details
     * @return array
     */
    public function mapPaymentMethod($paymentMethod, $details = [])
    {
        $paymentDetail = $details['detail'] ?? '';
        
        $mapping = [
            'qris' => [
                'type' => 'qris',
                'details' => []
            ],
            'ewallet' => [
                'type' => $this->mapEwalletType($paymentDetail),
                'details' => []
            ],
            'bank_transfer' => [
                'type' => $paymentDetail === 'mandiri' ? 'echannel' : 'bank_transfer',
                'details' => $paymentDetail === 'mandiri' ? [] : ['bank' => $paymentDetail ?: 'bca']
            ],
            'convenience_store' => [
                'type' => 'cstore',
                'details' => ['store' => $paymentDetail ?: 'indomaret']
            ],
        ];

        return $mapping[$paymentMethod] ?? [
            'type' => 'qris',
            'details' => []
        ];
    }

    /**
     * Map e-wallet type to Midtrans payment type
     * 
     * @param string $ewalletType
     * @return string
     */
    private function mapEwalletType($ewalletType)
    {
        $mapping = [
            'gopay' => 'gopay',
            'shopeepay' => 'shopeepay',
            'dana' => 'qris', // Dana via QRIS
            'ovo' => 'qris', // OVO via QRIS
        ];

        return $mapping[$ewalletType] ?? 'qris';
    }

    /**
     * Create Snap transaction
     * 
     * @param array $orderData
     * @return array
     */
    public function createSnapTransaction($orderData)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderData['order_id'],
                'gross_amount' => $orderData['gross_amount'],
            ],
            'customer_details' => [
                'first_name' => $orderData['customer']['first_name'],
                'last_name' => $orderData['customer']['last_name'] ?? '',
                'email' => $orderData['customer']['email'],
                'phone' => $orderData['customer']['phone'],
                'billing_address' => $orderData['customer']['address'] ?? null,
                'shipping_address' => $orderData['customer']['address'] ?? null,
            ],
            'item_details' => $orderData['items'],
        ];

        // Add enabled payments if specified (to filter payment methods on Snap)
        if (isset($orderData['enabled_payments'])) {
            $params['enabled_payments'] = $orderData['enabled_payments'];
        }

        try {
            $transaction = Snap::createTransaction($params);
            
            return [
                'success' => true,
                'token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
