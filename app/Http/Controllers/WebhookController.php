<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class WebhookController extends Controller
{
    /**
     * Handle Midtrans webhook notification
     */
    public function handle(Request $request)
    {
        try {
            // Set Midtrans configuration
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            // Create notification instance
            $notification = new Notification();

            // Get notification data
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $orderNumber = $notification->order_id;

            Log::info('Midtrans Webhook Received', [
                'order_id' => $orderNumber,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            // Find order and payment
            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::error('Order not found for webhook', ['order_number' => $orderNumber]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = $order->payment;

            if (!$payment) {
                Log::error('Payment not found for webhook', ['order_number' => $orderNumber]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Update payment based on transaction status
            $updateData = [
                'transaction_id' => $notification->transaction_id ?? null,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $notification->payment_type ?? null,
            ];

            // Handle different transaction statuses
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // Payment successful
                    $order->update(['status' => 'processing']);
                    $updateData['settlement_time'] = now();
                }
            } elseif ($transactionStatus == 'settlement') {
                // Payment successful
                $order->update(['status' => 'processing']);
                $updateData['settlement_time'] = now();
            } elseif ($transactionStatus == 'pending') {
                // Payment pending
                $order->update(['status' => 'pending']);
            } elseif ($transactionStatus == 'deny') {
                // Payment denied
                $order->update(['status' => 'cancelled']);
            } elseif ($transactionStatus == 'expire') {
                // Payment expired
                $order->update(['status' => 'cancelled']);
            } elseif ($transactionStatus == 'cancel') {
                // Payment cancelled
                $order->update(['status' => 'cancelled']);
            }

            // Update payment record
            $payment->update($updateData);
            
            // Also update order payment_method and payment_detail based on actual payment
            if (isset($notification->payment_type)) {
                $order->update([
                    'payment_method' => $notification->payment_type,
                    'payment_detail' => $this->getPaymentDetail($notification)
                ]);
            }

            Log::info('Webhook processed successfully', [
                'order_number' => $orderNumber,
                'order_status' => $order->status,
                'payment_status' => $transactionStatus,
            ]);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Webhook processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get payment detail from notification
     */
    private function getPaymentDetail($notification)
    {
        $paymentType = $notification->payment_type ?? '';
        
        // Extract specific payment details based on type
        switch ($paymentType) {
            case 'bank_transfer':
                // Get bank name from VA number
                if (isset($notification->va_numbers[0])) {
                    return $notification->va_numbers[0]->bank ?? 'bank_transfer';
                }
                return 'bank_transfer';
                
            case 'echannel':
                return 'mandiri';
                
            case 'gopay':
                return 'gopay';
                
            case 'shopeepay':
                return 'shopeepay';
                
            case 'qris':
                return 'qris';
                
            case 'cstore':
                return $notification->store ?? 'cstore';
                
            case 'credit_card':
                return $notification->card_type ?? 'credit_card';
                
            default:
                return $paymentType;
        }
    }
}
