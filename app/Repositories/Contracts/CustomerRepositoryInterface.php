<?php

namespace App\Repositories\Contracts;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search customers by name or email
     */
    public function search($query);

    /**
     * Get customer statistics
     */
    public function getStatistics();

    /**
     * Get customers with filters
     */
    public function getWithFilters(array $filters, $perPage = 15);

    /**
     * Get top customers by spending
     */
    public function getTopCustomers($limit = 10);

    /**
     * Get customer order history
     */
    public function getOrderHistory($customerId);

    /**
     * Get customer spending analytics
     */
    public function getSpendingAnalytics($customerId);
}
