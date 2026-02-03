<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Services\MidtransService;

class CheckoutController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function index()
    {
        // Get Cart
        $cart = null;
        if (Auth::check()) {
            $cart = Auth::user()->cart;
        } else {
            $sessionId = Session::getId();
            $cart = Cart::where('session_id', $sessionId)->first();
        }

        if (!$cart || $cart->items->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = $cart->items()->with('product.productImages', 'product.brand')->get();
        
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        // Default shipping cost (will be calculated dynamically later)
        $shippingCost = 0;
        $total = $subtotal + $shippingCost;

        // Get User Address if available
        $user = Auth::user();

        // Get available vouchers
        $availableVouchers = \App\Models\Discount::active()->get();

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'total', 'user', 'availableVouchers'));
    }

    public function process(Request $request)
    {
        // Log incoming request for debugging
        \Log::info('Checkout process started', [
            'has_cart' => Auth::check() ? 'user' : 'guest',
            'session_id' => Session::getId(),
            'request_data' => $request->except(['_token'])
        ]);

        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'province_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required|string|max:10',
            'payment_method' => 'nullable|string', // Optional, will use Snap
            'payment_detail' => 'nullable|string',
        ]);

        \Log::info('Validation passed', ['validated' => $validated]);

        // Get cart
        $cart = null;
        if (Auth::check()) {
            $cart = Auth::user()->cart;
        } else {
            $sessionId = Session::getId();
            $cart = Cart::where('session_id', $sessionId)->first();
        }

        if (!$cart || $cart->items->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);
            $shippingCost = $request->input('shipping_cost', 0);
            $total = $subtotal + $shippingCost;

            // Create order (payment method will be determined by Midtrans)
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'email' => $validated['email'],
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'province_id' => $validated['province_id'],
                'city_id' => $validated['city_id'],
                'postal_code' => $validated['postal_code'],
                'payment_method' => 'snap', // Will be updated after payment
                'payment_detail' => null, // Will be updated after payment
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending'
            ]);

            // Create order items for Midtrans
            $itemDetails = [];
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'size' => $item->size,
                    'price' => $item->product->price,
                    'product_name' => $item->product->name,
                    'subtotal' => $item->product->price * $item->quantity
                ]);

                // Add to Midtrans item details
                $itemDetails[] = [
                    'id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'name' => substr($item->product->name, 0, 50) // Midtrans limit
                ];
            }

            // Add shipping cost as item
            if ($shippingCost > 0) {
                $itemDetails[] = [
                    'id' => 'SHIPPING',
                    'price' => $shippingCost,
                    'quantity' => 1,
                    'name' => 'Shipping Cost'
                ];
            }

            // Prepare Midtrans transaction data (show all payment methods)
            $orderData = [
                'order_id' => $order->order_number,
                'gross_amount' => $total,
                'customer' => [
                    'first_name' => $validated['name'],
                    'last_name' => '', // Optional
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => [
                        'address' => $validated['address'],
                        'city' => $validated['city_id'], // This should ideally be city name
                        'postal_code' => $validated['postal_code'],
                        'country_code' => 'IDN'
                    ]
                ],
                'items' => $itemDetails
                // No enabled_payments - show all available payment methods in Midtrans
            ];

            // Create Midtrans Snap Transaction
            $midtransResponse = $this->midtrans->createSnapTransaction($orderData);

            if (!$midtransResponse['success']) {
                throw new \Exception('Failed to create payment: ' . $midtransResponse['message']);
            }

            // Save payment information (Initial State)
            $paymentData = [
                'order_id' => $order->id,
                'transaction_id' => null, // Will be updated after payment
                'payment_type' => 'snap', // Placeholder
                'gross_amount' => $total,
                'transaction_status' => 'pending',
                'payment_data' => [
                    'snap_token' => $midtransResponse['token'],
                    'redirect_url' => $midtransResponse['redirect_url'] // Now correctly retrieving redirect_url
                ],
            ];

            $payment = Payment::create($paymentData);

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            \Log::info('Order created successfully with Snap', [
                'order_number' => $order->order_number,
                'token' => $midtransResponse['token']
            ]);

            // Redirect to payment page (for Embedded/Popup mode)
            return redirect()->route('payment.show', $order->order_number)
                ->with('success', 'Order created successfully! Please complete payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }

    /**
     * Apply discount/voucher code
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));

        // Find discount
        $discount = \App\Models\Discount::where('code', $code)->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid voucher code.',
            ], 404);
        }

        // Check if discount is valid
        if (!$discount->isValid()) {
            $message = 'This voucher is not available.';
            
            if ($discount->status !== 'active') {
                $message = 'This voucher is inactive.';
            } elseif ($discount->start_date && $discount->start_date > now()) {
                $message = 'This voucher is not yet active.';
            } elseif ($discount->end_date && $discount->end_date < now()) {
                $message = 'This voucher has expired.';
            } elseif ($discount->max_uses && $discount->uses_count >= $discount->max_uses) {
                $message = 'This voucher has reached its usage limit.';
            }
            
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 400);
        }

        // Get cart
        $cart = null;
        if (Auth::check()) {
            $cart = Auth::user()->cart;
        } else {
            $sessionId = Session::getId();
            $cart = \App\Models\Cart::where('session_id', $sessionId)->first();
        }

        if (!$cart || $cart->items->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 400);
        }

        // Calculate subtotal
        $subtotal = $cart->items->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        // Check minimum order amount
        if ($discount->min_order_amount && $subtotal < $discount->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount is Rp ' . number_format($discount->min_order_amount, 0, ',', '.'),
            ], 400);
        }

        // Calculate discount amount
        $discountAmount = $discount->calculateDiscount($subtotal);

        // Store discount in session
        Session::put('discount', [
            'code' => $discount->code,
            'type' => $discount->type,
            'value' => $discount->value,
            'amount' => $discountAmount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher applied successfully!',
            'discount' => [
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value,
                'amount' => $discountAmount,
                'formatted_amount' => 'Rp ' . number_format($discountAmount, 0, ',', '.'),
            ],
        ]);
    }

    /**
     * Remove discount/voucher code
     */
    public function removeDiscount()
    {
        Session::forget('discount');

        return response()->json([
            'success' => true,
            'message' => 'Voucher removed successfully.',
        ]);
    }
}

