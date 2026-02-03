<?php

namespace App\Services;

use App\Repositories\Contracts\VisitorLogRepositoryInterface;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Cache;

class VisitorTrackingService
{
    protected VisitorLogRepositoryInterface $visitorLogRepository;
    protected int $cacheTTL = 300; // 5 minutes

    public function __construct(VisitorLogRepositoryInterface $visitorLogRepository)
    {
        $this->visitorLogRepository = $visitorLogRepository;
    }

    /**
     * Log a visit
     * Record visitor log with IP, user agent, page, timestamp
     */
    public function logVisit(string $pageUrl, ?int $userId = null, ?string $sessionId = null): void
    {
        $this->visitorLogRepository->logVisit([
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'page_url' => $pageUrl,
            'user_id' => $userId,
            'session_id' => $sessionId ?? session()->getId(),
            'visited_at' => now(),
        ]);

        // Clear visitor cache when new visit is logged
        $this->clearCache();
    }

    /**
     * Get unique visitors today
     * Count unique IPs today
     */
    public function getUniqueVisitorsToday(): int
    {
        return Cache::remember('visitors.unique.today', $this->cacheTTL, function () {
            return $this->visitorLogRepository->getUniqueVisitorsToday();
        });
    }

    /**
     * Get unique visitors this month
     * Count unique IPs this month
     */
    public function getUniqueVisitorsThisMonth(): int
    {
        return Cache::remember('visitors.unique.month', $this->cacheTTL, function () {
            return $this->visitorLogRepository->getUniqueVisitorsThisMonth();
        });
    }

    /**
     * Get visitor trends
     * Daily/monthly aggregation for charts
     * 
     * @param string $period 'daily' or 'monthly'
     * @param int $limit Number of periods to return
     * @return \Illuminate\Support\Collection
     */
    public function getVisitorTrends(string $period = 'daily', int $limit = 30)
    {
        $cacheKey = "visitors.trends.{$period}.{$limit}";

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($period, $limit) {
            return $this->visitorLogRepository->getTrends($period, $limit);
        });
    }

    /**
     * Clean up old logs
     * Delete logs older than specified days (default 90 days)
     */
    public function cleanupOldLogs(int $days = 90): int
    {
        return $this->visitorLogRepository->cleanup($days);
    }

    /**
     * Get page views statistics
     */
    public function getPageViewsStatistics(int $days = 7): array
    {
        $cacheKey = "visitors.page_views.{$days}";

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($days) {
            $stats = [];
            $startDate = now()->subDays($days - 1)->startOfDay();
            
            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $stats[] = [
                    'date' => $date->format('Y-m-d'),
                    'views' => \App\Models\VisitorLog::whereDate('visited_at', $date)->count(),
                ];
            }
            
            return $stats;
        });
    }

    /**
     * Get visitor statistics summary
     */
    public function getStatisticsSummary(): array
    {
        return Cache::remember('visitors.summary', $this->cacheTTL, function () {
            return [
                'today' => $this->getUniqueVisitorsToday(),
                'this_month' => $this->getUniqueVisitorsThisMonth(),
                'total_page_views_today' => $this->visitorLogRepository->getTotalPageViewsToday(),
                'total_page_views_month' => $this->visitorLogRepository->getTotalPageViewsThisMonth(),
            ];
        });
    }

    /**
     * Clear visitor cache
     */
    protected function clearCache(): void
    {
        Cache::forget('visitors.unique.today');
        Cache::forget('visitors.unique.month');
        Cache::forget('visitors.summary');
        // Note: We don't clear trends and page_views cache as they're less time-sensitive
    }
}
