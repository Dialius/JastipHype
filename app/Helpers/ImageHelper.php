<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL with proper handling for different environments
     * 
     * @param string|null $path
     * @param string|null $disk
     * @return string
     */
    public static function getImageUrl(?string $path, ?string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');
        try {
            if (empty($path)) {
                return self::getPlaceholderUrl();
            }
            
            // If it's already a full URL, return as is
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }
            
            // Clean path and generate URL
            $cleanPath = ltrim($path, '/');
            
            // Use asset helper for public disk (now stored in public/uploads)
            return asset('uploads/' . $cleanPath);
            
        } catch (\Throwable $e) {
            \Log::error('ImageHelper::getImageUrl error', [
                'path' => $path,
                'disk' => $disk,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return self::getPlaceholderUrl();
        }
    }
    
    /**
     * Get product image URL
     * 
     * @param \App\Models\Product $product
     * @param string $type
     * @return string
     */
    public static function getProductImageUrl($product, string $type = 'front'): string
    {
        if (!$product) {
            return self::getPlaceholderUrl();
        }
        
        // Get image by type or first available
        $image = $product->productImages->where('type', $type)->first() 
              ?? $product->productImages->first();
        
        if (!$image) {
            return self::getPlaceholderUrl();
        }
        
        return self::getImageUrl($image->image_path);
    }
    
    /**
     * Get category image URL
     * 
     * @param \App\Models\Category $category
     * @return string
     */
    public static function getCategoryImageUrl($category): string
    {
        if (!$category || !$category->image) {
            return self::getPlaceholderUrl();
        }
        
        return self::getImageUrl($category->image);
    }
    
    /**
     * Get brand logo URL
     * 
     * @param \App\Models\Brand $brand
     * @return string
     */
    public static function getBrandLogoUrl($brand): string
    {
        if (!$brand || !$brand->logo_path) {
            return self::getPlaceholderUrl();
        }
        
        return self::getImageUrl($brand->logo_path);
    }
    
    /**
     * Get banner image URL
     * 
     * @param \App\Models\Banner $banner
     * @return string
     */
    public static function getBannerImageUrl($banner): string
    {
        if (!$banner) {
            return self::getPlaceholderUrl();
        }
        
        // Check banner's own image first
        if ($banner->image_path) {
            return self::getImageUrl($banner->image_path);
        }
        
        // Fallback to product image if banner is linked to product
        if ($banner->product_id && $banner->product) {
            return self::getProductImageUrl($banner->product);
        }
        
        return self::getPlaceholderUrl();
    }
    
    /**
     * Get placeholder image URL
     * 
     * @return string
     */
    public static function getPlaceholderUrl(): string
    {
        return asset('images/placeholder-product.svg');
    }
    
    /**
     * Get multiple product images
     * 
     * @param \App\Models\Product $product
     * @return array
     */
    public static function getProductImages($product): array
    {
        if (!$product || !$product->productImages) {
            return [self::getPlaceholderUrl()];
        }
        
        return $product->productImages->map(function($image) {
            return self::getImageUrl($image->image_path);
        })->toArray();
    }
}
