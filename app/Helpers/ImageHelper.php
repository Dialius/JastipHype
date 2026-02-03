<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL with proper handling for different environments
     * 
     * @param string|null $path
     * @param string $disk
     * @return string
     */
    public static function getImageUrl(?string $path, string $disk = 'public'): string
    {
        if (empty($path)) {
            return self::getPlaceholderUrl();
        }
        
        // If it's already a full URL, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Check if running in serverless environment
        if (self::isServerless()) {
            // In serverless, use asset() for public files
            // Remove 'storage/' prefix if exists
            $cleanPath = str_replace('storage/', '', $path);
            return asset('storage/' . $cleanPath);
        }
        
        // For traditional hosting, use Storage::url()
        try {
            return Storage::disk($disk)->url($path);
        } catch (\Exception $e) {
            \Log::warning('Failed to get storage URL', [
                'path' => $path,
                'disk' => $disk,
                'error' => $e->getMessage(),
            ]);
            
            // Fallback to asset()
            return asset('storage/' . $path);
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
     * Check if running in serverless environment
     * 
     * @return bool
     */
    protected static function isServerless(): bool
    {
        return !empty(getenv('VERCEL_ENV')) || 
               !empty(getenv('AWS_LAMBDA_FUNCTION_NAME')) ||
               !empty(getenv('NETLIFY'));
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
