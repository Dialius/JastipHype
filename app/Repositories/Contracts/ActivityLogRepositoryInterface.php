<?php

namespace App\Repositories\Contracts;

interface ActivityLogRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get logs with filters
     */
    public function getWithFilters(array $filters, $perPage = 15);

    /**
     * Get filtered logs (alias for getWithFilters)
     */
    public function getFiltered(array $filters, $perPage = 15);

    /**
     * Get unique users from logs
     */
    public function getUniqueUsers();

    /**
     * Get unique actions from logs
     */
    public function getUniqueActions();

    /**
     * Get unique modules from logs
     */
    public function getUniqueModules();

    /**
     * Log an activity
     */
    public function log(array $data);

    /**
     * Get logs by user
     */
    public function getByUser($userId);

    /**
     * Get logs by module
     */
    public function getByModule($module);
}
