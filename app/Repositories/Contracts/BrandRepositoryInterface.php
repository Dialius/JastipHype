<?php

namespace App\Repositories\Contracts;

interface BrandRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get brands with product count
     */
    public function getWithProductCount();

    /**
     * Get brand statistics (product count, revenue)
     */
    public function getStatistics($brandId);

    /**
     * Update brand order
     */
    public function updateOrder(array $orderData);

    /**
     * Check if brand has products
     */
    public function hasProducts($brandId);
}
