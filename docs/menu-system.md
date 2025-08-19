# TaskHub Menu System

## Overview

The TaskHub menu system provides a flexible way to register, organize, and display navigation menus throughout the application. Modules can register their own menu items that integrate seamlessly with the core navigation structure.

## Database Structure

### Tables

#### `menus`

Central repository for different menu collections:

| Field | Type | Description |
|-------|------|-------------|
| `id` | bigint | Primary key |
| `name` | string | Menu name |
| `slug` | string | Unique identifier |
| `url` | string(30) | Optional URL |
| `description` | string | Optional description |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Update timestamp |

The default installation creates an "admin_settings" menu with ID 1, which serves as the main admin navigation.

#### `menu_items`

Individual navigation entries that can be hierarchical:

| Field | Type | Description |
|-------|------|-------------|
| `id` | bigint | Primary key |
| `title` | string | Display name |
| `url` | string | Target URL |
| `menu_id` | bigint | Parent menu ID (from `menus` table) |
| `parent_id` | bigint | Parent menu item ID (for nested items) |
| `icon` | string | Icon class (typically Bootstrap Icons) |
| `is_parent` | boolean | Whether this item has child items |
| `order` | integer | Display order |
| `module` | string | Module that owns this item (for cleanup) |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Update timestamp |

## Models

### `Menu` Model

Represents a collection of menu items:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'url'];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
```

### `MenuItem` Model

Represents individual navigation links:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['menu_id', 'parent_id', 'title', 'url', 'order'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }
}
```

## Adding Menu Items from Modules

Modules should register their menu items in migrations. This ensures proper setup when the module is installed.

### Example Migration

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddModuleMenuItem extends Migration
{
    public function up()
    {
        // Check if the menu item already exists
        $existingItem = DB::table('menu_items')
            ->where('menu_id', 1)  // Admin menu always has ID 1
            ->where('title', 'My Feature')
            ->first();

        // If it doesn't exist, insert the new menu item
        if (!$existingItem) {
            DB::table('menu_items')->insert([
                'menu_id' => 1,  // Admin menu ID
                'parent_id' => null,  // Main menu, not a sub-item (or parent ID for sub-menu)
                'title' => 'My Feature',
                'url' => '/admin/my-feature',
                'icon' => 'bi bi-star',  // Bootstrap Icons class
                'is_parent' => false,  // Set to true if this will have child items
                'order' => 50,  // Position in the menu
                'module' => 'td-my-module',  // IMPORTANT: Module slug for proper cleanup
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        // Remove the menu item on rollback
        DB::table('menu_items')
            ->where('menu_id', 1)
            ->where('title', 'My Feature')
            ->delete();
    }
}
```

### Important Guidelines

1. **Always set the `module` field** to your module's slug. This allows the system to:
   - Hide menu items when a module is disabled
   - Remove menu items when a module is uninstalled

2. **Use proper hierarchy**:
   - Top-level items: Set `parent_id` to `null`
   - Sub-items: Set `parent_id` to the ID of the parent menu item

3. **Check for existing items** before creating to prevent duplicates during reinstalls.

4. **Use Bootstrap Icons** (`bi bi-*` classes) for consistency with the rest of TaskHub.

5. **Consider menu order** to place your item logically among other navigation items.

## Menu Rendering

The menu system automatically loads menu data into all views through the base Controller class:

```php
namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;

abstract class Controller
{
    public function __construct()
    {
        // Fetch all main menus with their menu items
        $menus = Menu::with(['items' => function ($query) {
            $query->whereNull('parent_id')->with('children');
        }])->get();

        // Share menus with all views
        View::share('menus', $menus);
    }
}
```

This means that `$menus` is available in all views, ready to be rendered in the navigation.

## Best Practices

1. **Use descriptive titles** that clearly indicate the feature's purpose.

2. **Keep URLs consistent** with your module's routing scheme.

3. **Add your menu items in migrations** rather than service providers to ensure proper installation/uninstallation.

4. **Keep the menu hierarchy logical** - don't create deeply nested structures.

5. **Always use the `module` field** to ensure your menu items are properly managed during module lifecycle events.

## Example: Complete Module Integration

In your module's migration:

```php
// Top-level menu item
$menuItem = DB::table('menu_items')->insertGetId([
    'menu_id' => 1,
    'parent_id' => null,
    'title' => 'Task Manager',
    'url' => '/admin/tasks',
    'icon' => 'bi bi-list-check',
    'is_parent' => true,
    'order' => 30,
    'module' => 'td-tasks',
    'created_at' => now(),
    'updated_at' => now(),
]);

// Sub-menu items
DB::table('menu_items')->insert([
    [
        'menu_id' => 1,
        'parent_id' => $menuItem,
        'title' => 'All Tasks',
        'url' => '/admin/tasks/list',
        'icon' => 'bi bi-list-ul',
        'is_parent' => false,
        'order' => 10,
        'module' => 'td-tasks',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'menu_id' => 1,
        'parent_id' => $menuItem,
        'title' => 'Create Task',
        'url' => '/admin/tasks/create',
        'icon' => 'bi bi-plus-circle',
        'is_parent' => false,
        'order' => 20,
        'module' => 'td-tasks',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
```

This creates a hierarchical menu structure for your module that will be properly maintained throughout the module's lifecycle.
