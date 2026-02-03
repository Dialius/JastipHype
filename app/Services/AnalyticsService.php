<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    protected $orderRepository;
    protected $productRepository;
    protected $customerRepository;
    protected $paymentRepository;

    // Cache TTL in seconds (5 minutes)
    protected $cacheTTL = 300;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        CustomerRepositoryInterface $customerRepository,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Get revenue analytics
     */
    public function getRevenueAnalytics($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        $cacheKey = 'analytics.revenue.' . $startDate->format('Y-m-d') . '.' . $endDate->format('Y-m-d');

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($startDate, $endDate) {
            return [
                'total' => $this->orderRepository->getRevenue($startDate, $endDate),
                'today' => $this->orderRepository->getRevenue(today(), today()->endOfDay()),
                'this_week' => $this->orderRepository->getRevenue(now()->startOfWeek(), now()->endOfWeek()),
                'this_month' => $this->orderRepository->getRevenue(now()->startOfMonth(), now()->endOfMonth()),
                'this_year' => $this->orderRepository->getRevenue(now()->startOfYear(), now()->endOfYear()),
            ];
        });
    }

    /**
     * Get revenue by payment method
     */
    public function getRevenueByPaymentMethod()
    {
        return Cache::remember('analytics.payment_methods', $this->cacheTTL, function () {
            return $this->paymentRepository->getPaymentMethodDistribution();
        });
    }

    /**
     * Get product performance
     */
    public function getProductPerformance($limit = 10)
    {
        $cacheKey = 'analytics.product_performance.' . $limit;

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($limit) {
            return DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('orders.status', ['processing', 'shipped', 'delivered'])
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('SUM(order_items.quantity) as total_quantity'),
                    DB::raw('SUM(order_items.subtotal) as total_revenue')
                )
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_revenue')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get customer analytics
     */
    public function getCustomerAnalytics()
    {
        return Cache::remember('analytics.customers', $this->cacheTTL, function () {
            $stats = $this->customerRepository->getStatistics();
            $topCustomers = $this->customerRepository->getTopCustomers(10);

            return [
                'statistics' => $stats,
                'top_customers' => $topCustomers,
            ];
        });
    }

    /**
     * Get dashboard metrics
     */
    public function getDashboardMetrics()
    {
        return Cache::remember('analytics.dashboard', $this->cacheTTL, function () {
            return [
                'revenue' => $this->getRevenueAnalytics(),
                'orders' => $this->orderRepository->getStatistics(),
                'customers' => $this->customerRepository->getStatistics(),
                'products' => [
                    'total' => $this->productRepository->count(),
                    'active' => $this->productRepository->getActive()->count(),
                    'low_stock' => $this->productRepository->getLowStock()->count(),
                ],
                'payments' => $this->paymentRepository->getStatistics(),
            ];
        });
    }

    /**
     * Clear analytics cache
     */
    public function clearCache()
    {
        Cache::forget('analytics.dashboard');
        Cache::forget('analytics.revenue.*');
        Cache::forget('analytics.payment_methods');
        Cache::forget('analytics.product_performance.*');
        Cache::forget('analytics.customers');
    }
}
