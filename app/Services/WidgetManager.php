<?php

namespace App\Services;

use App\Models\Widget;
use App\Models\WidgetPosition;
use Illuminate\Support\Facades\Log;

class WidgetManager
{
    /**
     * Render widgets for a specific position and route
     * 
     * @param string $position Position key (e.g., 'sidebar', 'header_right')
     * @param string $route Route name (e.g., 'dashboard')
     * @param array $options Additional options for rendering
     * @return string Rendered HTML
     */

    public function render($position, $route, array $options = [])
    {
        try {
            $widgets = WidgetPosition::with('widget')
                ->where('position_key', $position)
                ->where(function($query) use ($route) {
                    $query->where('route', $route)
                        ->orWhere('route', '*');
                })
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
            
            if ($widgets->isEmpty()) {
                return '';
            }
            
            $output = '';
            foreach ($widgets as $widgetPosition) {
                // Skip if widget is not found or inactive
                if (!$widgetPosition->widget || !$widgetPosition->widget->is_active) {
                    continue;
                }
                
                // Skip if user doesn't have permission
                if ($widgetPosition->widget->requires_auth && !$this->hasAccess($widgetPosition->widget)) {
                    continue;
                }
                
                try {
                    // Get data for the widget if needed
                    $data = $this->getWidgetData($widgetPosition->widget, $widgetPosition->settings ?? []);
                    
                    // Merge default settings with instance settings
                    $settings = array_merge(
                        $widgetPosition->widget->default_settings ?: [],
                        $widgetPosition->settings ?: []
                    );
                    
                    // Render the widget view
                    $widgetHtml = view($widgetPosition->widget->view_path, [
                        'widget' => $widgetPosition->widget,
                        'settings' => $settings,
                        'size' => $widgetPosition->size,
                        'data' => $data,
                    ])->render();
                    
                    // Create a wrapper based on size
                    $output .= $this->wrapWidget($widgetHtml, $widgetPosition);
                } catch (\Throwable $e) {  // Endre fra \Exception til \Throwable for å fange opp alle typer feil
                    // If rendering fails, show an error
                    Log::error("Failed to render widget {$widgetPosition->widget->name}: {$e->getMessage()}");
                    try {
                        $output .= view('partials.widget-error', [
                            'widget' => $widgetPosition->widget,
                            'error' => $e->getMessage()
                        ])->render();
                    } catch (\Throwable $viewError) {
                        // Hvis selv feilvisningen feiler, vis en enkel feilmelding
                        $output .= '<div class="alert alert-danger">Widget Error: ' . e($e->getMessage()) . '</div>';
                    }
                }
            }
            
            return $output;
        } catch (\Throwable $e) {  // Endre fra \Exception til \Throwable
            Log::error("Widget rendering error for position $position on route $route: {$e->getMessage()}");
            return '<div class="alert alert-danger">Widget position error: Unable to render widgets for this position.</div>';
        }
    }
    
    /**
     * Get data for a widget if it has a controller
     * 
     * @param Widget $widget
     * @param array $settings
     * @return array
     */
    protected function getWidgetData(Widget $widget, array $settings = [])
    {
        // If no module specified, return empty data
        if (empty($widget->module)) {
            return [];
        }
        
        // First try to find a module-based controller
        if (str_starts_with($widget->module, 'td-')) {
            $moduleData = $this->getModuleWidgetData($widget, $settings);
            if (!empty($moduleData)) {
                return $moduleData;
            }
        }
        
        // Fallback to core controller
        $controllerClass = "App\\Http\\Controllers\\Widgets\\{$widget->module}\\{$widget->name}Controller";
        
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, 'getData')) {
                return $controller->getData($settings);
            }
        }
        
        return [];
    }
    
    /**
     * Get data from a module-based widget controller
     * 
     * @param Widget $widget
     * @param array $settings
     * @return array
     */
    protected function getModuleWidgetData(Widget $widget, array $settings = [])
    {
        // Format the module name for namespace (e.g., 'td-equipment' -> 'TdEquipment')
        $moduleBase = ucfirst(str_replace('-', '', $widget->module));
        
        // Parse the view path to get the widget name
        // e.g., 'td-equipment::widgets.equipment-list' -> 'equipment-list'
        $viewPathParts = explode('.', $widget->view_path);
        $viewName = end($viewPathParts);
        
        // Format the method name (e.g., 'equipment-list' -> 'getEquipmentListData')
        $methodName = 'get' . str_replace('-', '', ucfirst($viewName)) . 'Data';
        
        // Build controller class path based on module
        $controllerClass = "\\Modules\\{$moduleBase}\\Controllers\\{$moduleBase}WidgetController";
        
        if (class_exists($controllerClass)) {
            try {
                $controller = app($controllerClass);
                if (method_exists($controller, $methodName)) {
                    return $controller->$methodName($settings);
                }
                
                // Fallback to generic getData method
                if (method_exists($controller, 'getData')) {
                    return $controller->getData($viewName, $settings);
                }
            } catch (\Exception $e) {
                \Log::error("Module widget controller error: " . $e->getMessage());
            }
        }
        
        return [];
    }
    
    /**
     * Check if current user has access to the widget
     * 
     * @param Widget $widget
     * @return bool
     */
    protected function hasAccess(Widget $widget)
    {
        if (!$widget->requires_auth) {
            return true;
        }
        
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        if (empty($widget->permissions)) {
            return true;
        }
        
        foreach ($widget->permissions as $permission) {
            try {
                if ($user->hasPermissionTo($permission)) {
                    return true;
                }
            } catch (\Exception $e) {
                Log::warning("Permission check failed: {$e->getMessage()}");
                continue;
            }
        }
        
        return false;
    }
    
    /**
     * Wrap widget HTML in appropriate container based on size
     * 
     * @param string $html Widget HTML content
     * @param WidgetPosition $position Widget position with size information
     * @return string Wrapped widget HTML
     */
    protected function wrapWidget($html, WidgetPosition $position)
    {
        $sizeCssClass = match ($position->size) {
            'small' => 'col-md-4',
            'medium' => 'col-md-6',
            'large' => 'col-md-8',
            'full' => 'col-12',
            default => 'col-md-6',
        };
        
        return sprintf(
            '<div class="widget %s mb-4" data-widget-id="%s" data-position-id="%s">
                <div class="card h-100">
                    <div class="card-body">
                        %s
                    </div>
                </div>
            </div>',
            $sizeCssClass,
            $position->widget_id,
            $position->id,
            $html
        );
    }
    
    /**
     * Get all available widget positions
     * 
     * @return array Position key => Description mapping
     */
    public function getAvailablePositions()
    {
        return [
            'widget_header_right' => 'Header Right',
            'widget_sidebar' => 'Sidebar',
            'widget_pageHeader_right' => 'Page Header Right',
        ];
    }
}
