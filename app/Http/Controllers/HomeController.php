<?php

namespace App\Http\Controllers;

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
        // Featured drop for hero section
        $featuredDrop = Product::where('is_featured', true)
            ->where('is_limited_edition', true)
            ->with(['brand', 'productImages'])
            ->first();

        // Categories for featured categories section
        $categories = Category::active()
            ->ordered()
            ->limit(4)
            ->get();

        // New arrivals
        $newArrivals = Product::active()
            ->with(['brand', 'productImages'])
            ->latest()
            ->limit(8)
            ->get();

        // Limited edition showcase
        $limitedShowcase = Product::limited()
            ->active()
            ->with(['brand', 'productImages'])
            ->inRandomOrder()
            ->first();

        // Featured brands
        $featuredBrands = Brand::featured()
            ->limit(6)
            ->get();

        return view('home.index', compact(
            'featuredDrop',
            'categories',
            'newArrivals',
            'limitedShowcase',
            'featuredBrands'
        ));
    }
}
