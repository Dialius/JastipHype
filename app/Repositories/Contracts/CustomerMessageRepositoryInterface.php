<?php

namespace App\Repositories\Contracts;

interface CustomerMessageRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get messages by customer
     */
    public function getByCustomer($customerId);

    /**
     * Get message thread
     */
    public function getThread($messageId);

    /**
     * Send message
     */
    public function sendMessage(array $data);

    /**
     * Get messages with filters
     */
    public function getWithFilters(array $filters, $perPage = 15);

    /**
     * Mark message as resolved
     */
    public function markAsResolved($id);
}
