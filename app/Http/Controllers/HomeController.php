<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        // Active banners for hero carousel (only type 'hero')
        $banners = Banner::active()
            ->ofType('hero')
            ->with(['product.brand', 'product.productImages'])
            ->ordered()
            ->get();

        // Featured drop for hero section (fallback if no banners)
        $featuredDrop = Product::where('is_featured', true)
            ->where('is_limited_edition', true)
            ->with(['brand', 'productImages'])
            ->first();

        // Categories for featured categories section (4 specific categories)
        $categories = Category::whereIn('slug', ['accessories', 'clothing', 'hoodies', 'sneakers'])
            ->active()
            ->ordered()
            ->get();

        // New arrivals
        $newArrivals = Product::active()
            ->with(['brand', 'productImages'])
            ->latest()
            ->limit(8)
            ->get();

        // Limited edition showcase - use banner with type "limited" if available
        $limitedBanner = Banner::active()
            ->ofType('limited')
            ->with(['product.brand', 'product.productImages'])
            ->first();
        
        // If limited banner exists, use its product, otherwise get random limited product
        $limitedShowcase = $limitedBanner && $limitedBanner->product 
            ? $limitedBanner->product 
            : Product::limited()
                ->active()
                ->with(['brand', 'productImages'])
                ->inRandomOrder()
                ->first();

        // Featured brands
        $featuredBrands = Brand::featured()
            ->limit(6)
            ->get();

        return view('home.index', compact(
            'banners',
            'featuredDrop',
            'categories',
            'newArrivals',
            'limitedShowcase',
            'limitedBanner',
            'featuredBrands'
        ));
    }
}
