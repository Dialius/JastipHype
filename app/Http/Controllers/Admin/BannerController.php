<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BannerService;
use App\Repositories\Contracts\BannerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    public function __construct(
        protected BannerService $bannerService,
        protected BannerRepositoryInterface $bannerRepository
    ) {}

    /**
     * Display a listing of banners.
     */
    public function index()
    {
        $banners = $this->bannerRepository->getAllOrdered();
        
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        $types = [
            'hero' => ['width' => 1920, 'height' => 600],
            'secondary' => ['width' => 1200, 'height' => 400],
            'promo' => ['width' => 800, 'height' => 300],
        ];
        
        // Get products for dropdown
        $products = \App\Models\Product::with('brand')
            ->orderBy('name')
            ->get();
        
        return view('admin.banners.create', compact('types', 'products'));
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:hero,limited',
            'show_countdown' => 'nullable|boolean',
            'countdown_target' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'product_id' => 'nullable|exists:products,id',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:500',
            'link' => 'nullable|url|max:500',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            $banner = $this->bannerService->createBanner($validated, $request->file('image'));
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner created successfully.');
        } catch (\Exception $e) {
            Log::error('Banner creation failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create banner: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit($id)
    {
        $banner = $this->bannerRepository->findById($id);
        
        if (!$banner) {
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Banner not found.');
        }
        
        $types = [
            'hero' => ['width' => 1920, 'height' => 600],
            'secondary' => ['width' => 1200, 'height' => 400],
            'promo' => ['width' => 800, 'height' => 300],
        ];
        
        // Get products for dropdown
        $products = \App\Models\Product::with('brand')
            ->orderBy('name')
            ->get();
        
        return view('admin.banners.edit', compact('banner', 'types', 'products'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, $id)
    {
        $banner = $this->bannerRepository->findById($id);
        
        if (!$banner) {
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Banner not found.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:hero,limited',
            'show_countdown' => 'nullable|boolean',
            'countdown_target' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'product_id' => 'nullable|exists:products,id',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:500',
            'link' => 'nullable|url|max:500',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            $this->bannerService->updateBanner($banner, $validated, $request->file('image'));
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner updated successfully.');
        } catch (\Exception $e) {
            Log::error('Banner update failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update banner: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy($id)
    {
        $banner = $this->bannerRepository->findById($id);
        
        if (!$banner) {
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Banner not found.');
        }

        try {
            $this->bannerService->deleteBanner($banner);
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Banner deletion failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Failed to delete banner: ' . $e->getMessage());
        }
    }

    /**
     * Update banner display order.
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:banners,id',
        ]);

        try {
            $this->bannerService->updateOrder($validated['order']);
            
            return response()->json([
                'success' => true,
                'message' => 'Banner order updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Banner order update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update banner order.',
            ], 500);
        }
    }

    /**
     * Toggle banner status (active/inactive).
     */
    public function toggleStatus($id)
    {
        $banner = $this->bannerRepository->findById($id);
        
        if (!$banner) {
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Banner not found.');
        }

        try {
            $newStatus = $banner->status === 'active' ? 'inactive' : 'active';
            $this->bannerRepository->update($banner->id, ['status' => $newStatus]);
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', "Banner {$newStatus} successfully.");
        } catch (\Exception $e) {
            Log::error('Banner status toggle failed: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.banners.index')
                ->with('error', 'Failed to toggle banner status.');
        }
    }
}
