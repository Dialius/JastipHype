<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DiscountService;
use App\Repositories\Contracts\DiscountRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    public function __construct(
        protected DiscountService $discountService,
        protected DiscountRepositoryInterface $discountRepository,
        protected ProductRepositoryInterface $productRepository,
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * Display a listing of discounts.
     */
    public function index()
    {
        $discounts = $this->discountRepository->paginate(15);
        $statistics = $this->discountService->getStatistics();

        return view('admin.discounts.index', compact('discounts', 'statistics'));
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create()
    {
        $products = $this->productRepository->all();
        $categories = $this->categoryRepository->all();

        return view('admin.discounts.create', compact('products', 'categories'));
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'uses_per_customer' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'applicable_to' => 'required|in:all,products,categories',
            'applicable_ids' => 'nullable|array',
            'applicable_ids.*' => 'integer',
        ]);

        // Additional validation for percentage
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()
                ->withInput()
                ->withErrors(['value' => 'Percentage value cannot exceed 100%']);
        }

        try {
            $this->discountService->create($validated);
            
            return redirect()
                ->route('admin.discounts.index')
                ->with('success', 'Discount created successfully.');
        } catch (\Exception $e) {
            Log::error('Discount creation failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create discount: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit($id)
    {
        $discount = $this->discountRepository->findById($id);
        
        if (!$discount) {
            return redirect()
                ->route('admin.discounts.index')
                ->with('error', 'Discount not found.');
        }

        $products = $this->productRepository->all();
        $categories = $this->categoryRepository->all();

        return view('admin.discounts.edit', compact('discount', 'products', 'categories'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, $id)
    {
        $discount = $this->discountRepository->findById($id);
        
        if (!$discount) {
            return redirect()
                ->route('admin.discounts.index')
                ->with('error', 'Discount not found.');
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code,' . $id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'uses_per_customer' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'applicable_to' => 'required|in:all,products,categories',
            'applicable_ids' => 'nullable|array',
            'applicable_ids.*' => 'integer',
        ]);

        // Additional validation for percentage
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()
                ->withInput()
                ->withErrors(['value' => 'Percentage value cannot exceed 100%']);
        }

        try {
            $this->discountService->update($id, $validated);
            
            return redirect()
                ->route('admin.discounts.index')
                ->with('success', 'Discount updated successfully.');
        } catch (\Exception $e) {
            Log::error('Discount update failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update discount: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy($id)
    {
        $discount = $this->discountRepository->findById($id);
        
        if (!$discount) {
            return redirect()
                ->route('admin.discounts.index')
                ->with('error', 'Discount not found.');
        }

        try {
            $this->discountService->delete($id);
            
            return redirect()
                ->route('admin.discounts.index')
                ->with('success', 'Discount deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Discount deletion failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.discounts.index')
                ->with('error', 'Failed to delete discount.');
        }
    }

    /**
     * Toggle discount status (active/inactive).
     */
    public function toggleStatus($id)
    {
        $discount = $this->discountRepository->findById($id);
        
        if (!$discount) {
            return redirect()
                ->route('admin.discounts.index')
                ->with('error', 'Discount not found.');
        }

        try {
            $newStatus = $discount->status === 'active' ? 'inactive' : 'active';
            $this->discountRepository->update($id, ['status' => $newStatus]);
            
            return redirect()
                ->route('admin.discounts.index')
                ->with('success', "Discount {$newStatus} successfully.");
        } catch (\Exception $e) {
            Log::error('Discount status toggle failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.discounts.index')
                ->with('error', 'Failed to toggle discount status.');
        }
    }
}
