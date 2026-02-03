<?php

namespace App\Services;

use App\Repositories\Contracts\BrandRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandService
{
    protected $brandRepository;
    protected $fileUploadService;

    public function __construct(
        BrandRepositoryInterface $brandRepository,
        FileUploadService $fileUploadService
    ) {
        $this->brandRepository = $brandRepository;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Create a new brand
     */
    public function create(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle logo upload
        if (isset($data['logo']) && $data['logo']) {
            $data['logo_path'] = $this->uploadLogo($data['logo']);
        }

        return $this->brandRepository->create($data);
    }

    /**
     * Create a new brand (alias for create)
     */
    public function createBrand(array $data)
    {
        return $this->create($data);
    }

    /**
     * Update a brand
     */
    public function update($id, array $data)
    {
        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            return null;
        }

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $brand->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle logo upload
        if (isset($data['logo']) && $data['logo']) {
            // Try to delete old logo, but don't fail if it doesn't work
            if ($brand->logo_path) {
                try {
                    $this->deleteLogo($brand->logo_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old brand logo, continuing with update', [
                        'brand_id' => $id,
                        'logo_path' => $brand->logo_path,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            $data['logo_path'] = $this->uploadLogo($data['logo']);
        }

        return $this->brandRepository->update($id, $data);
    }

    /**
     * Update a brand (alias for update)
     */
    public function updateBrand($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete a brand
     */
    public function delete($id)
    {
        // Check if brand has products
        if ($this->brandRepository->hasProducts($id)) {
            throw new \Exception('Cannot delete brand with existing products');
        }

        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            return false;
        }

        // Try to delete logo, but don't fail if it doesn't work
        if ($brand->logo_path) {
            try {
                $this->deleteLogo($brand->logo_path);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete brand logo, continuing with brand deletion', [
                    'brand_id' => $id,
                    'logo_path' => $brand->logo_path,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $this->brandRepository->delete($id);
    }

    /**
     * Delete a brand (alias for delete)
     */
    public function deleteBrand($id)
    {
        return $this->delete($id);
    }

    /**
     * Get brands with product count
     */
    public function getWithProductCount()
    {
        return $this->brandRepository->getWithProductCount();
    }

    /**
     * Get brand statistics
     */
    public function getStatistics($brandId)
    {
        return $this->brandRepository->getStatistics($brandId);
    }

    /**
     * Update brand order
     */
    public function updateOrder(array $orderData)
    {
        return $this->brandRepository->updateOrder($orderData);
    }

    /**
     * Upload brand logo
     */
    protected function uploadLogo($logo)
    {
        return $this->fileUploadService->upload($logo, 'brands', 'public');
    }

    /**
     * Delete brand logo
     */
    protected function deleteLogo($logoPath)
    {
        $this->fileUploadService->delete($logoPath, 'public');
    }
}
