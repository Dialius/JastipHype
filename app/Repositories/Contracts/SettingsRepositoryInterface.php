<?php

namespace App\Repositories\Contracts;

interface SettingsRepositoryInterface
{
    /**
     * Get a setting value
     * Supports dot notation: get('shipping.enabled_couriers', null, [])
     * Or traditional: get('shipping', 'enabled_couriers', [])
     */
    public function get($group, $key = null, $default = null);

    /**
     * Set a setting value
     * Supports dot notation: set('shipping.enabled_couriers', ['jne', 'pos'])
     * Or traditional: set('shipping', 'enabled_couriers', ['jne', 'pos'])
     */
    public function set($group, $key = null, $value = null, $type = 'string');

    /**
     * Get all settings for a group
     */
    public function getGroup($group);

    /**
     * Get all settings for a group (alias)
     */
    public function getByGroup($group);

    /**
     * Set multiple settings for a group
     */
    public function setGroup($group, array $settings);
}
