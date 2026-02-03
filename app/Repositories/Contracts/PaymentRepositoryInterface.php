<?php

namespace App\Repositories\Contracts;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get payments with filters
     */
    public function getWithFilters(array $filters, $perPage = 15);

    /**
     * Get payment method distribution
     */
    public function getPaymentMethodDistribution();

    /**
     * Get payment statistics
     */
    public function getStatistics();

    /**
     * Sync payment status from Midtrans
     */
    public function syncStatus($id);
}
