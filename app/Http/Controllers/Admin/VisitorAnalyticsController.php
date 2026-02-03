<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VisitorTrackingService;
use App\Services\OnlineUsersService;
use Illuminate\Http\Request;

class VisitorAnalyticsController extends Controller
{
    protected VisitorTrackingService $visitorTrackingService;
    protected OnlineUsersService $onlineUsersService;

    public function __construct(
        VisitorTrackingService $visitorTrackingService,
        OnlineUsersService $onlineUsersService
    ) {
        $this->visitorTrackingService = $visitorTrackingService;
        $this->onlineUsersService = $onlineUsersService;
    }

    /**
     * Display visitor analytics dashboard
     */
    public function index(Request $request)
    {
        // Get visitor statistics
        $statistics = $this->visitorTrackingService->getStatisticsSummary();
        
        // Get online users
        $onlineUsers = $this->onlineUsersService->getOnlineUsers();
        $onlineCount = $this->onlineUsersService->getOnlineCount();
        
        // Get visitor trends
        $period = $request->input('period', 'daily');
        $limit = $period === 'daily' ? 30 : 12; // 30 days or 12 months
        $trends = $this->visitorTrackingService->getVisitorTrends($period, $limit);
        
        // Get page views statistics
        $pageViews = $this->visitorTrackingService->getPageViewsStatistics(7);

        return view('admin.visitors.index', compact(
            'statistics',
            'onlineUsers',
            'onlineCount',
            'trends',
            'pageViews',
            'period'
        ));
    }

    /**
     * Get online users (AJAX endpoint)
     */
    public function getOnlineUsers(Request $request)
    {
        $users = $this->onlineUsersService->getOnlineUsers();
        $count = $this->onlineUsersService->getOnlineCount();

        return response()->json([
            'users' => $users,
            'count' => $count,
        ]);
    }

    /**
     * Get visitor trends (AJAX endpoint)
     */
    public function getTrends(Request $request)
    {
        $period = $request->input('period', 'daily');
        $limit = $request->input('limit', 30);
        
        $trends = $this->visitorTrackingService->getVisitorTrends($period, $limit);

        return response()->json($trends);
    }
}
