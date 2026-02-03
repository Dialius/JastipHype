<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'sku',
        'stock',
        'image',
        'images',
        'is_limited_edition',
        'is_featured',
        'is_active',
        'sizes',
        'colors',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_limited_edition' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sizes' => 'array',
        'colors' => 'array',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
       return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the images for the product.
     */
    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    /**
     * Get the primary image for the product.
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include limited edition products.
     */
    public function scopeLimited($query)
    {
        return $query->where('is_limited_edition', true);
    }

    /**
     * Scope a query to only include in-stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Get the final price (sale price if available, otherwise regular price).
     */
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if product is on sale.
     */
    public function getIsOnSaleAttribute()
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Check if product is low in stock.
     */
    public function getIsLowStockAttribute()
    {
        return $this->stock > 0 && $this->stock < 10;
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Get average rating for the product.
     */
    public function averageRating()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get total number of reviews.
     */
    public function reviewsCount()
    {
        return $this->reviews()->count();
    }

    /**
     * Get user's review for this product.
     */
    public function userReview($userId)
    {
        return $this->reviews()->where('user_id', $userId)->first();
    }

    /**
     * Check if user has reviewed this product.
     */
    public function hasUserReviewed($userId)
    {
        return $this->reviews()->where('user_id', $userId)->exists();
    }
}
