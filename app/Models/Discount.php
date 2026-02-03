<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'uses_count',
        'uses_per_customer',
        'start_date',
        'end_date',
        'status',
        'applicable_to',
        'applicable_ids',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'uses_count' => 'integer',
        'uses_per_customer' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'applicable_ids' => 'array',
    ];

    /**
     * Scope to get only active discounts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_uses')
                  ->orWhereRaw('uses_count < max_uses');
            });
    }

    /**
     * Check if discount is valid
     */
    public function isValid()
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        if ($this->max_uses && $this->uses_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Check if discount is applicable to a product
     */
    public function isApplicableToProduct($productId)
    {
        if ($this->applicable_to === 'all') {
            return true;
        }

        if ($this->applicable_to === 'products') {
            return in_array($productId, $this->applicable_ids ?? []);
        }

        if ($this->applicable_to === 'categories') {
            $product = Product::find($productId);
            return $product && in_array($product->category_id, $this->applicable_ids ?? []);
        }

        return false;
    }

    /**
     * Calculate discount amount for given order total
     */
    public function calculateDiscount($orderTotal)
    {
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($orderTotal * $this->value) / 100;
        }

        return $this->value;
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('uses_count');
    }

    /**
     * Get remaining uses
     */
    public function getRemainingUsesAttribute()
    {
        if (!$this->max_uses) {
            return null; // unlimited
        }

        return max(0, $this->max_uses - $this->uses_count);
    }
}
