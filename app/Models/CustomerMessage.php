<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMessage extends Model
{
    protected $fillable = [
        'customer_id',
        'admin_id',
        'message',
        'type',
        'status',
        'parent_id',
    ];

    /**
     * Get the customer who sent the message
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the admin who responded
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the parent message (for threading)
     */
    public function parent()
    {
        return $this->belongsTo(CustomerMessage::class, 'parent_id');
    }

    /**
     * Get all replies to this message
     */
    public function replies()
    {
        return $this->hasMany(CustomerMessage::class, 'parent_id')->orderBy('created_at');
    }

    /**
     * Get the root message of the thread
     */
    public function thread()
    {
        if ($this->parent_id) {
            return $this->parent->thread();
        }
        return $this;
    }

    /**
     * Get all messages in the thread
     */
    public function threadMessages()
    {
        $root = $this->thread();
        return static::where('id', $root->id)
            ->orWhere('parent_id', $root->id)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Scope to get only root messages (not replies)
     */
    public function scopeRootMessages($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by customer
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Check if message is from admin
     */
    public function isFromAdmin()
    {
        return $this->admin_id !== null;
    }

    /**
     * Check if message is from customer
     */
    public function isFromCustomer()
    {
        return $this->admin_id === null;
    }

    /**
     * Mark message as read/resolved
     */
    public function markAsResolved()
    {
        $this->update(['status' => 'resolved']);
    }

    /**
     * Mark message as closed
     */
    public function markAsClosed()
    {
        $this->update(['status' => 'closed']);
    }
}
