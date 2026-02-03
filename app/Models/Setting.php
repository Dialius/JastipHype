<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when settings are updated
        static::saved(function ($setting) {
            Cache::forget("settings.{$setting->group}.{$setting->key}");
            Cache::forget("settings.group.{$setting->group}");
            Cache::forget('settings');
        });

        static::deleted(function ($setting) {
            Cache::forget("settings.{$setting->group}.{$setting->key}");
            Cache::forget("settings.group.{$setting->group}");
            Cache::forget('settings');
        });
    }

    /**
     * Get a setting value by group and key
     */
    public static function get($group, $key = null, $default = null)
    {
        // Handle dot notation (e.g., 'shipping.enabled_couriers', null, $default)
        if ($key === null || (is_array($key) || is_bool($key) || is_int($key))) {
            // Second parameter is actually the default value
            if ($key !== null) {
                $default = $key;
            }
            
            if (strpos($group, '.') !== false) {
                list($group, $key) = explode('.', $group, 2);
            } else {
                return $default;
            }
        }
        
        $cacheKey = "settings.{$group}.{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($group, $key, $default) {
            $setting = static::where('group', $group)
                ->where('key', $key)
                ->first();

            if (!$setting) {
                return $default;
            }

            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set($group, $key = null, $value = null, $type = 'string')
    {
        // Handle dot notation (e.g., 'shipping.enabled_couriers', $value)
        if ($value === null && $key !== null && strpos($group, '.') !== false) {
            $value = $key;
            list($group, $key) = explode('.', $group, 2);
        }
        // Handle traditional (e.g., 'shipping', 'enabled_couriers', $value)
        elseif (strpos($group, '.') !== false && $key !== null && $value !== null) {
            list($group, $key) = explode('.', $group, 2);
        }
        
        // Auto-detect type and convert value to string for database storage
        if ($type === 'string') {
            if (is_array($value)) {
                $type = 'json';
                $value = json_encode($value);
            } elseif (is_bool($value)) {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            } elseif (is_int($value)) {
                $type = 'integer';
                $value = (string) $value;
            } else {
                $value = (string) $value;
            }
        } else {
            // Type is explicitly specified, convert value accordingly
            if ($type === 'json' && is_array($value)) {
                $value = json_encode($value);
            } elseif ($type === 'boolean' && is_bool($value)) {
                $value = $value ? '1' : '0';
            } elseif ($type === 'integer' && is_int($value)) {
                $value = (string) $value;
            } else {
                $value = (string) $value;
            }
        }
        
        $setting = static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value, 'type' => $type]
        );
        
        // Clear cache
        Cache::forget("settings.{$group}.{$key}");
        Cache::forget("settings.group.{$group}");
        
        return $setting;
    }

    /**
     * Get all settings for a group
     */
    public static function getGroup($group)
    {
        $cacheKey = "settings.group.{$group}";

        return Cache::remember($cacheKey, 3600, function () use ($group) {
            $settings = static::where('group', $group)->get();
            
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = static::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Set multiple settings for a group
     */
    public static function setGroup($group, array $settings)
    {
        foreach ($settings as $key => $value) {
            // Determine type and convert value if needed
            if (is_array($value)) {
                $type = 'json';
                $value = json_encode($value);
            } elseif (is_bool($value)) {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            } elseif (is_int($value)) {
                $type = 'integer';
                $value = (string) $value;
            } else {
                $type = 'string';
                $value = (string) $value;
            }
            
            static::set($group, $key, $value, $type);
        }
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Scope to filter by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
