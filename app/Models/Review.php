<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'comment',
        'verified_purchase',
    ];

    protected $casts = [
        'rating' => 'integer',
        'verified_purchase' => 'boolean',
    ];

    /**
     * Get the user that wrote the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product being reviewed
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get formatted date (e.g., "18 Jan 2026 | Variant: L")
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->format('d M Y') . ' | Variant: L';
    }

    /**
     * Get user's first letter for avatar
     */
    public function getUserInitialAttribute(): string
    {
        return strtoupper(substr($this->user->name, 0, 1));
    }

    /**
     * Get staff response for this review
     */
    public function response()
    {
        return $this->hasOne(ReviewResponse::class);
    }

    /**
     * Get images for this review
     */
    public function images()
    {
        return $this->hasMany(ReviewImage::class)->orderBy('order');
    }
}
