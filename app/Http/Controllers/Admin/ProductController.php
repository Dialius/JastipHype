<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $fileUploadService;

    public function __construct(
        ProductService $productService,
        ProductRepositoryInterface $productRepository,
        \App\Services\FileUploadService $fileUploadService
    ) {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'is_active' => $request->input('is_active'),
            'stock_status' => $request->input('stock_status'), // low, out, in_stock
        ];

        // Use pagination instead of loading all products
        $query = \App\Models\Product::query()
            ->with(['category', 'brand', 'productImages' => function($q) {
                $q->where('is_primary', true)->orWhere('order', 0)->limit(1);
            }]);

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sku', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['stock_status'])) {
            switch ($filters['stock_status']) {
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'low':
                    $query->where('stock', '>', 0)->where('stock', '<=', 10);
                    break;
                case 'in_stock':
                    $query->where('stock', '>', 10);
                    break;
            }
        }

        // Paginate results (20 per page)
        $products = $query->latest()->paginate(20)->withQueryString();
        
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'filters'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.front' => 'nullable|array',
            'images.front.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.back' => 'nullable|array',
            'images.back.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.detail' => 'nullable|array',
            'images.detail.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.other' => 'nullable|array',
            'images.other.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Create product first
        $product = $this->productRepository->create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'sku' => $validated['sku'],
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'weight' => $validated['weight'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        // Handle image uploads with categories
        $imageTypes = ['front', 'back', 'detail', 'other'];
        $order = 0;
        
        foreach ($imageTypes as $type) {
            if ($request->hasFile("images.{$type}")) {
                foreach ($request->file("images.{$type}") as $image) {
                    $path = $this->fileUploadService->upload($image, 'products');
                    
                    \App\Models\ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'type' => $type,
                        'order' => $order,
                        'is_primary' => $order === 0, // First image is primary
                    ]);
                    
                    $order++;
                }
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Product not found!');
        }

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Product not found!');
        }

        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Product not found!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $id,
            'sku' => 'required|string|unique:products,sku,' . $id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle existing images
        $existingImages = json_decode($product->images, true) ?? [];

        // Remove selected images
        if ($request->has('remove_images')) {
            foreach ($request->input('remove_images') as $imagePath) {
                if (($key = array_search($imagePath, $existingImages)) !== false) {
                    $this->fileUploadService->delete($imagePath);
                    unset($existingImages[$key]);
                }
            }
            $existingImages = array_values($existingImages);
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $this->fileUploadService->upload($image, 'products');
                $existingImages[] = $path;
            }
        }

        $validated['images'] = json_encode($existingImages);
        $validated['is_active'] = $request->has('is_active');

        $this->productService->updateProduct($id, $validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Product not found!');
        }

        // Delete product images
        $images = json_decode($product->images, true) ?? [];
        foreach ($images as $imagePath) {
            $this->fileUploadService->delete($imagePath);
        }

        $this->productService->deleteProduct($id);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found!'], 404);
        }

        $this->productService->updateProduct($id, [
            'is_active' => !$product->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => !$product->is_active,
            'message' => 'Product status updated successfully!'
        ]);
    }

    /**
     * Bulk delete products
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        foreach ($validated['product_ids'] as $productId) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                // Delete images
                $images = json_decode($product->images, true) ?? [];
                $this->fileUploadService->deleteMultiple($images, 'public');
                $this->productService->deleteProduct($productId);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', count($validated['product_ids']) . ' products deleted successfully!');
    }

    /**
     * Update stock for a product
     */
    public function updateStock(Request $request, $id)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $product = $this->productRepository->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found!'], 404);
        }

        $this->productService->updateProduct($id, [
            'stock' => $validated['stock']
        ]);

        return response()->json([
            'success' => true,
            'stock' => $validated['stock'],
            'message' => 'Stock updated successfully!'
        ]);
    }
}
