<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'admin_id',
        'message',
        'is_from_admin',
        'is_internal_note',
        'read_at',
    ];

    protected $casts = [
        'is_from_admin' => 'boolean',
        'is_internal_note' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the ticket this message belongs to
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /**
     * Get the user who sent the message (customer)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who sent the message
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the sender name
     */
    public function getSenderNameAttribute(): string
    {
        if ($this->is_from_admin) {
            return $this->admin?->name ?? 'Support Team';
        }
        
        return $this->user?->name ?? $this->ticket?->guest_name ?? 'Customer';
    }

    /**
     * Check if message is unread
     */
    public function getIsUnreadAttribute(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Boot method to update ticket's last_reply_at
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($message) {
            $message->ticket->update([
                'last_reply_at' => now(),
            ]);
            
            // If admin replied and ticket was open, set to in_progress
            if ($message->is_from_admin && $message->ticket->status === 'open') {
                $message->ticket->update(['status' => 'in_progress']);
            }
            
            // If customer replied and ticket was resolved, reopen it
            if (!$message->is_from_admin && $message->ticket->status === 'resolved') {
                $message->ticket->update(['status' => 'pending']);
            }
        });
    }
}
