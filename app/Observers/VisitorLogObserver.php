<?php

namespace App\Observers;

use App\Models\VisitorLog;

class VisitorLogObserver
{
    /**
     * Handle the VisitorLog "created" event.
     */
    public function created(VisitorLog $visitorLog): void
    {
        // Update cache for visitor statistics
        \Illuminate\Support\Facades\Cache::forget('visitor_stats_today');
        \Illuminate\Support\Facades\Cache::forget('visitor_stats_month');
    }

    /**
     * Handle the VisitorLog "updated" event.
     */
    public function updated(VisitorLog $visitorLog): void
    {
        //
    }

    /**
     * Handle the VisitorLog "deleted" event.
     */
    public function deleted(VisitorLog $visitorLog): void
    {
        //
    }

    /**
     * Handle the VisitorLog "restored" event.
     */
    public function restored(VisitorLog $visitorLog): void
    {
        //
    }

    /**
     * Handle the VisitorLog "force deleted" event.
     */
    public function forceDeleted(VisitorLog $visitorLog): void
    {
        //
    }
}
