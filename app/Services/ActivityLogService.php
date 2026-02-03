<?php

namespace App\Services;

use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    protected ActivityLogRepositoryInterface $activityLogRepository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    /**
     * Log an activity
     */
    public function log(string $action, string $module, ?int $entityId = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        $this->activityLogRepository->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'entity_id' => $entityId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log create operation
     */
    public function logCreate(string $module, int $entityId, array $values): void
    {
        $this->log('create', $module, $entityId, null, $values);
    }

    /**
     * Log update operation
     */
    public function logUpdate(string $module, int $entityId, array $oldValues, array $newValues): void
    {
        $this->log('update', $module, $entityId, $oldValues, $newValues);
    }

    /**
     * Log delete operation
     */
    public function logDelete(string $module, int $entityId, array $values): void
    {
        $this->log('delete', $module, $entityId, $values, null);
    }

    /**
     * Log status change
     */
    public function logStatusChange(string $module, int $entityId, string $oldStatus, string $newStatus): void
    {
        $this->log('status_change', $module, $entityId, ['status' => $oldStatus], ['status' => $newStatus]);
    }

    /**
     * Log login
     */
    public function logLogin(int $userId): void
    {
        $this->activityLogRepository->create([
            'user_id' => $userId,
            'action' => 'login',
            'module' => 'auth',
            'entity_id' => $userId,
            'old_values' => null,
            'new_values' => null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log logout
     */
    public function logLogout(int $userId): void
    {
        $this->activityLogRepository->create([
            'user_id' => $userId,
            'action' => 'logout',
            'module' => 'auth',
            'entity_id' => $userId,
            'old_values' => null,
            'new_values' => null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
