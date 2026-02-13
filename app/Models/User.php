<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'address',
        'is_admin',
        'password_reset_otp',
        'password_reset_otp_expires_at',
        'password_change_otp',
        'password_change_otp_expires_at',
        'pending_password',
        'suspension_reason',
        'suspended_at',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if the user is an administrator
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin ?? false;
    }

    /**
     * Get the user's wishlists
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    
    /**
     * Get the user's cart
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    
    /**
     * Get the user's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the user's data export requests
     */
    public function dataExportRequests()
    {
        return $this->hasMany(DataExportRequest::class);
    }

    /**
     * Get the user's data deletion requests
     */
    public function dataDeletionRequests()
    {
        return $this->hasMany(DataDeletionRequest::class);
    }

    /**
     * Get the user's security events
     */
    public function securityEvents()
    {
        return $this->hasMany(SecurityEvent::class);
    }

    /**
     * Get status attribute (virtual attribute based on suspension_reason)
     */
    public function getStatusAttribute()
    {
        return $this->suspension_reason ? 'suspended' : 'active';
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended()
    {
        return !is_null($this->suspension_reason);
    }
}
