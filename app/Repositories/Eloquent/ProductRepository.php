<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function search($query)
    {
        return $this->model
            ->select('id', 'name', 'sku', 'price', 'stock', 'brand_id', 'category_id', 'image', 'is_active')
            ->with(['brand:id,name', 'category:id,name'])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->get();
    }

    public function filter(array $filters)
    {
        $query = $this->model->query();

        if (isset($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }

        return $query->get();
    }

    public function getLowStock($threshold = 10)
    {
        return $this->model
            ->select('id', 'name', 'sku', 'stock', 'brand_id', 'category_id', 'price')
            ->with(['brand:id,name', 'category:id,name'])
            ->where('stock', '>', 0)
            ->where('stock', '<', $threshold)
            ->where('is_active', true)
            ->orderBy('stock')
            ->get();
    }

    public function getWithFilters(array $filters, $perPage = 15)
    {
        $query = $this->model->with(['brand', 'category']);

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Brand filter
        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Status filter
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        // Low stock filter
        if (!empty($filters['low_stock'])) {
            $query->where('stock', '>', 0)->where('stock', '<', 10);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function getActive()
    {
        return $this->model->where('is_active', true)->get();
    }

    public function bulkUpdateStatus(array $ids, $status)
    {
        return $this->model->whereIn('id', $ids)->update(['is_active' => $status]);
    }

    public function paginate($perPage = 15, array $filters = [])
    {
        return $this->getWithFilters($filters, $perPage);
    }

    public function count()
    {
        return $this->model->count();
    }
}
