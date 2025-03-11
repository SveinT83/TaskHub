<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - DASHBOARDCONTROLLER
//
// This controller is responsible for handling the dashboard related actions such as displaying the dashboard.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Widgets\WidgetPosition;

class DashboardController extends Controller
{
    public function index()
    {
        // Hent widgets for dashboardet
        $widgets = WidgetPosition::where('route', '/dashboard')
            ->with('widget')
            ->get();

        // Debugging: Skriv ut widget data
        if ($widgets->isEmpty()) {
            // dd('No widgets found for route: /dashboard', $widgets);
        }

        // Dynamisk kall til kontrolleren og hent data
        $widgetData = [];
        foreach ($widgets as $widgetPosition) {
            $controllerAction = explode('@', $widgetPosition->widget->controller);
            $controller = app($controllerAction[0]);
            $action = $controllerAction[1];
            $widgetData[$widgetPosition->widget->id] = $controller->$action()->getData();
        }

        // Returner visningen med widgets og data
        return view('dashboard', ['widgets' => $widgets, 'widgetData' => $widgetData]);
    }
}