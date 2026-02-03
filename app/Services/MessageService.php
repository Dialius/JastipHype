<?php

namespace App\Services;

use App\Repositories\Contracts\CustomerMessageRepositoryInterface;

class MessageService
{
    protected $messageRepository;

    public function __construct(CustomerMessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * Get messages by customer
     */
    public function getByCustomer($customerId)
    {
        return $this->messageRepository->getByCustomer($customerId);
    }

    /**
     * Get message thread
     */
    public function getThread($messageId)
    {
        return $this->messageRepository->getThread($messageId);
    }

    /**
     * Send message
     */
    public function sendMessage(array $data)
    {
        return $this->messageRepository->sendMessage($data);
    }

    /**
     * Reply to message
     */
    public function reply($parentId, $message, $adminId)
    {
        return $this->messageRepository->sendMessage([
            'parent_id' => $parentId,
            'message' => $message,
            'admin_id' => $adminId,
            'customer_id' => $this->messageRepository->find($parentId)->customer_id,
        ]);
    }

    /**
     * Get messages with filters
     */
    public function getWithFilters(array $filters, $perPage = 15)
    {
        return $this->messageRepository->getWithFilters($filters, $perPage);
    }

    /**
     * Mark message as resolved
     */
    public function markAsResolved($id)
    {
        return $this->messageRepository->markAsResolved($id);
    }

    /**
     * Get message thread for a customer
     */
    public function getMessageThread($customerId)
    {
        return $this->messageRepository->getByCustomer($customerId);
    }

    /**
     * Send message to customer from admin
     */
    public function sendToCustomer($customerId, $adminId, $message)
    {
        return $this->messageRepository->create([
            'customer_id' => $customerId,
            'admin_id' => $adminId,
            'message' => $message,
            'type' => 'admin_to_customer',
            'status' => 'sent',
        ]);
    }

    /**
     * Get message statistics
     */
    public function getStatistics()
    {
        return [
            'total_messages' => $this->messageRepository->count(),
            'unread_messages' => $this->messageRepository->countUnread(),
            'resolved_messages' => $this->messageRepository->countResolved(),
            'pending_messages' => $this->messageRepository->countPending(),
        ];
    }

    /**
     * Mark message as read
     */
    public function markAsRead($messageId)
    {
        return $this->messageRepository->update($messageId, [
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Delete message
     */
    public function deleteMessage($messageId)
    {
        return $this->messageRepository->delete($messageId);
    }
}
