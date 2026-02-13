<?php

namespace App\Services;

use App\Models\SecurityEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class SecurityService
{
    /**
     * Log security event
     */
    public function logSecurityEvent(
        ?int $userId,
        string $eventType,
        ?array $metadata = null
    ): void {
        SecurityEvent::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Log login attempt
     */
    public function logLoginAttempt(string $email, bool $successful): void
    {
        DB::table('login_attempts')->insert([
            'email' => $email,
            'ip_address' => request()->ip(),
            'successful' => $successful,
            'user_agent' => request()->userAgent(),
            'attempted_at' => now(),
        ]);
    }

    /**
     * Check if IP is blocked
     */
    public function isIpBlocked(string $ip): bool
    {
        return DB::table('blocked_ips')
            ->where('ip_address', $ip)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Check failed login attempts
     */
    public function checkFailedLoginAttempts(string $email): int
    {
        return DB::table('login_attempts')
            ->where('email', $email)
            ->where('successful', false)
            ->where('attempted_at', '>', now()->subMinutes(15))
            ->count();
    }

    /**
     * Block IP temporarily
     */
    public function blockIpTemporarily(string $ip, string $reason, int $minutes = 60): void
    {
        DB::table('blocked_ips')->updateOrInsert(
            ['ip_address' => $ip],
            [
                'reason' => $reason,
                'expires_at' => now()->addMinutes($minutes),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Validate password strength
     */
    public function validatePasswordStrength(string $password): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password harus minimal 8 karakter';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf besar';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf kecil';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password harus mengandung angka';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password harus mengandung karakter spesial';
        }

        return $errors;
    }

    /**
     * Check if password has been compromised
     */
    public function isPasswordCompromised(string $password): bool
    {
        $sha1 = strtoupper(sha1($password));
        $prefix = substr($sha1, 0, 5);
        $suffix = substr($sha1, 5);

        try {
            $response = file_get_contents("https://api.pwnedpasswords.com/range/{$prefix}");
            return str_contains($response, $suffix);
        } catch (\Exception $e) {
            return false; // If API fails, don't block user
        }
    }

    /**
     * Clean old login attempts
     */
    public function cleanOldLoginAttempts(): void
    {
        DB::table('login_attempts')
            ->where('attempted_at', '<', now()->subDays(30))
            ->delete();
    }

    /**
     * Clean expired blocked IPs
     */
    public function cleanExpiredBlockedIps(): void
    {
        DB::table('blocked_ips')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->delete();
    }
}
