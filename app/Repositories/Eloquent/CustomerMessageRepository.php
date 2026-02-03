<?php

namespace App\Repositories\Eloquent;

use App\Models\CustomerMessage;
use App\Repositories\Contracts\CustomerMessageRepositoryInterface;

class CustomerMessageRepository extends BaseRepository implements CustomerMessageRepositoryInterface
{
    public function __construct(CustomerMessage $model)
    {
        parent::__construct($model);
    }

    public function getByCustomer($customerId)
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->rootMessages()
            ->with(['replies', 'customer', 'admin'])
            ->latest()
            ->get();
    }

    public function getThread($messageId)
    {
        $message = $this->find($messageId);
        return $message ? $message->threadMessages() : collect();
    }

    public function sendMessage(array $data)
    {
        return $this->create($data);
    }

    public function getWithFilters(array $filters, $perPage = 15)
    {
        $query = $this->model->with(['customer', 'admin'])->rootMessages();

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Type filter
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Customer filter
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('message', 'like', "%{$search}%");
        }

        // Sorting
        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function markAsResolved($id)
    {
        $message = $this->find($id);
        if ($message) {
            $message->markAsResolved();
            return $message;
        }
        return null;
    }

    public function count()
    {
        return $this->model->count();
    }

    public function countUnread()
    {
        return $this->model->where('status', 'unread')->count();
    }

    public function countResolved()
    {
        return $this->model->where('status', 'resolved')->count();
    }

    public function countPending()
    {
        return $this->model->where('status', 'pending')->count();
    }
}
