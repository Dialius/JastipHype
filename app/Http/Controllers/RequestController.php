<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Brand;
use App\Models\Category;

class RequestController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('request.index', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'product_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'budget' => 'nullable|numeric|min:0|max:999999999999',
            'url' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // TODO: Store in database or send email
        // For now, we'll just return success
        
        return back()->with('success', 'Request submitted successfully! We will contact you soon.');
    }
}
