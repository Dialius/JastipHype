<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['brand', 'category'])
            ->where('is_active', true);

        // Category filter (Radio button - single choice)
        $selectedCategory = null;
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
            $selectedCategory = Category::find($request->category);
        }

        // Brand filter (Checkboxes - multiple)
        if ($request->has('brands') && !empty($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }

        // New Arrivals filter (last 30 days)
        if ($request->filled('new')) {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        // Discount filter (products with sale_price)
        if ($request->filled('discount')) {
            $query->whereNotNull('sale_price')
                  ->where('sale_price', '>', 0)
                  ->whereRaw('sale_price < price');
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Size filter (JSON search)
        if ($request->has('sizes') && !empty($request->sizes)) {
            $sizes = $request->sizes;
            $query->where(function($q) use ($sizes) {
                foreach ($sizes as $size) {
                    $q->orWhereJsonContains('sizes', $size);
                }
            });
        }

        // Availability filter (Radio button - single choice)
        if ($request->filled('availability') && $request->availability != 'all') {
            $availability = $request->availability;
            
            if ($availability == 'in_stock') {
                $query->where('stock', '>', 10);
            } elseif ($availability == 'limited') {
                $query->where('is_limited_edition', true);
            } elseif ($availability == 'low_stock') {
                $query->whereBetween('stock', [1, 10]);
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'featured');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default: // featured
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('is_limited_edition', 'desc')
                      ->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Get filter options
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        
        // Get price range
        $minPrice = Product::where('is_active', true)->min('price');
        $maxPrice = Product::where('is_active', true)->max('price');

        return view('products.index', compact(
            'products', 
            'categories', 
            'brands',
            'minPrice',
            'maxPrice',
            'selectedCategory'
        ));
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['brand', 'category', 'productImages'])
            ->where('is_active', true)
            ->firstOrFail();
        
        // Get related products (same category, exclude current product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['brand', 'productImages'])
            ->inRandomOrder()
            ->limit(8)
            ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }
}
