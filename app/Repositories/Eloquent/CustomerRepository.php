<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function search($query)
    {
        return $this->model
            ->where('is_admin', false)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->get();
    }

    public function getStatistics()
    {
        $customers = $this->model->where('is_admin', false);

        return [
            'total' => $customers->count(),
            'active' => $customers->whereNotNull('email_verified_at')->count(),
            'new_today' => $customers->whereDate('created_at', today())->count(),
            'new_this_week' => $customers->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_this_month' => $customers->whereMonth('created_at', now()->month)->count(),
        ];
    }

    public function getWithFilters(array $filters, $perPage = 15)
    {
        $query = $this->model->where('is_admin', false);

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter - using suspension_reason as indicator
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'suspended') {
                $query->whereNotNull('suspension_reason');
            } elseif ($filters['status'] === 'active') {
                $query->whereNull('suspension_reason');
            }
        }

        // Verified filter
        if (isset($filters['verified'])) {
            if ($filters['verified']) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Spending filters
        if (!empty($filters['min_spending'])) {
            $query->whereHas('orders', function($q) use ($filters) {
                $q->select('user_id')
                  ->where('status', 'delivered')
                  ->groupBy('user_id')
                  ->havingRaw('SUM(total) >= ?', [$filters['min_spending']]);
            });
        }

        if (!empty($filters['max_spending'])) {
            $query->whereHas('orders', function($q) use ($filters) {
                $q->select('user_id')
                  ->where('status', 'delivered')
                  ->groupBy('user_id')
                  ->havingRaw('SUM(total) <= ?', [$filters['max_spending']]);
            });
        }

        // Minimum orders filter
        if (!empty($filters['min_orders'])) {
            $query->has('orders', '>=', $filters['min_orders']);
        }

        // Days since last order filter
        if (!empty($filters['days_since_order'])) {
            $date = now()->subDays($filters['days_since_order']);
            $query->whereHas('orders', function($q) use ($date) {
                $q->where('created_at', '>=', $date);
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function getTopCustomers($limit = 10)
    {
        return $this->model
            ->where('is_admin', false)
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->orderByDesc('orders_sum_total')
            ->limit($limit)
            ->get();
    }

    public function getOrderHistory($customerId)
    {
        $customer = $this->find($customerId);
        return $customer ? $customer->orders()->with(['items.product', 'payment'])->latest()->get() : collect();
    }

    public function getSpendingAnalytics($customerId)
    {
        $customer = $this->find($customerId);
        
        if (!$customer) {
            return null;
        }

        $orders = $customer->orders()
            ->whereIn('status', ['processing', 'shipped', 'delivered'])
            ->get();

        return [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total'),
            'average_order_value' => $orders->avg('total'),
            'first_order_date' => $orders->min('created_at'),
            'last_order_date' => $orders->max('created_at'),
        ];
    }

    public function paginate($perPage = 15, array $filters = [])
    {
        $query = $this->model->where('is_admin', false);

        // Search by name or email
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status - using custom attribute or default to 'active'
        // Note: users table doesn't have status column by default
        // We'll filter suspended users if suspension_reason exists
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'suspended') {
                $query->whereNotNull('suspension_reason');
            } elseif ($filters['status'] === 'active') {
                $query->whereNull('suspension_reason');
            }
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_orders':
                $query->withCount('orders')->orderByDesc('orders_count');
                break;
            case 'highest_spending':
                $query->withSum(['orders' => function($q) {
                    $q->where('status', 'delivered');
                }], 'total')->orderByDesc('orders_sum_total');
                break;
            default: // latest
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Load relationships for display
        $query->withCount('orders')
              ->withSum(['orders' => function($q) {
                  $q->where('status', 'delivered');
              }], 'total');

        return $query->paginate($perPage);
    }

    public function count()
    {
        return $this->model->where('is_admin', false)->count();
    }

    public function countByStatus($status)
    {
        $query = $this->model->where('is_admin', false);
        
        if ($status === 'suspended') {
            $query->whereNotNull('suspension_reason');
        } elseif ($status === 'active') {
            $query->whereNull('suspension_reason');
        }
        
        return $query->count();
    }

    public function countNewThisMonth()
    {
        return $this->model->where('is_admin', false)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }
}
