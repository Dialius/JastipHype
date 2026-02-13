<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataExportRequest extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'file_path',
        'completed_at',
        'expires_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
