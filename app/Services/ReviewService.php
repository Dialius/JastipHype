<?php

namespace App\Services;

use App\Repositories\Contracts\ReviewRepositoryInterface;

class ReviewService
{
    protected $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Get reviews with filters
     */
    public function getWithFilters(array $filters, $perPage = 15)
    {
        return $this->reviewRepository->getWithFilters($filters, $perPage);
    }

    /**
     * Get pending reviews
     */
    public function getPending()
    {
        return $this->reviewRepository->getPending();
    }

    /**
     * Approve review
     */
    public function approve($id)
    {
        return $this->reviewRepository->approve($id);
    }

    /**
     * Reject review
     */
    public function reject($id, $reason = null)
    {
        return $this->reviewRepository->reject($id, $reason);
    }

    /**
     * Delete review
     */
    public function delete($id)
    {
        return $this->reviewRepository->delete($id);
    }

    /**
     * Get review statistics
     */
    public function getStatistics()
    {
        return $this->reviewRepository->getStatistics();
    }
}
