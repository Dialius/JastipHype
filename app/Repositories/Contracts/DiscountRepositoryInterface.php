<?php

namespace App\Repositories\Contracts;

interface DiscountRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get active discounts
     */
    public function getActive();

    /**
     * Find discount by code
     */
    public function findByCode($code);

    /**
     * Check if discount is applicable to product
     */
    public function isApplicableToProduct($discountId, $productId);

    /**
     * Increment discount usage
     */
    public function incrementUsage($id);

    /**
     * Get discount statistics
     */
    public function getStatistics();
}
