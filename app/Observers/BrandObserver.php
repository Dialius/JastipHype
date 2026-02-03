<?php

namespace App\Observers;

use App\Models\Brand;

class BrandObserver
{
    /**
     * Handle the Brand "creating" event.
     */
    public function creating(Brand $brand): void
    {
        // Generate slug if not provided
        if (empty($brand->slug)) {
            $brand->slug = \Illuminate\Support\Str::slug($brand->name);
        }
    }

    /**
     * Handle the Brand "deleting" event.
     */
    public function deleting(Brand $brand): bool
    {
        // Prevent deletion if brand has products
        if ($brand->products()->exists()) {
            throw new \Exception('Cannot delete brand with existing products');
        }
        return true;
    }

    /**
     * Handle the Brand "restored" event.
     */
    public function restored(Brand $brand): void
    {
        //
    }

    /**
     * Handle the Brand "force deleted" event.
     */
    public function forceDeleted(Brand $brand): void
    {
        //
    }
}
