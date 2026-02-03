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
            ->with(['brand', 'category', 'productImages'])
            ->where('is_active', true);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhereHas('brand', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

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
        if ($request->filled('discount') || $request->filled('on_sale')) {
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

        // Prepare Header Data
        $pageTitle = 'All Products';
        $pageDescription = 'Discover our latest collection of luxury fashion and accessories';

        if ($request->filled('search')) {
            $pageTitle = 'Search Results';
            $pageDescription = 'Found ' . $products->total() . ' ' . \Illuminate\Support\Str::plural('result', $products->total()) . ' for "<strong>' . e($request->search) . '</strong>"';
        } elseif ($selectedCategory) {
            $pageTitle = $selectedCategory->name;
            $pageDescription = 'Explore our curated selection of ' . strtolower($selectedCategory->name);
        }

        if ($request->ajax()) {
            $view = view('products.partials.product-list', compact('products'))->render();
            return response()->json([
                'html' => $view,
                'hasMore' => $products->hasMorePages(),
                'pageTitle' => $pageTitle,
                'pageDescription' => $pageDescription
            ]);
        }
        
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
            'selectedCategory',
            'pageTitle',
            'pageDescription'
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
        
        // Track recently viewed products
        $recentlyViewed = session('recently_viewed', []);
        
        // Remove if already exists and add to front
        $recentlyViewed = array_diff($recentlyViewed, [$product->id]);
        array_unshift($recentlyViewed, $product->id);
        
        // Keep only last 10 products
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);
        
        session(['recently_viewed' => $recentlyViewed]);
        
        // Get related products (same category, exclude current product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['brand', 'productImages'])
            ->inRandomOrder()
            ->limit(8)
            ->get();
        
        // Get available vouchers
        $availableVouchers = \App\Models\Discount::active()->get();
        
        return view('products.show', compact('product', 'relatedProducts', 'availableVouchers'));
    }

    /**
     * Search autocomplete suggestions API
     */
    public function searchSuggestions(Request $request)
    {
        $search = $request->input('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('brand', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            })
            ->with(['brand', 'productImages'])
            ->limit(8)
            ->get();
        
        return response()->json($products);
    }
}
