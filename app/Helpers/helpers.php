<?php

use App\Helpers\ImageHelper;

if (!function_exists('image_url')) {
    /**
     * Get image URL with proper handling for different environments
     * 
     * @param string|null $path
     * @param string $disk
     * @return string
     */
    function image_url(?string $path, string $disk = 'public'): string
    {
        try {
            return ImageHelper::getImageUrl($path, $disk);
        } catch (\Throwable $e) {
            \Log::error('image_url helper error', ['error' => $e->getMessage(), 'path' => $path]);
            return asset('images/placeholder-product.svg');
        }
    }
}

if (!function_exists('product_image_url')) {
    /**
     * Get product image URL
     * 
     * @param \App\Models\Product $product
     * @param string $type
     * @return string
     */
    function product_image_url($product, string $type = 'front'): string
    {
        try {
            return ImageHelper::getProductImageUrl($product, $type);
        } catch (\Throwable $e) {
            \Log::error('product_image_url helper error', ['error' => $e->getMessage()]);
            return asset('images/placeholder-product.svg');
        }
    }
}

if (!function_exists('category_image_url')) {
    /**
     * Get category image URL
     * 
     * @param \App\Models\Category $category
     * @return string
     */
    function category_image_url($category): string
    {
        try {
            return ImageHelper::getCategoryImageUrl($category);
        } catch (\Throwable $e) {
            \Log::error('category_image_url helper error', ['error' => $e->getMessage()]);
            return asset('images/placeholder-product.svg');
        }
    }
}

if (!function_exists('brand_logo_url')) {
    /**
     * Get brand logo URL
     * 
     * @param \App\Models\Brand $brand
     * @return string
     */
    function brand_logo_url($brand): string
    {
        try {
            return ImageHelper::getBrandLogoUrl($brand);
        } catch (\Throwable $e) {
            \Log::error('brand_logo_url helper error', ['error' => $e->getMessage()]);
            return asset('images/placeholder-product.svg');
        }
    }
}

if (!function_exists('banner_image_url')) {
    /**
     * Get banner image URL
     * 
     * @param \App\Models\Banner $banner
     * @return string
     */
    function banner_image_url($banner): string
    {
        try {
            return ImageHelper::getBannerImageUrl($banner);
        } catch (\Throwable $e) {
            \Log::error('banner_image_url helper error', ['error' => $e->getMessage()]);
            return asset('images/placeholder-product.svg');
        }
    }
}
