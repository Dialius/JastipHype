<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with hierarchy
     */
    public function index()
    {
        // Get all categories with parent relationship and product count
        $categories = Category::withCount('products')
            ->with('parent')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        // Build tree structure
        $categoryTree = $this->buildTree($categories);

        return view('admin.categories.index', compact('categories', 'categoryTree'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        // Get all categories for parent selection (exclude current if editing)
        $categories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Set parent_id to null if empty
        if (empty($validated['parent_id'])) {
            $validated['parent_id'] = null;
        }

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category
     */
    public function show($id)
    {
        $category = Category::withCount('products')
            ->with(['parent', 'children'])
            ->findOrFail($id);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        // Get all categories for parent selection (exclude current and its descendants)
        $categories = Category::where('id', '!=', $id)
            ->whereNull('parent_id')
            ->orWhere(function($query) use ($id) {
                $query->where('parent_id', '!=', $id)
                      ->where('id', '!=', $id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Prevent circular reference
        if (!empty($validated['parent_id']) && $validated['parent_id'] == $id) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'A category cannot be its own parent!');
        }

        // Check if new parent is a descendant
        if (!empty($validated['parent_id']) && $this->isDescendant($id, $validated['parent_id'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Cannot set a descendant category as parent!');
        }

        // Set parent_id to null if empty
        if (empty($validated['parent_id'])) {
            $validated['parent_id'] = null;
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        // Check if category has products
        if ($category->products_count > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', "Cannot delete category. It has {$category->products_count} products associated with it.");
        }

        // Check if category has children
        $childrenCount = Category::where('parent_id', $id)->count();
        if ($childrenCount > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', "Cannot delete category. It has {$childrenCount} subcategories.");
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Build tree structure from flat categories
     */
    private function buildTree($categories, $parentId = null)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildTree($categories, $category->id);
                if ($children) {
                    $category->children_tree = $children;
                }
                $tree[] = $category;
            }
        }

        return $tree;
    }

    /**
     * Check if a category is a descendant of another
     */
    private function isDescendant($categoryId, $potentialDescendantId)
    {
        $category = Category::find($potentialDescendantId);
        
        while ($category && $category->parent_id) {
            if ($category->parent_id == $categoryId) {
                return true;
            }
            $category = Category::find($category->parent_id);
        }

        return false;
    }
}
