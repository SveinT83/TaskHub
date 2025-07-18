<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'view_path',
        'module',
        'category',
        'is_configurable',
        'default_settings',
        'icon',
        'preview_image',
        'requires_auth',
        'permissions',
        'is_active'
    ];

    protected $casts = [
        'default_settings' => 'array',
        'permissions' => 'array',
        'is_configurable' => 'boolean',
        'requires_auth' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Widget has many positions
     */
    public function positions()
    {
        return $this->hasMany(WidgetPosition::class);
    }

    /**
     * Active positions for this widget
     */
    public function activePositions()
    {
        return $this->hasMany(WidgetPosition::class)->where('is_active', true);
    }

    /**
     * Scope for active widgets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for widgets in a category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Check if user has access to this widget
     */
    public function hasAccess($user = null)
    {
        if (!$this->requires_auth) {
            return true;
        }

        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) {
            return false;
        }

        if (empty($this->permissions)) {
            return true;
        }

        // Check user roles against widget permissions
        foreach ($this->permissions as $permission) {
            try {
                if ($user->hasPermissionTo($permission)) {
                    return true;
                }
            } catch (\Exception $e) {
                // Log permission error but continue checking other permissions
                \Log::warning("Permission check failed for permission '{$permission}' on widget '{$this->name}': " . $e->getMessage());
                continue;
            }
        }

        return false;
    }

    /**
     * Get default settings or empty settings
     */
    public function getDefaultSettingsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
}
