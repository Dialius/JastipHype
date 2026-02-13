<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'necessary',
        'functional',
        'analytics',
        'marketing',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'necessary' => 'boolean',
        'functional' => 'boolean',
        'analytics' => 'boolean',
        'marketing' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
