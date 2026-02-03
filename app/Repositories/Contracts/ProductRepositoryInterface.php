<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search products by name or SKU
     */
    public function search($query);

    /**
     * Filter products by brand, category, status
     */
    public function filter(array $filters);

    /**
     * Get low stock products
     */
    public function getLowStock($threshold = 10);

    /**
     * Get products with pagination, search, and filters
     */
    public function getWithFilters(array $filters, $perPage = 15);

    /**
     * Get active products
     */
    public function getActive();

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(array $ids, $status);

    /**
     * Count total products
     */
    public function count();
}
