<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataDeletionRequest;
use App\Models\DataExportRequest;
use App\Services\GdprService;
use Illuminate\Http\Request;

class GdprAdminController extends Controller
{
    protected $gdprService;

    public function __construct(GdprService $gdprService)
    {
        $this->gdprService = $gdprService;
    }

    /**
     * Show GDPR dashboard
     */
    public function index()
    {
        $exportRequests = DataExportRequest::with('user')
            ->latest()
            ->paginate(20);

        $deletionRequests = DataDeletionRequest::with('user', 'approver')
            ->latest()
            ->paginate(20);

        return view('admin.gdpr.index', compact('exportRequests', 'deletionRequests'));
    }

    /**
     * Approve deletion request
     */
    public function approveDeletion(Request $request, DataDeletionRequest $deletionRequest)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $deletionRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return back()->with('success', 'Deletion request approved');
    }

    /**
     * Reject deletion request
     */
    public function rejectDeletion(Request $request, DataDeletionRequest $deletionRequest)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $deletionRequest->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        return back()->with('success', 'Deletion request rejected');
    }

    /**
     * Process approved deletion
     */
    public function processDeletion(DataDeletionRequest $deletionRequest)
    {
        if ($deletionRequest->status !== 'approved') {
            return back()->with('error', 'Request must be approved first');
        }

        try {
            $deletionRequest->update(['status' => 'processing']);
            $this->gdprService->processDataDeletion($deletionRequest);
            
            return back()->with('success', 'User data deleted successfully');
        } catch (\Exception $e) {
            $deletionRequest->update(['status' => 'approved']);
            return back()->with('error', 'Failed to delete user data: ' . $e->getMessage());
        }
    }
}
