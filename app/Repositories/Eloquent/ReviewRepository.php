<?php

namespace App\Repositories\Eloquent;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        return $this->model->with(['user', 'product.productImages', 'product.brand', 'product.category', 'images', 'response.user'])->find($id);
    }

    public function getWithFilters(array $filters, $perPage = 15)
    {
        $query = $this->model->with(['user', 'product.productImages', 'images']);

        // Rating filter
        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        // Product filter
        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function getPending()
    {
        // Reviews don't have status, return empty collection
        return collect();
    }

    public function approve($id)
    {
        // Reviews don't have status column, they're approved by default
        // This method can be used for future implementation
        return $this->find($id);
    }

    public function reject($id, $reason = null)
    {
        // Reviews don't have status column
        // For now, we can soft delete to "reject"
        return $this->delete($id);
    }

    public function getStatistics()
    {
        return [
            'total' => $this->model->count(),
            'pending' => 0, // No status column
            'approved' => $this->model->count(),
            'rejected' => 0, // No status column
            'average_rating' => round($this->model->avg('rating'), 1),
        ];
    }
}
