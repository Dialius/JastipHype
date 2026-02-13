<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecurityAdminController extends Controller
{
    /**
     * Show security dashboard
     */
    public function index()
    {
        // Recent security events
        $recentEvents = SecurityEvent::with('user')
            ->latest()
            ->take(50)
            ->get();

        // Failed login attempts (last 24 hours)
        $failedLogins = DB::table('login_attempts')
            ->where('successful', false)
            ->where('attempted_at', '>', now()->subDay())
            ->orderBy('attempted_at', 'desc')
            ->get();

        // Blocked IPs
        $blockedIps = DB::table('blocked_ips')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $stats = [
            'failed_logins_today' => DB::table('login_attempts')
                ->where('successful', false)
                ->whereDate('attempted_at', today())
                ->count(),
            'successful_logins_today' => DB::table('login_attempts')
                ->where('successful', true)
                ->whereDate('attempted_at', today())
                ->count(),
            'blocked_ips_count' => count($blockedIps),
            'security_events_today' => SecurityEvent::whereDate('created_at', today())->count(),
        ];

        return view('admin.security.index', compact(
            'recentEvents',
            'failedLogins',
            'blockedIps',
            'stats'
        ));
    }

    /**
     * Block IP address
     */
    public function blockIp(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'required|string|max:255',
            'duration' => 'nullable|integer|min:1', // in hours
        ]);

        $expiresAt = $validated['duration'] 
            ? now()->addHours($validated['duration']) 
            : null;

        DB::table('blocked_ips')->updateOrInsert(
            ['ip_address' => $validated['ip_address']],
            [
                'reason' => $validated['reason'],
                'blocked_by' => auth()->id(),
                'expires_at' => $expiresAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return back()->with('success', 'IP address blocked successfully');
    }

    /**
     * Unblock IP address
     */
    public function unblockIp(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip',
        ]);

        DB::table('blocked_ips')
            ->where('ip_address', $validated['ip_address'])
            ->delete();

        return back()->with('success', 'IP address unblocked successfully');
    }

    /**
     * View security event details
     */
    public function showEvent(SecurityEvent $event)
    {
        $event->load('user');
        return view('admin.security.event-details', compact('event'));
    }
}
