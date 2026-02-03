<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Contracts\SettingsRepositoryInterface;

class SettingsRepository implements SettingsRepositoryInterface
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function get($group, $key = null, $default = null)
    {
        // If only one parameter, treat as dot notation
        if ($key === null && strpos($group, '.') !== false) {
            return Setting::get($group, null, $default);
        }
        
        return Setting::get($group, $key, $default);
    }

    public function set($group, $key = null, $value = null, $type = 'string')
    {
        // If only two parameters, treat first as dot notation
        if ($value === null && strpos($group, '.') !== false) {
            return Setting::set($group, $key);
        }
        
        return Setting::set($group, $key, $value, $type);
    }

    public function getGroup($group)
    {
        return Setting::getGroup($group);
    }

    public function getByGroup($group)
    {
        return $this->getGroup($group);
    }

    public function setGroup($group, array $settings)
    {
        return Setting::setGroup($group, $settings);
    }
}
