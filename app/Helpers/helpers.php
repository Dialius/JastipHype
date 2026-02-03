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
        return ImageHelper::getImageUrl($path, $disk);
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
        return ImageHelper::getProductImageUrl($product, $type);
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
        return ImageHelper::getCategoryImageUrl($category);
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
        return ImageHelper::getBrandLogoUrl($brand);
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
        return ImageHelper::getBannerImageUrl($banner);
    }
}
