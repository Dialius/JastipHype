<?php

namespace App\Repositories\Contracts;

interface BannerRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get active banners
     */
    public function getActive();

    /**
     * Get banners by type
     */
    public function getByType($type);

    /**
     * Update banner order
     */
    public function updateOrder(array $orderData);

    /**
     * Toggle banner status
     */
    public function toggleStatus($id);
}
