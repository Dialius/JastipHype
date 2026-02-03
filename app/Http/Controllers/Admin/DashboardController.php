<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Services\OnlineUsersService;
use App\Services\VisitorTrackingService;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $analyticsService;
    protected $onlineUsersService;
    protected $visitorTrackingService;
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
        AnalyticsService $analyticsService,
        OnlineUsersService $onlineUsersService,
        VisitorTrackingService $visitorTrackingService,
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->analyticsService = $analyticsService;
        $this->onlineUsersService = $onlineUsersService;
        $this->visitorTrackingService = $visitorTrackingService;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all dashboard metrics
        $metrics = $this->analyticsService->getDashboardMetrics();
        
        // Get revenue analytics
        $revenue = $metrics['revenue'];
        
        // Get order statistics
        $orders = $metrics['orders'];
        
        // Get customer statistics
        $customers = $metrics['customers'];
        
        // Get product statistics
        $products = $metrics['products'];
        
        // Get online users count
        $onlineUsers = $this->onlineUsersService->getOnlineCount();
        
        // Get unique visitors
        $visitorsToday = $this->visitorTrackingService->getUniqueVisitorsToday();
        $visitorsMonth = $this->visitorTrackingService->getUniqueVisitorsThisMonth();
        
        // Get recent orders
        $recentOrders = $this->orderRepository->getRecent(10);
        
        // Get low stock products
        $lowStockProducts = $this->productRepository->getLowStock(10);
        
        // Get revenue data for last 7 days (for chart)
        $revenueChartData = $this->getRevenueChartData();
        
        // Get visitor data for last 7 days (for chart)
        $visitorChartData = $this->getVisitorChartData();
        
        return view('admin.dashboard.index', compact(
            'revenue',
            'orders',
            'customers',
            'products',
            'onlineUsers',
            'visitorsToday',
            'visitorsMonth',
            'recentOrders',
            'lowStockProducts',
            'revenueChartData',
            'visitorChartData'
        ));
    }
    
    /**
     * Get revenue data for last 7 days for chart
     *
     * @return array
     */
    private function getRevenueChartData()
    {
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            
            // Get revenue for this date
            $revenue = $this->orderRepository->getRevenue(
                $date,
                $date->copy()->endOfDay()
            );
            
            $data[] = $revenue;
            $labels[] = $date->format('d M');
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Get visitor data for last 7 days for chart
     *
     * @return array
     */
    private function getVisitorChartData()
    {
        $trends = $this->visitorTrackingService->getVisitorTrends('daily', 7);
        
        $data = [];
        $labels = [];
        
        // Convert Collection to array and format data
        if ($trends && $trends->isNotEmpty()) {
            foreach ($trends as $trend) {
                $labels[] = date('d M', strtotime($trend->date));
                $data[] = $trend->visitors;
            }
        } else {
            // If no data, generate last 7 days with zero
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('d M');
                $data[] = 0;
            }
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
