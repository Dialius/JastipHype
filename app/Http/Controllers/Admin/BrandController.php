<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BrandService;
use App\Repositories\Contracts\BrandRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    protected $brandService;
    protected $brandRepository;

    public function __construct(
        BrandService $brandService,
        BrandRepositoryInterface $brandRepository
    ) {
        $this->brandService = $brandService;
        $this->brandRepository = $brandRepository;
    }

    /**
     * Display a listing of brands with statistics
     */
    public function index()
    {
        $brands = $this->brandRepository->all();
        
        // Get statistics for each brand
        foreach ($brands as $brand) {
            $brand->product_count = $brand->products()->count();
            $brand->total_revenue = $brand->products()
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', 'delivered')
                ->sum(\DB::raw('order_items.quantity * order_items.price'));
        }

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new brand
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created brand
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'slug' => 'nullable|string|unique:brands,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=200,min_height=200,max_width=1000,max_height=1000',
            'status' => 'required|in:active,inactive',
            'display_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('brands', 'public');
            // Remove logo file object from validated data
            unset($validated['logo']);
        }

        $brand = $this->brandService->createBrand($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand created successfully!');
    }

    /**
     * Display the specified brand
     */
    public function show($id)
    {
        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            return redirect()
                ->route('admin.brands.index')
                ->with('error', 'Brand not found!');
        }

        // Get brand statistics
        $brand->product_count = $brand->products()->count();
        $brand->total_revenue = $brand->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->sum(\DB::raw('order_items.quantity * order_items.price'));

        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified brand
     */
    public function edit($id)
    {
        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            return redirect()
                ->route('admin.brands.index')
                ->with('error', 'Brand not found!');
        }

        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified brand
     */
    public function update(Request $request, $id)
    {
        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            return redirect()
                ->route('admin.brands.index')
                ->with('error', 'Brand not found!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'slug' => 'nullable|string|unique:brands,slug,' . $id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:min_width=200,min_height=200,max_width=1000,max_height=1000',
            'status' => 'required|in:active,inactive',
            'display_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'remove_logo' => 'nullable|boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured');

        // Handle logo removal
        if ($request->has('remove_logo') && $request->remove_logo) {
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
                $validated['logo_path'] = null;
            }
        }

        // Handle logo upload (replace existing)
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('brands', 'public');
            // Remove logo file object from validated data
            unset($validated['logo']);
        }

        $this->brandService->updateBrand($id, $validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand updated successfully!');
    }

    /**
     * Remove the specified brand
     */
    public function destroy($id)
    {
        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            return redirect()
                ->route('admin.brands.index')
                ->with('error', 'Brand not found!');
        }

        // Check if brand has products
        $productCount = $brand->products()->count();
        if ($productCount > 0) {
            return redirect()
                ->route('admin.brands.index')
                ->with('error', "Cannot delete brand. It has {$productCount} products associated with it.");
        }

        // Delete logo
        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $this->brandService->deleteBrand($id);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand deleted successfully!');
    }

    /**
     * Update brand display order (drag-and-drop)
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:brands,id',
            'orders.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['orders'] as $orderData) {
            $this->brandService->updateBrand($orderData['id'], [
                'display_order' => $orderData['order']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Brand order updated successfully!'
        ]);
    }

    /**
     * Toggle brand status
     */
    public function toggleStatus($id)
    {
        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            return response()->json(['error' => 'Brand not found!'], 404);
        }

        $newStatus = $brand->status === 'active' ? 'inactive' : 'active';
        
        $this->brandService->updateBrand($id, [
            'status' => $newStatus
        ]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Brand status updated successfully!'
        ]);
    }
}
