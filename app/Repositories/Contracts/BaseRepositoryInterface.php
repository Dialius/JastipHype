<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    /**
     * Get all records
     */
    public function all();

    /**
     * Find a record by ID
     */
    public function find($id);

    /**
     * Find a record by ID (alias)
     */
    public function findById($id);

    /**
     * Create a new record
     */
    public function create(array $data);

    /**
     * Update a record
     */
    public function update($id, array $data);

    /**
     * Delete a record
     */
    public function delete($id);

    /**
     * Get paginated records
     */
    public function paginate($perPage = 15);
}
