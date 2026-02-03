<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')
            ->where(function($query) {
                // Show brands that have either logo or logo_path
                $query->whereNotNull('logo')
                      ->where('logo', '!=', '')
                      ->orWhereNotNull('logo_path')
                      ->where('logo_path', '!=', '');
            })
            ->where('status', 'active') // Only show active brands
            ->orderBy('name')
            ->get();
        
        return view('brands.index', compact('brands'));
    }

    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        
        // Redirect to products page with brand filter
        return redirect()->route('products.index', ['brands' => [$brand->id]]);
    }
}
