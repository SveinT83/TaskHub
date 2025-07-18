<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - DASHBOARDCONTROLLER
//
// This controller is responsible for handling the dashboard related actions such as displaying the dashboard.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WidgetPosition;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $route = $request->route()->getName() ?? 'dashboard';
        
        // Hent aktive widgets for denne ruten, gruppert etter posisjon
        $widgetPositions = WidgetPosition::where('route', $route)
            ->where('is_active', true)
            ->with('widget')
            ->orderBy('position_key')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('position_key');

        // Render widgets med data
        $renderedWidgets = [];
        foreach ($widgetPositions as $position => $widgets) {
            $renderedWidgets[$position] = [];
            foreach ($widgets as $widgetPosition) {
                try {
                    $renderedWidgets[$position][] = [
                        'html' => $this->renderWidget($widgetPosition),
                        'size' => $widgetPosition->size,
                        'widget' => $widgetPosition->widget,
                        'position' => $widgetPosition
                    ];
                } catch (\Exception $e) {
                    // Log error og fortsett med neste widget
                    \Log::error("Failed to render widget {$widgetPosition->widget->name}: " . $e->getMessage());
                }
            }
        }

        return view('dashboard', compact('renderedWidgets', 'widgetPositions'));
    }

    /**
     * Render en enkelt widget
     */
    private function renderWidget(WidgetPosition $widgetPosition)
    {
        $widget = $widgetPosition->widget;
        
        // Sjekk tilgang til widget
        if (!$widget->hasAccess()) {
            return '<div class="alert alert-warning">Du har ikke tilgang til denne widgeten.</div>';
        }
        
        // Hent widget-data hvis nødvendig
        $data = $this->getWidgetData($widget, $widgetPosition->widget_settings);

        try {
            return view($widget->view_path, [
                'data' => $data,
                'settings' => $widgetPosition->widget_settings,
                'size' => $widgetPosition->size,
                'widget' => $widget
            ])->render();
        } catch (\Exception $e) {
            return view('partials.widget-error', [
                'widget' => $widget,
                'error' => $e->getMessage()
            ])->render();
        }
    }

    /**
     * Hent data for en widget (override i subklasser eller via service)
     */
    protected function getWidgetData($widget, $settings = [])
    {
        // Denne metoden kan utvides for å hente data basert på widget-type
        // For nå returnerer vi tomt array
        return [];
    }
}