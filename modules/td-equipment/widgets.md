# Widget Setup Documentation

## Introduction
This document provides instructions on how to set up and manage widgets in the application.

## Widget Model
The `Widget` model represents a widget in the system. It has the following attributes:
- `name`: The name of the widget.
- `description`: A brief description of the widget.
- `view_path`: The path to the Blade view file for the widget.
- `module`: The module to which the widget belongs.

## WidgetPosition Model
The `WidgetPosition` model represents the position of a widget on a specific route. It has the following attributes:
- `route`: The route where the widget should be displayed.
- `name`: The name of the widget position.
- `widget_id`: The ID of the associated widget.

## Storing Controllers in the Database
Controllers can be stored in the database to dynamically manage widget behavior. The `Controller` model has the following attributes:
- `name`: The name of the controller.
- `widget_id`: The ID of the associated widget.
- `method`: The method to be called on the controller.

## Creating a Widget
1. Create a new widget entry in the `widgets` table.
2. Define the widget's view in a Blade file. For example:
    ```blade
    <!-- filepath: /var/Projects/TaskHub/Dev/modules/td-equipment/src/Resources/views/widgets/equipmentsListWidget.blade.php -->
    <div class="widget">
        <h3>Equipment Widget</h3>
        <p>This is an example widget.</p>
    </div>
    ```

## Assigning a Widget to a Route
1. Create a new widget position entry in the `widget_positions` table.
2. Specify the route and the widget ID.

## Displaying Widgets on the Dashboard
The `DashboardController` is responsible for fetching and displaying widgets on the dashboard. It retrieves widgets assigned to the `/dashboard` route and passes them to the view.

Example:
```php
// filepath: /var/Projects/TaskHub/Dev/app/Http/Controllers/DashboardController.php
public function index()
{
    $widgets = WidgetPosition::where('route', '/dashboard')
        ->with('widget')
        ->get();

    return view('dashboard', ['widgets' => $widgets]);
}
```

## Conclusion
By following the steps outlined in this document, you can set up and manage widgets in the application effectively.
