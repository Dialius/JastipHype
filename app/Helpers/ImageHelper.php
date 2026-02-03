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
            
            // Check if it's external storage (S3, Cloudinary, etc.)
            $isExternal = in_array($disk, ['s3', 'cloudinary', 'gcs']);
            
            try {
                // Always try to get URL from Storage driver first
                // This handles S3/Cloudinary URLs correctly
                $url = Storage::disk($disk)->url($path);
                
                // If we got a valid URL, return it
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    return $url;
                }
            } catch (\Exception $e) {
                // Log error but continue to fallback
                \Log::warning('Failed to get storage URL', [
                    'path' => $path,
                    'disk' => $disk,
                    'error' => $e->getMessage(),
                ]);
            }
            
            // Fallback for local/public disk in serverless
            if (self::isServerless() && !$isExternal) {
                // In serverless local, we can only serve what's in public/storage (build assets)
                // We cannot serve what's in /tmp/storage (uploaded files)
                // So this will likely only work for pre-deployed assets
                $cleanPath = ltrim($path, '/');
                $cleanPath = str_replace('storage/', '', $cleanPath);
                return asset('storage/' . $cleanPath);
            }
            
            // Default fallback
            $cleanPath = ltrim($path, '/');
            return asset('storage/' . $cleanPath);
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
