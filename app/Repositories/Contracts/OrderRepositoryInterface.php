<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Filter orders by status
     */
    public function filterByStatus($status);

    /**
     * Filter orders by date range
     */
    public function filterByDateRange($startDate, $endDate);

    /**
     * Get recent orders
     */
    public function getRecent($limit = 10);

    /**
     * Get orders with filters
     */
    public function getWithFilters(array $filters, $perPage = 15);

    /**
     * Get order statistics
     */
    public function getStatistics();

    /**
     * Get revenue by date range
     */
    public function getRevenue($startDate, $endDate);
}
