<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VisitorLog extends Model
{
    const UPDATED_AT = null; // Only use created_at

    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'user_id',
        'session_id',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /**
     * Get the user associated with this visit
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get unique visitors count for today
     */
    public static function uniqueVisitorsToday()
    {
        return static::whereDate('visited_at', today())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Get unique visitors count for this month
     */
    public static function uniqueVisitorsThisMonth()
    {
        return static::whereYear('visited_at', now()->year)
            ->whereMonth('visited_at', now()->month)
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Get visitor trends (daily aggregation)
     */
    public static function dailyTrends($days = 30)
    {
        return static::select(
                DB::raw('DATE(visited_at) as date'),
                DB::raw('COUNT(DISTINCT ip_address) as visitors'),
                DB::raw('COUNT(*) as page_views')
            )
            ->where('visited_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get visitor trends (monthly aggregation)
     */
    public static function monthlyTrends($months = 12)
    {
        return static::select(
                DB::raw('YEAR(visited_at) as year'),
                DB::raw('MONTH(visited_at) as month'),
                DB::raw('COUNT(DISTINCT ip_address) as visitors'),
                DB::raw('COUNT(*) as page_views')
            )
            ->where('visited_at', '>=', now()->subMonths($months))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get most visited pages
     */
    public static function topPages($limit = 10)
    {
        return static::select('page_url', DB::raw('COUNT(*) as visits'))
            ->groupBy('page_url')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get();
    }

    /**
     * Clean up old logs (older than specified days)
     */
    public static function cleanup($days = 90)
    {
        return static::where('visited_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('visited_at', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
