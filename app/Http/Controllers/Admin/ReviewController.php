<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\ReviewResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService,
        protected ReviewRepositoryInterface $reviewRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $filters = [
            'rating' => $request->get('rating'),
            'product_id' => $request->get('product_id'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];

        $reviews = $this->reviewService->getWithFilters($filters, 15);
        $statistics = $this->reviewService->getStatistics();
        
        // Get products for filter dropdown (only active products with reviews)
        $products = \App\Models\Product::whereHas('reviews')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.reviews.index', compact('reviews', 'statistics', 'products', 'filters'));
    }

    /**
     * Display the specified review.
     */
    public function show($id)
    {
        $review = $this->reviewRepository->findById($id);
        
        if (!$review) {
            return redirect()
                ->route('admin.reviews.index')
                ->with('error', 'Review not found.');
        }

        // Load relationships including productImages
        $review->load(['user', 'product.productImages', 'product.brand', 'product.category', 'images', 'response.user']);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review (currently reviews are approved by default).
     */
    public function approve($id)
    {
        $review = $this->reviewRepository->findById($id);
        
        if (!$review) {
            return redirect()
                ->route('admin.reviews.index')
                ->with('error', 'Review not found.');
        }

        try {
            // Since reviews don't have status, this is a placeholder
            // In future, you can add a status column to reviews table
            $this->reviewService->approve($id);
            
            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Review approved successfully.');
        } catch (\Exception $e) {
            Log::error('Review approval failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.reviews.index')
                ->with('error', 'Failed to approve review.');
        }
    }

    /**
     * Reject a review (soft delete).
     */
    public function reject(Request $request, $id)
    {
        $review = $this->reviewRepository->findById($id);
        
        if (!$review) {
            return redirect()
                ->route('admin.reviews.index')
                ->with('error', 'Review not found.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            // Soft delete the review (rejection)
            $this->reviewService->reject($id, $validated['reason'] ?? null);
            
            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Review rejected and hidden successfully.');
        } catch (\Exception $e) {
            Log::error('Review rejection failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.reviews.index')
                ->with('error', 'Failed to reject review.');
        }
    }

    /**
     * Add admin response to a review.
     */
    public function respond(Request $request, $id)
    {
        $review = $this->reviewRepository->findById($id);
        
        if (!$review) {
            return redirect()
                ->route('admin.reviews.index')
                ->with('error', 'Review not found.');
        }

        $validated = $request->validate([
            'response' => 'required|string|max:1000',
        ]);

        try {
            // Check if response already exists
            if ($review->response) {
                // Update existing response
                $review->response->update([
                    'response' => $validated['response'],
                    'user_id' => Auth::id(),
                ]);
            } else {
                // Create new response
                ReviewResponse::create([
                    'review_id' => $review->id,
                    'user_id' => Auth::id(),
                    'response' => $validated['response'],
                ]);
            }
            
            return redirect()
                ->route('admin.reviews.show', $id)
                ->with('success', 'Response added successfully.');
        } catch (\Exception $e) {
            Log::error('Review response failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to add response: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified review from storage (soft delete).
     */
    public function destroy($id)
    {
        $review = $this->reviewRepository->findById($id);
        
        if (!$review) {
            return redirect()
                ->route('admin.reviews.index')
                ->with('error', 'Review not found.');
        }

        try {
            $this->reviewService->delete($id);
            
            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Review deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Review deletion failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.reviews.index')
                ->with('error', 'Failed to delete review.');
        }
    }
}
