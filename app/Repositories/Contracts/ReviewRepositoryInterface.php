<?php

namespace App\Repositories\Contracts;

interface ReviewRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get reviews with filters
     */
    public function getWithFilters(array $filters, $perPage = 15);

    /**
     * Get pending reviews
     */
    public function getPending();

    /**
     * Approve review
     */
    public function approve($id);

    /**
     * Reject review
     */
    public function reject($id, $reason = null);

    /**
     * Get review statistics
     */
    public function getStatistics();
}
