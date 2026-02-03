<?php

namespace App\Services;

use App\Repositories\Contracts\BannerRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    protected $bannerRepository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * Create a new banner
     */
    public function createBanner(array $data, $image = null)
    {
        // Handle image upload
        if ($image) {
            $data['image_path'] = $this->uploadImage($image);
        }

        // Set default order (last position)
        if (!isset($data['order'])) {
            $lastBanner = $this->bannerRepository->getLastOrder();
            $data['order'] = $lastBanner ? $lastBanner->order + 1 : 1;
        }

        return $this->bannerRepository->create($data);
    }

    /**
     * Update a banner
     */
    public function updateBanner($banner, array $data, $image = null)
    {
        // Handle image upload
        if ($image) {
            // Delete old image
            if ($banner->image_path) {
                $this->deleteImage($banner->image_path);
            }
            $data['image_path'] = $this->uploadImage($image);
        }

        return $this->bannerRepository->update($banner->id, $data);
    }

    /**
     * Delete a banner
     */
    public function deleteBanner($banner)
    {
        // Delete image
        if ($banner->image_path) {
            $this->deleteImage($banner->image_path);
        }

        return $this->bannerRepository->delete($banner->id);
    }

    /**
     * Get active banners
     */
    public function getActive()
    {
        return $this->bannerRepository->getActive();
    }

    /**
     * Get banners by type
     */
    public function getByType($type)
    {
        return $this->bannerRepository->getByType($type);
    }

    /**
     * Update banner order
     */
    public function updateOrder(array $orderData)
    {
        return $this->bannerRepository->updateOrder($orderData);
    }

    /**
     * Toggle banner status
     */
    public function toggleStatus($id)
    {
        return $this->bannerRepository->toggleStatus($id);
    }

    /**
     * Upload banner image
     */
    protected function uploadImage($image)
    {
        $path = $image->store('banners', 'public');
        return $path;
    }

    /**
     * Delete banner image
     */
    protected function deleteImage($imagePath)
    {
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
