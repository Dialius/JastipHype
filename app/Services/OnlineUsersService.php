<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\User;

class OnlineUsersService
{
    protected int $ttl = 300; // 5 minutes in seconds

    /**
     * Track user activity
     * Store user activity in Redis/Cache with TTL of 5 minutes
     */
    public function trackActivity(int $userId): void
    {
        $key = "online_users:{$userId}";
        
        // Store in cache with TTL
        Cache::put($key, [
            'user_id' => $userId,
            'last_activity' => now()->toDateTimeString(),
        ], $this->ttl);
        
        // Also maintain a set of all online user keys for easy retrieval
        $this->addToOnlineSet($userId);
    }

    /**
     * Get count of online users
     */
    public function getOnlineCount(): int
    {
        $userIds = $this->getOnlineUserIds();
        return count($userIds);
    }

    /**
     * Get list of online users with details
     */
    public function getOnlineUsers(): array
    {
        $userIds = $this->getOnlineUserIds();
        
        if (empty($userIds)) {
            return [];
        }

        // Get user details from database
        $users = User::whereIn('id', $userIds)
            ->select('id', 'name', 'email')
            ->get();

        // Merge with last activity time from cache
        return $users->map(function ($user) {
            $key = "online_users:{$user->id}";
            $data = Cache::get($key);
            
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'last_activity' => $data['last_activity'] ?? null,
            ];
        })->toArray();
    }

    /**
     * Get array of online user IDs
     */
    protected function getOnlineUserIds(): array
    {
        $setKey = 'online_users_set';
        $userIds = Cache::get($setKey, []);
        
        // Filter out expired users
        $activeUserIds = [];
        foreach ($userIds as $userId) {
            $key = "online_users:{$userId}";
            if (Cache::has($key)) {
                $activeUserIds[] = $userId;
            }
        }
        
        // Update the set with only active users
        if (count($activeUserIds) !== count($userIds)) {
            Cache::put($setKey, $activeUserIds, 3600); // 1 hour
        }
        
        return $activeUserIds;
    }

    /**
     * Add user to online users set
     */
    protected function addToOnlineSet(int $userId): void
    {
        $setKey = 'online_users_set';
        $userIds = Cache::get($setKey, []);
        
        if (!in_array($userId, $userIds)) {
            $userIds[] = $userId;
            Cache::put($setKey, $userIds, 3600); // 1 hour
        }
    }

    /**
     * Remove user from online tracking
     */
    public function removeUser(int $userId): void
    {
        $key = "online_users:{$userId}";
        Cache::forget($key);
        
        // Remove from set
        $setKey = 'online_users_set';
        $userIds = Cache::get($setKey, []);
        $userIds = array_filter($userIds, fn($id) => $id !== $userId);
        Cache::put($setKey, $userIds, 3600);
    }

    /**
     * Check if user is online
     */
    public function isOnline(int $userId): bool
    {
        $key = "online_users:{$userId}";
        return Cache::has($key);
    }
}
