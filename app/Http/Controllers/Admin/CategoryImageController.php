<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CategoryImageController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    /**
     * Show the form for editing category images.
     */
    public function edit()
    {
        // Get the 4 main categories for Shop by Category section
        $categories = Category::whereIn('slug', ['accessories', 'clothing', 'hoodies', 'sneakers'])
            ->orderBy('order')
            ->get();
        
        // If categories don't exist, create them
        if ($categories->count() < 4) {
            $this->ensureCategoriesExist();
            $categories = Category::whereIn('slug', ['accessories', 'clothing', 'hoodies', 'sneakers'])
                ->orderBy('order')
                ->get();
        }
        
        return view('admin.categories.images', compact('categories'));
    }

    /**
     * Update category images.
     */
    public function update(Request $request)
    {
        $request->validate([
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $updated = 0;
            
            foreach ($request->input('categories', []) as $categoryId => $data) {
                $category = Category::findOrFail($categoryId);
                
                // Check if there's a file for this category
                if ($request->hasFile("categories.{$categoryId}.image")) {
                    // Delete old image if exists
                    if ($category->image) {
                        $this->fileUploadService->delete($category->image, 'public');
                    }
                    
                    // Store new image
                    $file = $request->file("categories.{$categoryId}.image");
                    $path = $this->fileUploadService->upload($file, 'categories', 'public');
                    
                    $category->image = $path;
                    $category->save();
                    $updated++;
                }
            }
            
            $message = $updated > 0 
                ? "Successfully updated {$updated} category image(s)." 
                : "No images were updated.";
            
            return redirect()
                ->route('admin.categories.images.edit')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Category images update failed: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Failed to update category images: ' . $e->getMessage());
        }
    }

    /**
     * Ensure the 4 main categories exist.
     */
    private function ensureCategoriesExist()
    {
        $categories = [
            ['name' => 'Accessories', 'slug' => 'accessories', 'order' => 1],
            ['name' => 'Clothing', 'slug' => 'clothing', 'order' => 2],
            ['name' => 'Hoodies', 'slug' => 'hoodies', 'order' => 3],
            ['name' => 'Sneakers', 'slug' => 'sneakers', 'order' => 4],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                [
                    'name' => $categoryData['name'],
                    'order' => $categoryData['order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
