<?php

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Models\WidgetPosition;
use App\Services\WidgetManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class WidgetController extends Controller
{
    protected $widgetManager;

    public function __construct(WidgetManager $widgetManager = null)
    {
        $this->widgetManager = $widgetManager ?? app(WidgetManager::class);
        //$this->middleware('auth'); Denne gir feilmelding. Rutene er beskyttet bak tilsvarende middleware.
    }

    /**
     * Display a listing of available widgets
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $widgets = Widget::where('is_active', true)->orderBy('name')->get();
        $positions = $this->getAvailablePositions();
        $currentWidgets = WidgetPosition::with('widget')->orderBy('position_key')->get();
        
        return view('admin.configurations.widgets.index', [
            'widgets' => $widgets,
            'positions' => $positions,
            'currentWidgets' => $currentWidgets
        ]);
    }

    /**
     * Configure widgets for a specific route
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function configure(Request $request)
    {
        $route = $request->get('route', 'dashboard');
        $widgets = Widget::active()->get()->groupBy('category');
        
        $activeWidgets = WidgetPosition::where('route', $route)
            ->active()
            ->with('widget')
            ->orderBy('position_key')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('position_key');

        $availableRoutes = $this->getAvailableRoutes();
        $positionKeys = $this->getAvailablePositions();

        return view('admin.configurations.widgets.configure', [
            'widgets' => $widgets, 
            'activeWidgets' => $activeWidgets, 
            'route' => $route, 
            'availableRoutes' => $availableRoutes, 
            'positionKeys' => $positionKeys
        ]);
    }

    /**
     * Add a widget to a position
     */
    public function addWidget(Request $request)
    {
        $request->validate([
            'widget_id' => 'required|exists:widgets,id',
            'route' => 'required|string',
            'position_key' => 'required|string',
            'size' => 'required|in:small,medium,large,full'
        ]);

        // Check if widget already exists in this position
        $existing = WidgetPosition::where('widget_id', $request->widget_id)
            ->where('route', $request->route)
            ->where('position_key', $request->position_key)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false, 
                'message' => 'Widget eksisterer allerede på denne posisjonen'
            ], 422);
        }

        WidgetPosition::create([
            'widget_id' => $request->widget_id,
            'route' => $request->route,
            'position_key' => $request->position_key,
            'size' => $request->size,
            'is_active' => true,
            'settings' => $request->settings ?? []
        ]);

        return response()->json(['success' => true, 'message' => 'Widget added']);
    }

    /**
     * Remove widget from position
     */
    public function removeWidget(WidgetPosition $widgetPosition)
    {
        $widgetPosition->delete();
        return response()->json(['success' => true, 'message' => 'Widget removed']);
    }

    /**
     * Update widget order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:widget_positions,id',
            'positions.*.order' => 'required|integer'
        ]);

        foreach ($request->positions as $position) {
            WidgetPosition::where('id', $position['id'])
                ->update(['sort_order' => $position['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated']);
    }

    /**
     * Update widget settings
     * 
     * @param Request $request
     * @param WidgetPosition|int $widgetPosition (Route model binding or ID)
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request, $widgetPosition)
    {
        try {
            // Handle both route model binding and manual ID
            if (!($widgetPosition instanceof WidgetPosition)) {
                $widgetPosition = WidgetPosition::findOrFail($widgetPosition);
            }
            
            $request->validate([
                'settings' => 'array',
                'size' => 'sometimes|required|in:small,medium,large,full',
                'is_active' => 'sometimes|boolean'
            ]);

            // Get widget
            $widget = $widgetPosition->widget;
            
            // Check if it's a module-based widget
            if ($widget && str_starts_with($widget->module, 'td-')) {
                $settings = $this->getModuleWidgetSettings($widget, $widgetPosition, $request);
            } else {
                $settings = $request->input('settings', $widgetPosition->settings);
            }

            // Update settings
            $widgetPosition->settings = $settings;
            
            // Update size if provided
            if ($request->has('size')) {
                $widgetPosition->size = $request->size;
            }
            
            // Update active state if provided
            if ($request->has('is_active')) {
                $widgetPosition->is_active = $request->boolean('is_active');
            }
            
            $widgetPosition->save();
            
            // Return JSON response for API requests, redirect for form submissions
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Widget settings updated']);
            }
            
            return redirect()->route('admin.configurations.widgets.index')
                ->with('success', 'Widget settings updated.');
        } catch (\Exception $e) {
            Log::error('Failed to update widget settings: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update widget settings'], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update widget settings. ' . $e->getMessage());
        }
    }
    
    /**
     * Get settings from a module-based widget controller
     * 
     * @param Widget $widget
     * @param WidgetPosition $widgetPosition
     * @param Request $request
     * @return array
     */
    protected function getModuleWidgetSettings(Widget $widget, WidgetPosition $widgetPosition, Request $request)
    {
        // Format the module name for namespace
        $moduleBase = ucfirst(str_replace('-', '', $widget->module));
        
        // Parse the view path to get the widget name
        $viewPathParts = explode('.', $widget->view_path);
        $viewName = end($viewPathParts);
        
        // Build controller class path based on module
        $controllerClass = "\\Modules\\{$moduleBase}\\Controllers\\{$moduleBase}WidgetController";
        
        if (class_exists($controllerClass)) {
            try {
                $controller = app($controllerClass);
                
                // Try to find configure method
                if (method_exists($controller, 'configure')) {
                    return $controller->configure($widgetPosition, $request);
                }
                
                // Try widget-specific configuration method
                $methodName = 'configure' . str_replace('-', '', ucfirst($viewName));
                if (method_exists($controller, $methodName)) {
                    return $controller->{$methodName}($widgetPosition, $request);
                }
            } catch (\Exception $e) {
                Log::error("Module widget configuration error: " . $e->getMessage());
            }
        }
        
        // Fallback to direct request input
        return $request->input('settings', $widgetPosition->settings);
    }

    /**
     * Hent tilgjengelige ruter fra Laravel
     */
    private function getAvailableRoutes()
    {
        $routes = [];
        
        // Hent alle navngitte ruter
        foreach (Route::getRoutes()->getRoutesByName() as $name => $route) {
            // Filtrer ut admin-ruter og API-ruter
            if (!str_starts_with($name, 'admin.') && 
                !str_starts_with($name, 'api.') && 
                in_array('GET', $route->methods())) {
                
                $routes[$name] = ucfirst(str_replace(['.', '-', '_'], ' ', $name));
            }
        }

        // Legg til manuelle ruter
        $manualRoutes = [
            'dashboard' => 'Dashboard',
            'profile' => 'Profil',
            'home' => 'Hjem'
        ];

        return array_merge($manualRoutes, $routes);
    }

    /**
     * Add a widget to a position
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToPosition(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'widget_id' => 'required|exists:widgets,id',
            'position_key' => 'required|string|max:50',
            'route' => 'nullable|string|max:100',
            'size' => 'required|in:small,medium,large,full',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $route = $request->input('route') ?? '*';
        
        // Get the highest sort_order for this position and route
        $maxOrder = WidgetPosition::where('position_key', $request->position_key)
            ->where('route', $route)
            ->max('sort_order') ?? 0;
        
        try {
            $position = new WidgetPosition();
            $position->widget_id = $request->widget_id;
            $position->position_key = $request->position_key;
            $position->route = $route;
            $position->size = $request->size;
            $position->is_active = true;
            $position->sort_order = $maxOrder + 10; // Leave gaps for manual reordering
            $position->settings = [];
            $position->save();

            return redirect()->route('admin.configurations.widgets.index')
                ->with('success', 'Widget added to position successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to add widget to position: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to add widget to position. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Fjern widget fra posisjon
     */
    public function removeFromPosition($id)
    {
        try {
            $position = WidgetPosition::findOrFail($id);
            $position->delete();
            
            return redirect()->route('admin.configurations.widgets.index')
                ->with('success', 'Widget removed from position.');
        } catch (\Exception $e) {
            Log::error('Failed to remove widget from position: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to remove widget from position. ' . $e->getMessage());
        }
    }

    /**
     * NOTE: Den tidligere dupliserte updateSettings-metoden er fjernet
     * Den kombinerte metoden finnes nå tidligere i filen med typehinting for både ID og WidgetPosition-objekter
     */

    /**
     * Toggle widget position active state
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function togglePosition($id)
    {
        try {
            $position = WidgetPosition::findOrFail($id);
            $position->is_active = !$position->is_active;
            $position->save();
            
            $status = $position->is_active ? 'activated' : 'deactivated';
            
            return redirect()->route('admin.configurations.widgets.index')
                ->with('success', "Widget position {$status} successfully.");
        } catch (\Exception $e) {
            Log::error('Failed to toggle widget position: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to toggle widget position. ' . $e->getMessage());
        }
    }

    /**
     * Oppdater rekkefølge på widgets
     */
    public function reorderWidgets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:widget_positions,id',
            'positions.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            foreach ($request->positions as $item) {
                $position = WidgetPosition::find($item['id']);
                if ($position) {
                    $position->sort_order = $item['order'] * 10;
                    $position->save();
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to reorder widgets: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to reorder widgets'], 500);
        }
    }

    /**
     * Get available positions for widgets
     *
     * @return array
     */
    private function getAvailablePositions()
    {
        if ($this->widgetManager) {
            return $this->widgetManager->getAvailablePositions();
        }
        
        // Fallback if WidgetManager is not available
        return [
            'widget_header_right' => 'Header Right',
            'widget_sidebar' => 'Sidebar',
            'widget_pageHeader_right' => 'Page Header Right',
        ];
    }

    /**
     * Preview widget
     */
    public function preview(Widget $widget, Request $request)
    {
        $settings = $request->get('settings', []);
        $mergedSettings = array_merge($widget->default_settings, $settings);
        
        try {
            // Get preview data for module-based widgets
            $data = [];
            if (str_starts_with($widget->module, 'td-')) {
                $data = $this->getModuleWidgetPreviewData($widget, $mergedSettings);
            }
            
            // Render widget with settings and preview data
            $html = view($widget->view_path, [
                'data' => $data,
                'settings' => $mergedSettings,
                'preview' => true
            ])->render();

            return response()->json(['success' => true, 'html' => $html]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Could not preview widget: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get preview data from a module-based widget controller
     * 
     * @param Widget $widget
     * @param array $settings
     * @return array
     */
    protected function getModuleWidgetPreviewData(Widget $widget, array $settings = [])
    {
        // Format the module name for namespace
        $moduleBase = ucfirst(str_replace('-', '', $widget->module));
        
        // Parse the view path to get the widget name
        $viewPathParts = explode('.', $widget->view_path);
        $viewName = end($viewPathParts);
        
        // Method name for preview data
        $methodName = 'getPreviewData';
        $specificMethodName = 'get' . str_replace('-', '', ucfirst($viewName)) . 'PreviewData';
        
        // Build controller class path based on module
        $controllerClass = "\\Modules\\{$moduleBase}\\Controllers\\{$moduleBase}WidgetController";
        
        if (class_exists($controllerClass)) {
            try {
                $controller = app($controllerClass);
                
                // Try specific preview method first
                if (method_exists($controller, $specificMethodName)) {
                    return $controller->{$specificMethodName}($settings);
                }
                
                // Try generic preview method
                if (method_exists($controller, $methodName)) {
                    return $controller->{$methodName}($viewName, $settings);
                }
                
                // Fallback to regular data method if available
                $dataMethodName = 'get' . str_replace('-', '', ucfirst($viewName)) . 'Data';
                if (method_exists($controller, $dataMethodName)) {
                    return $controller->{$dataMethodName}($settings);
                }
            } catch (\Exception $e) {
                Log::error("Module widget preview error: " . $e->getMessage());
            }
        }
        
        return [];
    }
    
    /**
     * Refresh a widget with current data
     * 
     * @param WidgetPosition $widgetPosition
     * @return \Illuminate\Http\Response
     */
    public function refreshWidget(WidgetPosition $widgetPosition)
    {
        try {
            // Skip if widget is not found or inactive
            if (!$widgetPosition->widget || !$widgetPosition->widget->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Widget not found or inactive'
                ], 404);
            }
            
            // Skip if user doesn't have permission
            if ($widgetPosition->widget->requires_auth && 
                !$widgetPosition->widget->hasAccess(auth()->user())) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to view this widget'
                ], 403);
            }
            
            // Get data for the widget
            $data = [];
            if (str_starts_with($widgetPosition->widget->module, 'td-')) {
                $data = $this->getModuleWidgetData($widgetPosition->widget, $widgetPosition->settings ?? []);
            } else {
                $data = $this->widgetManager->getWidgetData($widgetPosition->widget, $widgetPosition->settings ?? []);
            }
            
            // Merge default settings with instance settings
            $settings = array_merge(
                $widgetPosition->widget->default_settings ?: [],
                $widgetPosition->settings ?: []
            );
            
            // Render the widget view
            $html = view($widgetPosition->widget->view_path, [
                'widget' => $widgetPosition->widget,
                'settings' => $settings,
                'size' => $widgetPosition->size,
                'data' => $data,
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to refresh widget: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh widget: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get data from module widget controller
     */
    protected function getModuleWidgetData(Widget $widget, array $settings = [])
    {
        // This is a simplified version of the method in WidgetManager
        // Format the module name for namespace
        $moduleBase = ucfirst(str_replace('-', '', $widget->module));
        
        // Parse the view path to get the widget name
        $viewPathParts = explode('.', $widget->view_path);
        $viewName = end($viewPathParts);
        
        // Format the method name
        $methodName = 'get' . str_replace('-', '', ucfirst($viewName)) . 'Data';
        
        // Build controller class path based on module
        $controllerClass = "\\Modules\\{$moduleBase}\\Controllers\\{$moduleBase}WidgetController";
        
        if (class_exists($controllerClass)) {
            try {
                $controller = app($controllerClass);
                if (method_exists($controller, $methodName)) {
                    return $controller->$methodName($settings);
                }
                
                // Try generic getData method
                if (method_exists($controller, 'getData')) {
                    return $controller->getData($viewName, $settings);
                }
            } catch (\Exception $e) {
                Log::error("Module widget data error: " . $e->getMessage());
            }
        }
        
        return [];
    }
}
