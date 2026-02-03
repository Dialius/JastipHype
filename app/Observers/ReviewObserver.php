<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        // Update product average rating
        $this->updateProductRating($review->product_id);
        
        // Send notification to admin about new review
        // Mail::to(config('mail.admin_email'))->queue(new NewReviewNotification($review));
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        // Update product average rating if rating changed
        if ($review->isDirty('rating')) {
            $this->updateProductRating($review->product_id);
        }
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        // Update product average rating
        $this->updateProductRating($review->product_id);
    }

    /**
     * Update product average rating and review count
     */
    protected function updateProductRating($productId)
    {
        $product = \App\Models\Product::find($productId);
        if ($product) {
            $reviews = $product->reviews();
            $avgRating = $reviews->avg('rating');
            $reviewCount = $reviews->count();
            
            $product->update([
                'rating' => round($avgRating ?? 0, 1),
                'review_count' => $reviewCount
            ]);
        }
    }

    /**
     * Handle the Review "restored" event.
     */
    public function restored(Review $review): void
    {
        //
    }

    /**
     * Handle the Review "force deleted" event.
     */
    public function forceDeleted(Review $review): void
    {
        //
    }
}
