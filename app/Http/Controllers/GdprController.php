<?php

namespace App\Http\Controllers;

use App\Models\CookieConsent;
use App\Services\GdprService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GdprController extends Controller
{
    protected $gdprService;

    public function __construct(GdprService $gdprService)
    {
        $this->gdprService = $gdprService;
    }

    /**
     * Show privacy policy
     */
    public function privacyPolicy()
    {
        return view('gdpr.privacy-policy');
    }

    /**
     * Show cookie policy
     */
    public function cookiePolicy()
    {
        return view('gdpr.cookie-policy');
    }

    /**
     * Show terms of service
     */
    public function termsOfService()
    {
        return view('gdpr.terms-of-service');
    }

    /**
     * Store cookie consent
     */
    public function storeCookieConsent(Request $request)
    {
        $validated = $request->validate([
            'necessary' => 'boolean',
            'functional' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
        ]);

        CookieConsent::create([
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'necessary' => $validated['necessary'] ?? true,
            'functional' => $validated['functional'] ?? false,
            'analytics' => $validated['analytics'] ?? false,
            'marketing' => $validated['marketing'] ?? false,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Show GDPR dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        return view('gdpr.dashboard', [
            'exportRequests' => $user->dataExportRequests()->latest()->get(),
            'deletionRequests' => $user->dataDeletionRequests()->latest()->get(),
        ]);
    }

    /**
     * Request data export
     */
    public function requestExport()
    {
        $user = auth()->user();
        
        // Check if there's a pending request
        $pending = $user->dataExportRequests()
            ->whereIn('status', ['pending', 'processing'])
            ->exists();
            
        if ($pending) {
            return back()->with('error', 'Anda sudah memiliki permintaan export yang sedang diproses');
        }

        $this->gdprService->exportUserData($user);

        return back()->with('success', 'Permintaan export data berhasil dibuat. Anda akan menerima email ketika data siap diunduh.');
    }

    /**
     * Download exported data
     */
    public function downloadExport($id)
    {
        $request = auth()->user()->dataExportRequests()->findOrFail($id);

        if ($request->status !== 'completed') {
            return back()->with('error', 'Data export belum selesai diproses');
        }

        if ($request->isExpired()) {
            return back()->with('error', 'Link download sudah expired');
        }

        return Storage::disk('local')->download($request->file_path);
    }

    /**
     * Request data deletion
     */
    public function requestDeletion(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        
        // Check if there's a pending request
        $pending = $user->dataDeletionRequests()
            ->whereIn('status', ['pending', 'approved', 'processing'])
            ->exists();
            
        if ($pending) {
            return back()->with('error', 'Anda sudah memiliki permintaan penghapusan data yang sedang diproses');
        }

        $this->gdprService->requestDataDeletion($user, $validated['reason'] ?? null);

        return back()->with('success', 'Permintaan penghapusan data berhasil dibuat. Tim kami akan meninjau permintaan Anda.');
    }
}
