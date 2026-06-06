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
        $order = Order::with(['payment', 'items.product.productImages'])
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

        // If snap_token exists, show Snap UI directly (legacy support)
        if (isset($payment->payment_data['snap_token'])) {
            return view('payment.show', compact('order', 'payment'));
        }

        // Get payment instructions (null if payment_type is 'pending' - user hasn't chosen yet)
        $instructions = ($payment->payment_type && $payment->payment_type !== 'pending')
            ? $payment->getInstructions()
            : null;

        return view('payment.show', compact('order', 'payment', 'instructions'));
    }


    /**
     * Process payment method selection and call Midtrans Core API
     */
    public function process(Request $request, $orderNumber)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'payment_detail' => 'nullable|string',
        ]);

        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $payment = $order->payment;
        if (!$payment || !$payment->isPending()) {
            return redirect()->back()->with('error', 'Invalid payment status.');
        }

        $paymentMethod = $request->input('payment_method');
        $paymentDetail = $request->input('payment_detail', '');

        // Map payment method
        $mapped = $this->midtrans->mapPaymentMethod($paymentMethod, ['detail' => $paymentDetail]);
        $midtransType = $mapped['type'];
        $midtransDetails = $mapped['details'];

        // Prepare items
        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product_name, 0, 50)
            ];
        }
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost'
            ];
        }

        $orderData = [
            'order_id' => $order->order_number,
            'gross_amount' => (int) $order->total,
            'customer' => [
                'first_name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => [
                    'address' => $order->address,
                    'city' => $order->city_id,
                    'postal_code' => $order->postal_code,
                    'country_code' => 'IDN'
                ]
            ],
            'items' => $itemDetails
        ];

        // Call Core API
        $response = $this->midtrans->createTransaction($orderData, $midtransType, $midtransDetails);

        if (!$response['success']) {
            return redirect()->back()->with('error', 'Failed to process payment: ' . $response['message']);
        }

        $data = $response['data'];

        // Extract relevant data based on type
        $paymentData = [];
        $qrCodeUrl = null;
        $deeplinkRedirect = null;
        $paymentCode = null;

        if (isset($data->va_numbers)) {
            $paymentData['va_numbers'] = json_decode(json_encode($data->va_numbers), true);
            $paymentCode = $data->va_numbers[0]->va_number;
        } elseif (isset($data->bill_key)) {
            $paymentData['bill_key'] = $data->bill_key;
            $paymentData['biller_code'] = $data->biller_code;
        } elseif (isset($data->payment_code)) {
            $paymentData['payment_code'] = $data->payment_code;
            $paymentCode = $data->payment_code;
        }

        if (isset($data->actions)) {
            $paymentData['actions'] = json_decode(json_encode($data->actions), true);
            foreach ($data->actions as $action) {
                if ($action->name === 'generate-qr-code') {
                    $qrCodeUrl = $action->url;
                }
                if ($action->name === 'deeplink-redirect') {
                    $deeplinkRedirect = $action->url;
                }
            }
        }
        if (isset($data->qr_string)) {
            $paymentData['qr_string'] = $data->qr_string;
        }

        // Update payment record
        $payment->update([
            'payment_type' => $paymentMethod,
            'payment_code' => $paymentCode,
            'payment_data' => $paymentData,
            'qr_code_url' => $qrCodeUrl,
            'deeplink_redirect' => $deeplinkRedirect,
            'transaction_id' => $data->transaction_id ?? null,
            'transaction_time' => isset($data->transaction_time) ? date('Y-m-d H:i:s', strtotime($data->transaction_time)) : null,
            'expiry_time' => isset($data->expiry_time) ? date('Y-m-d H:i:s', strtotime($data->expiry_time)) : null,
        ]);

        $order->update([
            'payment_method' => $paymentMethod,
            'payment_detail' => $paymentDetail,
        ]);

        return redirect()->route('payment.show', $orderNumber)->with('success', 'Payment processing started.');
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
