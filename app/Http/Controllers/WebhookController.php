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
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
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
}
