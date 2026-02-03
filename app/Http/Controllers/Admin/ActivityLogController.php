<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected ActivityLogRepositoryInterface $activityLogRepository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    /**
     * Display activity logs list
     */
    public function index(Request $request)
    {
        $filters = [
            'user_id' => $request->input('user_id'),
            'action' => $request->input('action'),
            'module' => $request->input('module'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $logs = $this->activityLogRepository->getFiltered($filters, 50);
        
        // Get unique users, actions, and modules for filters
        $users = $this->activityLogRepository->getUniqueUsers();
        $actions = $this->activityLogRepository->getUniqueActions();
        $modules = $this->activityLogRepository->getUniqueModules();

        return view('admin.activity-logs.index', compact('logs', 'users', 'actions', 'modules', 'filters'));
    }

    /**
     * Display activity log detail
     */
    public function show($id)
    {
        $log = $this->activityLogRepository->findById($id);

        if (!$log) {
            return redirect()->route('admin.activity-logs.index')
                ->with('error', 'Activity log not found');
        }

        return view('admin.activity-logs.show', compact('log'));
    }
}
