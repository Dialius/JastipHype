<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.brand', 'product.productImages')
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle product in wishlist (add/remove).
     */
    public function toggle(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add items to wishlist'
            ], 401);
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            // Remove from wishlist
            $wishlist->delete();
            
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Removed from wishlist',
                'in_wishlist' => false
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to wishlist',
                'in_wishlist' => true
            ]);
        }
    }

    /**
     * Check if product is in user's wishlist.
     */
    public function check(Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['in_wishlist' => false]);
        }

        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        return response()->json(['in_wishlist' => $inWishlist]);
    }
}
