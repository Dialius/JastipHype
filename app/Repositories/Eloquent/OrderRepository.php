<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function filterByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    public function filterByDateRange($startDate, $endDate)
    {
        return $this->model
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    public function getRecent($limit = 10)
    {
        return $this->model
            ->with(['user', 'items.product'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getWithFilters(array $filters, $perPage = 15)
    {
        $query = $this->model->with(['user', 'payment']);

        // Search by order number or customer name
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Payment method filter
        if (!empty($filters['payment_method'])) {
            $query->whereHas('payment', function ($q) use ($filters) {
                $q->where('payment_type', $filters['payment_method']);
            });
        }

        // Date range filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function getStatistics()
    {
        // Use single query with conditional aggregation for better performance
        $stats = $this->model
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = "shipped" THEN 1 ELSE 0 END) as shipped,
                SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today,
                SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) as this_week,
                SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) as this_month
            ')
            ->first();

        return [
            'total' => (int) $stats->total,
            'pending' => (int) $stats->pending,
            'processing' => (int) $stats->processing,
            'shipped' => (int) $stats->shipped,
            'delivered' => (int) $stats->delivered,
            'cancelled' => (int) $stats->cancelled,
            'today' => (int) $stats->today,
            'this_week' => (int) $stats->this_week,
            'this_month' => (int) $stats->this_month,
        ];
    }

    public function getRevenue($startDate, $endDate)
    {
        return $this->model
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['processing', 'shipped', 'delivered'])
            ->sum('total');
    }

    public function paginate($perPage = 15, array $filters = [])
    {
        return $this->getWithFilters($filters, $perPage);
    }

    public function count()
    {
        return $this->model->count();
    }

    public function countByStatus($status)
    {
        return $this->model->where('status', $status)->count();
    }
}
