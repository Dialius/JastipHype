<?php

namespace App\Repositories\Eloquent;

use App\Models\VisitorLog;
use App\Repositories\Contracts\VisitorLogRepositoryInterface;

class VisitorLogRepository extends BaseRepository implements VisitorLogRepositoryInterface
{
    public function __construct(VisitorLog $model)
    {
        parent::__construct($model);
    }

    public function logVisit(array $data)
    {
        return $this->create(array_merge($data, [
            'visited_at' => now(),
            'session_id' => session()->getId(),
        ]));
    }

    public function getUniqueVisitorsToday()
    {
        return VisitorLog::uniqueVisitorsToday();
    }

    public function getTotalPageViewsToday()
    {
        return VisitorLog::whereDate('visited_at', today())->count();
    }

    public function getTotalPageViewsThisMonth()
    {
        return VisitorLog::whereYear('visited_at', now()->year)
            ->whereMonth('visited_at', now()->month)
            ->count();
    }

    public function getUniqueVisitorsThisMonth()
    {
        return VisitorLog::uniqueVisitorsThisMonth();
    }

    public function getTrends($period = 'daily', $limit = 30)
    {
        if ($period === 'monthly') {
            return VisitorLog::monthlyTrends($limit);
        }
        return VisitorLog::dailyTrends($limit);
    }

    public function cleanup($days = 90)
    {
        return VisitorLog::cleanup($days);
    }
}
