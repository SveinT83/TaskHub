<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get a setting from the database.
     *
     * @param string $name The name of the setting
     * @param string $group The group of the setting
     * @param mixed $default Default value if setting doesn't exist
     * @return mixed
     */
    function setting(string $name, string $group = 'general', $default = null)
    {
        // Cache key for this setting
        $cacheKey = "setting_{$group}_{$name}";

        // Try to get from cache first
        return Cache::remember($cacheKey, 86400, function () use ($name, $group, $default) {
            $setting = Setting::where('name', $name)
                            ->where('group', $group)
                            ->first();

            return $setting ? $setting->value : $default;
        });
    }
}
