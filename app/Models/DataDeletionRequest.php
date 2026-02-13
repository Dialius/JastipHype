<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDeletionRequest extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'reason',
        'admin_notes',
        'approved_by',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
