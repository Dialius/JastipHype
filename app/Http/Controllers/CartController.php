<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private function getCart()
    {
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'session_id' => Session::getId()
                ]);
            }
            return $cart;
        }

        $sessionId = Session::getId();
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $sessionId
            ]);
        }
        
        return $cart;
    }

    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product')->get();
        
        // Calculate subtotal
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        // Get related products for cross-sell (Random for now)
        $relatedProducts = Product::inRandomOrder()->take(4)->get();

        return view('cart.index', compact('cartItems', 'subtotal', 'relatedProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'required|string'
        ]);

        $cart = $this->getCart();
        
        // Check if item exists
        $existingItem = $cart->items()
            ->where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'size' => $request->size
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cartCount' => $cart->items()->sum('quantity')
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        $item = $cart->items()->where('id', $itemId)->firstOrFail();
        
        $item->update([
            'quantity' => $request->quantity
        ]);

        if ($request->wantsJson()) {
            $cartItems = $cart->items()->with('product')->get();
            $subtotal = $cartItems->sum(fn($i) => $i->quantity * $i->product->price);
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'item_total' => $item->quantity * $item->product->price,
                'subtotal' => $subtotal,
                'cartCount' => $cart->items()->sum('quantity')
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function destroy($itemId)
    {
        $cart = $this->getCart();
        $item = $cart->items()->where('id', $itemId)->firstOrFail();
        $item->delete();

        if (request()->wantsJson()) {
            $cartItems = $cart->items()->with('product')->get();
            $subtotal = $cartItems->sum(fn($i) => $i->quantity * $i->product->price);
            
            return response()->json([
                'success' => true,
                'message' => 'Item removed',
                'subtotal' => $subtotal,
                'cartCount' => $cart->items()->sum('quantity'),
                'empty' => $cartItems->isEmpty()
            ]);
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
    public function miniCart()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product.brand', 'product.productImages')->latest()->take(5)->get(); // Limit to 5 recent items
        
        $subtotal = $cart->items->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        $view = view('cart.partials.mini-cart', compact('cartItems', 'subtotal'))->render();

        return response()->json([
            'html' => $view,
            'count' => $cart->items()->sum('quantity')
        ]);
    }
}
