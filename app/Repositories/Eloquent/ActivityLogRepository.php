<?php

namespace App\Repositories\Eloquent;

use App\Models\ActivityLog;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;

class ActivityLogRepository extends BaseRepository implements ActivityLogRepositoryInterface
{
    public function __construct(ActivityLog $model)
    {
        parent::__construct($model);
    }

    public function getWithFilters(array $filters, $perPage = 15)
    {
        $query = $this->model->with('user');

        // User filter
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Action filter
        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        // Module filter
        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        // Date range filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        // Sorting
        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function getFiltered(array $filters, $perPage = 15)
    {
        return $this->getWithFilters($filters, $perPage);
    }

    public function getUniqueUsers()
    {
        return $this->model->with('user')
            ->select('user_id')
            ->distinct()
            ->whereNotNull('user_id')
            ->get()
            ->pluck('user')
            ->filter();
    }

    public function getUniqueActions()
    {
        return $this->model->select('action')
            ->distinct()
            ->whereNotNull('action')
            ->orderBy('action')
            ->pluck('action');
    }

    public function getUniqueModules()
    {
        return $this->model->select('module')
            ->distinct()
            ->whereNotNull('module')
            ->orderBy('module')
            ->pluck('module');
    }

    public function log(array $data)
    {
        return $this->create(array_merge($data, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));
    }

    public function getByUser($userId)
    {
        return $this->model->where('user_id', $userId)->latest()->get();
    }

    public function getByModule($module)
    {
        return $this->model->where('module', $module)->latest()->get();
    }
}
