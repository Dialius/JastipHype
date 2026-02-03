<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function getWithFilters(array $filters, $perPage = 15)
    {
        $query = $this->model->with(['order.user']);

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('transaction_status', $filters['status']);
        }

        // Payment type filter
        if (!empty($filters['payment_type'])) {
            $query->where('payment_type', $filters['payment_type']);
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Search by transaction ID or order ID
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->with(['order.user', 'order.items.product'])->find($id);
    }

    public function getPaymentMethodDistribution()
    {
        return $this->model
            ->select('payment_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(gross_amount) as total'))
            ->whereIn('transaction_status', ['settlement', 'capture', 'paid'])
            ->groupBy('payment_type')
            ->get();
    }

    public function getStatusDistribution()
    {
        return $this->model
            ->select('transaction_status', DB::raw('COUNT(*) as count'))
            ->groupBy('transaction_status')
            ->get();
    }

    public function getRevenueByPaymentMethod()
    {
        return $this->model
            ->select('payment_type', DB::raw('SUM(gross_amount) as revenue'))
            ->whereIn('transaction_status', ['settlement', 'capture', 'paid'])
            ->groupBy('payment_type')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    public function getStatistics()
    {
        $total = $this->model->count();
        $paid = $this->model->whereIn('transaction_status', ['settlement', 'capture', 'paid'])->count();
        
        return [
            'total' => $total,
            'paid' => $paid,
            'pending' => $this->model->whereIn('transaction_status', ['pending', 'unpaid'])->count(),
            'failed' => $this->model->whereIn('transaction_status', ['deny', 'cancel', 'expire', 'failed'])->count(),
            'total_amount' => $this->model->whereIn('transaction_status', ['settlement', 'capture', 'paid'])->sum('gross_amount'),
            'success_rate' => $total > 0 ? round(($paid / $total) * 100, 2) : 0,
        ];
    }

    public function syncStatus($id)
    {
        // This will be implemented in the service layer with Midtrans integration
        return $this->find($id);
    }
}
