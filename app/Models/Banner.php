<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'link',
        'button_text',
        'button_link',
        'product_id',
        'type',
        'show_countdown',
        'countdown_target',
        'order',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'countdown_target' => 'datetime',
        'order' => 'integer',
        'is_active' => 'boolean',
        'show_countdown' => 'boolean',
    ];

    /**
     * Get the product associated with this banner
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get only active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope to get banners by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Check if banner is currently active based on dates
     */
    public function getIsCurrentlyActiveAttribute()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get calculated status (active, scheduled, expired, inactive)
     */
    public function getCalculatedStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        $now = now();

        if ($this->start_date && $this->start_date > $now) {
            return 'scheduled';
        }

        if ($this->end_date && $this->end_date < $now) {
            return 'expired';
        }

        return 'active';
    }
    
    /**
     * Get the display image - from product if linked, otherwise from banner
     */
    public function getDisplayImageAttribute()
    {
        if ($this->product_id && $this->product) {
            return $this->product->primaryImage ? $this->product->primaryImage->image_path : null;
        }
        
        return $this->image_path;
    }
}
