<?php

namespace App\Services;

use App\Repositories\Contracts\BannerRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    protected $bannerRepository;
    protected $fileUploadService;

    public function __construct(
        BannerRepositoryInterface $bannerRepository,
        FileUploadService $fileUploadService
    ) {
        $this->bannerRepository = $bannerRepository;
        $this->fileUploadService = $fileUploadService;
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
            // Try to delete old image, but don't fail if it doesn't work
            if ($banner->image_path) {
                try {
                    $this->deleteImage($banner->image_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old banner image, continuing with update', [
                        'banner_id' => $banner->id,
                        'image_path' => $banner->image_path,
                        'error' => $e->getMessage()
                    ]);
                }
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
        // Try to delete image, but don't fail if it doesn't work
        // In serverless, files may be ephemeral
        if ($banner->image_path) {
            try {
                $this->deleteImage($banner->image_path);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete banner image, continuing with banner deletion', [
                    'banner_id' => $banner->id,
                    'image_path' => $banner->image_path,
                    'error' => $e->getMessage()
                ]);
            }
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
        return $this->fileUploadService->upload($image, 'banners');
    }

    /**
     * Delete banner image
     */
    protected function deleteImage($imagePath)
    {
        $this->fileUploadService->delete($imagePath);
    }
}
