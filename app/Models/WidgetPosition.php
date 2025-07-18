<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'route',
        'position_key',
        'widget_id',
        'sort_order',
        'is_active',
        'settings',
        'size',
        'name' // Kept for backwards compatibility
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Widget position belongs to a widget
     */
    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }

    /**
     * Scope for active positions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for a specific route
     */
    public function scopeForRoute($query, $route)
    {
        return $query->where('route', $route);
    }

    /**
     * Scope for a specific position
     */
    public function scopeForPosition($query, $position)
    {
        return $query->where('position_key', $position);
    }

    /**
     * Get CSS classes based on size
     */
    public function getSizeClassAttribute()
    {
        $sizeMap = [
            'small' => 'col-lg-3 col-md-6 col-sm-12',
            'medium' => 'col-lg-6 col-md-12', 
            'large' => 'col-lg-9 col-md-12',
            'full' => 'col-12'
        ];
        
        return $sizeMap[$this->size] ?? 'col-lg-6 col-md-12';
    }

    /**
     * Get widget settings (merge default with position-specific)
     */
    public function getWidgetSettingsAttribute()
    {
        $defaultSettings = $this->widget->default_settings ?? [];
        $positionSettings = $this->settings ?? [];
        
        return array_merge($defaultSettings, $positionSettings);
    }

    /**
     * Boot method for setting default values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($widgetPosition) {
            if (is_null($widgetPosition->sort_order)) {
                $maxOrder = static::where('route', $widgetPosition->route)
                    ->where('position_key', $widgetPosition->position_key)
                    ->max('sort_order') ?? 0;
                $widgetPosition->sort_order = $maxOrder + 1;
            }
        });
    }
}
