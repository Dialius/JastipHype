<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
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

        $cartItems = $cart->items()->with('product')->get();
        
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        // Default shipping cost (will be calculated dynamically later)
        $shippingCost = 0;
        $total = $subtotal + $shippingCost;

        // Get User Address if available
        $user = Auth::user();

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'total', 'user'));
    }

    public function process(Request $request)
    {
        // Validation will go here
        
        // Transaction Logic will go here
        
        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }
}
