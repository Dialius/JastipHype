<?php

namespace App\Repositories\Contracts;

interface VisitorLogRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Log a visit
     */
    public function logVisit(array $data);

    /**
     * Get unique visitors today
     */
    public function getUniqueVisitorsToday();

    /**
     * Get total page views today
     */
    public function getTotalPageViewsToday();

    /**
     * Get total page views this month
     */
    public function getTotalPageViewsThisMonth();

    /**
     * Get unique visitors this month
     */
    public function getUniqueVisitorsThisMonth();

    /**
     * Get visitor trends
     */
    public function getTrends($period = 'daily', $limit = 30);

    /**
     * Clean up old logs
     */
    public function cleanup($days = 90);
}
