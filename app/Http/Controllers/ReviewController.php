<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:100',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Check if user already reviewed this product
        if ($product->hasUserReviewed(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product.'
            ], 422);
        }

        // Create review
        $review = $product->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'verified_purchase' => false, // TODO: Check if user actually bought this
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!',
            'review' => $review->load('user'),
            'averageRating' => $product->averageRating(),
            'reviewsCount' => $product->reviewsCount(),
        ]);
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $productId = $review->product_id;
        $review->delete();

        $product = Product::find($productId);

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully!',
            'averageRating' => $product->averageRating(),
            'reviewsCount' => $product->reviewsCount(),
        ]);
    }
}
