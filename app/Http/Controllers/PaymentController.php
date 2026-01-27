<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Show payment instructions page
     */
    public function show($orderNumber)
    {
        $order = Order::with(['payment', 'items.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Check if user owns this order (if logged in)
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $payment = $order->payment;

        if (!$payment) {
            return redirect()->route('home')->with('error', 'Payment information not found.');
        }

        // Get payment instructions
        $instructions = $payment->getInstructions();

        return view('payment.show', compact('order', 'payment', 'instructions'));
    }

    /**
     * Check payment status via AJAX
     */
    public function checkStatus($orderNumber)
    {
        try {
            $order = Order::where('order_number', $orderNumber)->firstOrFail();
            
            // Check if user owns this order (if logged in)
            if (auth()->check() && $order->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $payment = $order->payment;

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            // Get status from Midtrans
            $statusResponse = $this->midtrans->getTransactionStatus($orderNumber);

            if (!$statusResponse['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to check payment status'
                ]);
            }

            $status = $statusResponse['data'];

            // Update payment record
            $payment->update([
                'transaction_status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null,
                'settlement_time' => isset($status->settlement_time) ? date('Y-m-d H:i:s', strtotime($status->settlement_time)) : null,
            ]);

            // Update order status if payment is successful
            if (in_array($status->transaction_status, ['settlement', 'capture'])) {
                $order->update(['status' => 'processing']);
            } elseif (in_array($status->transaction_status, ['deny', 'cancel', 'expire'])) {
                $order->update(['status' => 'cancelled']);
            }

            return response()->json([
                'success' => true,
                'status' => $status->transaction_status,
                'status_label' => $payment->getStatusLabel(),
                'is_success' => $payment->isSuccess(),
                'is_failed' => $payment->isFailed(),
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status check error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while checking payment status'
            ], 500);
        }
    }

    /**
     * Cancel payment
     */
    public function cancel($orderNumber)
    {
        try {
            $order = Order::where('order_number', $orderNumber)->firstOrFail();
            
            // Check if user owns this order (if logged in)
            if (auth()->check() && $order->user_id !== auth()->id()) {
                return redirect()->back()->with('error', 'Unauthorized access.');
            }

            $payment = $order->payment;

            if (!$payment || !$payment->isPending()) {
                return redirect()->back()->with('error', 'Cannot cancel this payment.');
            }

            // Cancel in Midtrans
            $cancelResponse = $this->midtrans->cancelTransaction($orderNumber);

            if ($cancelResponse['success']) {
                $payment->update(['transaction_status' => 'cancel']);
                $order->update(['status' => 'cancelled']);

                return redirect()->route('home')->with('success', 'Payment cancelled successfully.');
            }

            return redirect()->back()->with('error', 'Failed to cancel payment.');

        } catch (\Exception $e) {
            Log::error('Payment cancellation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while cancelling payment.');
        }
    }
}
