# TD Equipments Module Documentation

## Introduction
The "TD Equipments" module is designed to manage equipment within the application. It provides functionalities for creating, viewing, editing, and deleting equipment records. Additionally, it includes a widget to display equipment certification statuses on the dashboard.

## Features
- **Equipment Management**: Create, view, edit, and delete equipment records.
- **Service Management**: Track service history for each piece of equipment.
- **Widgets**: Display equipment-related information on the dashboard.

## Models
### Equipment Model
Represents an equipment item in the system. Key attributes include:
- `name`: The name of the equipment.
- `category_id`: The category to which the equipment belongs.
- `serial_number`: The serial number of the equipment.
- `status`: The current status of the equipment.
- `certification_month`: The month when the equipment needs certification.

### ServiceHistory Model
Represents the service history of an equipment item. Key attributes include:
- `equipment_id`: The ID of the associated equipment.
- `description`: A description of the service performed.
- `service_date`: The date when the service was performed.

## Controllers
### EquipmentController
Handles CRUD operations for equipment.
- `index()`: Lists all equipment.
- `create()`: Shows the form to create new equipment.
- `store()`: Saves new equipment to the database.
- `show($id)`: Displays a specific equipment item.
- `edit($id)`: Shows the form to edit an existing equipment item.
- `update($id)`: Updates an existing equipment item in the database.
- `destroy($id)`: Deletes an equipment item from the database.

### ServiceController
Handles CRUD operations for service history.
- `create($equipmentId)`: Shows the form to create a new service record for an equipment item.
- `store($equipmentId)`: Saves a new service record to the database.
- `destroy($serviceHistoryId)`: Deletes a service record from the database.

### WidgetController
Handles the display of widgets.
- `equipmentsListWidget()`: Displays a widget with equipment certification statuses.

## Routes
The module defines several routes for managing equipment and services. These routes are protected by middleware to ensure only authorized users can access them.

```php
// filepath: /var/Projects/TaskHub/Dev/modules/td-equipment/src/Routes/web.php
Route::middleware(['web', 'auth'])->group(function () {
    // Equipment routes
    Route::resource('equipment', EquipmentController::class)->except(['edit', 'update']);
    Route::get('equipment/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('equipment/{equipment}/update', [EquipmentController::class, 'update'])->name('equipment.update');

    // Service routes
    Route::resource('equipment.service', ServiceController::class)->only(['create', 'store', 'destroy']);

    // Widget routes
    Route::get('/widgets/equipments-list', [WidgetController::class, 'equipmentsListWidget'])->name('widgets.equipmentsListWidget');
});
```

## Views
The module includes several Blade views for displaying equipment and service information.

### Equipment Views
- **index.blade.php**: Lists all equipment.
- **show.blade.php**: Displays details of a specific equipment item.
- **create.blade.php**: Form to create new equipment.
- **edit.blade.php**: Form to edit existing equipment.

### Widget Views
- **equipmentsListWidget.blade.php**: Displays equipment certification statuses.

## Widget Setup
To display the equipment certification widget on the dashboard, follow these steps:
1. Ensure the widget is registered in the `widgets` table.
2. Assign the widget to the desired route in the `widget_positions` table.

Example migration to insert widget data:
```php
// filepath: /var/Projects/TaskHub/Dev/modules/td-equipment/database/migrations/2025_03_01_100007_insert_widget_row.php
public function up()
{
    $widgetId = DB::table('widgets')->insertGetId([
        'name' => 'Equipments List Widget',
        'description' => 'This widget displays a list of equipments.',
        'view_path' => 'widgets::equipmentsListWidget',
        'module' => 'td-equipment',
        'controller' => 'TronderData\Equipment\Http\Controllers\WidgetController@equipmentsListWidget',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('widget_positions')->insert([
        'route' => 'dashboard',
        'name' => 'main',
        'widget_id' => $widgetId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
```

## License
This module is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits
Developed by Trønder Data.
