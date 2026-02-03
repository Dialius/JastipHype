<?php

namespace App\Services;

use App\Repositories\Contracts\BrandRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandService
{
    protected $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
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
            // Delete old logo
            if ($brand->logo_path) {
                $this->deleteLogo($brand->logo_path);
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

        // Delete logo
        if ($brand->logo_path) {
            $this->deleteLogo($brand->logo_path);
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
        $path = $logo->store('brands', 'public');
        return $path;
    }

    /**
     * Delete brand logo
     */
    protected function deleteLogo($logoPath)
    {
        if (Storage::disk('public')->exists($logoPath)) {
            Storage::disk('public')->delete($logoPath);
        }
    }
}
