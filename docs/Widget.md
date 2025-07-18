# 🧩 TaskHub Widget System Documentation

This documentation explains the full implementation, usage, and development standards of the Widget System in TaskHub.

## 📑 Table of Contents

- [Introduction](#introduction)
- [Concept & Architecture](#concept--architecture)
- [Widget Lifecycle](#widget-lifecycle)
- [Widget Positions](#widget-positions)
- [Creating Widgets](#creating-widgets)
- [Rendering Widgets](#rendering-widgets)
- [Widget Configuration](#widget-configuration)
- [Access Control & Permissions](#access-control--permissions)
- [Dynamic & AJAX Refresh](#dynamic--ajax-refresh)
- [Routing Conventions](#routing-conventions)
- [Styling and View Best Practices](#styling-and-view-best-practices)
- [Debugging & Troubleshooting](#debugging--troubleshooting)
- [References](#references)

---

## 🧠 Introduction

TaskHub's widget system allows modules and the core application to inject self-contained, reusable interface components into predefined screen regions. Widgets support:

- Role-based visibility
- Configuration per instance
- Responsive layout sizes
- Modular rendering logic
- Automatic AJAX refresh

---

## 🏛️ Concept & Architecture

### Core Models

| Model | Purpose |
|-------|---------|
| `Widget` | Describes the widget’s type, permissions, and configuration |
| `WidgetPosition` | Maps a widget instance to a layout position and route |

### Core Services

- `WidgetManager` (`app/Services/WidgetManager.php`)  
  Handles widget discovery, rendering, and interaction.

### Rendering Layer

- Livewire component: `widgets.position-renderer`
- Blade views: `resources/views/livewire/widgets/position-renderer.blade.php`

---

## 🔄 Widget Lifecycle

1. **Definition**: Created in DB or registered via module ServiceProvider
2. **Assignment**: Added to a layout position via admin GUI
3. **Configuration**: Optional per-instance settings
4. **Rendering**: Called via Livewire or `WidgetManager`
5. **Refreshing**: Optionally auto-updated via AJAX

---

## 📌 Widget Positions

Widgets are rendered in defined layout positions. These are managed dynamically and stored in the database.

### Default Positions

| Position Key | Placement |
|--------------|-----------|
| `widget_header_right` | Top-right page header |
| `widget_sidebar` | Under sidebar menu |
| `widget_pageHeader_right` | Inside main content header |

---

## 🛠️ Creating Widgets

Widgets can originate from core or module-specific logic.

### Minimum Requirements

- A Blade view (with optional Livewire support)
- Optional controller method to fetch data
- Optional configuration logic
- Registration via DB or service provider

### Core Widget Example

**Blade View**:  
`resources/views/widgets/system-stats.blade.php`

```blade
<x-widget :title="$settings['title']" :size="$size" :widget="$widget">
    <div class="stats">
        <strong>Total Users:</strong> {{ $data['total_users'] ?? 0 }}
    </div>
</x-widget>
```

**Widget Registration**:

```php
Widget::create([
    'name' => 'System Stats',
    'view_path' => 'widgets.system-stats',
    'module' => 'core',
    'permissions' => ['view-system-stats'],
    'is_configurable' => true,
    'default_settings' => [
        'title' => 'System Overview'
    ],
]);
```

---

## 🧱 Module-Based Widgets

### Directory Structure

```
modules/td-example/
├── Controllers/ExampleWidgetController.php
├── Resources/views/widgets/example-widget.blade.php
└── Providers/ExampleServiceProvider.php
```

### ServiceProvider Registration

```php
Widget::firstOrCreate([
    'view_path' => 'td-example::widgets.example-widget',
], [
    'name' => 'Example Widget',
    'module' => 'td-example',
    'is_configurable' => true,
    'permissions' => ['view-td-example'],
]);
```

---

## 🖼️ Rendering Widgets

### Preferred: Livewire

```blade
<livewire:widgets.position-renderer position="widget_sidebar" :route="Route::currentRouteName()" />
```

### Alternative: Direct Service Call

```php
{!! app(\App\Services\WidgetManager::class)->render('widget_sidebar', 'dashboard') !!}
```

---

## ⚙️ Widget Configuration

Each widget may define default settings and expose configuration through a controller.

### Example Default Settings

```php
'default_settings' => [
    'title' => 'Recent Tickets',
    'show_count' => true,
    'refresh_interval' => 60000
]
```

### Available Setting Types

- String / Text
- Boolean
- Integer
- Select (enum)
- JSON objects (advanced)

### Settings View Example

```blade
@php
$limit = $settings['limit'] ?? 10;
@endphp

<ul>
    @foreach(array_slice($data, 0, $limit) as $item)
        <li>{{ $item['title'] }}</li>
    @endforeach
</ul>
```

---

## 🔐 Access Control & Permissions

Widgets respect TaskHub’s permission system via Spatie.

### Defining Widget Permissions

```php
'permissions' => ['view-client-stats']
```

### Permission Check in Blade

```blade
@can('view-client-stats')
    <x-widget ... />
@endcan
```

---

## 🔁 Dynamic & AJAX Refresh

Widgets may define a `refresh_interval` and auto-update via JavaScript.

### Markup Example

```html
<div class="widget"
     data-position-id="{{ $widgetPosition->id }}"
     data-auto-refresh="true"
     data-refresh-interval="30000">
</div>
```

### Script

```javascript
function refreshWidget(id) {
  fetch(`/admin/configurations/widgets/refresh/${id}`)
    .then(r => r.json())
    .then(d => {
      document.querySelector(`[data-position-id="${id}"]`).innerHTML = d.html;
    });
}
```

---

## 🛰️ Routing Conventions

Widget management routes are defined under:

```php
Route::prefix('admin/configurations/widgets')->group(function () {
    Route::get('/', 'index')->name('admin.configurations.widgets.index');
    Route::get('/configure', 'configure');
    Route::post('/store', 'store');
    Route::put('/{widgetPosition}', 'update');
    Route::delete('/{widgetPosition}', 'destroy');
    Route::get('/refresh/{widgetPosition}', 'refreshWidget');
});
```

---

## 🧾 Styling and View Best Practices

- Use `<x-widget>` for consistent layout
- Leverage Bootstrap classes for responsive layout
- Avoid global CSS collisions
- Fallback content for empty datasets
- Use `$preview`, `$size`, `$settings`, `$data`, `$widget` in views

---

## 🐛 Debugging & Troubleshooting

### Widget Not Appearing?

- Check if widget is active in DB
- Check permissions
- Verify Blade path exists
- Ensure route name matches

### Errors in View?

- Look in `storage/logs/laravel.log`
- Validate all variables exist
- Test isolated view in route

### CSS/JS Conflicts?

- Prefix classes (e.g., `td-mywidget`)
- Avoid inline scripts
- Use scoped JavaScript

---

## 📚 References

- [Laravel Blade](https://laravel.com/docs/blade)
- [Laravel Livewire](https://livewire.laravel.com/docs)
- [Spatie Permissions](https://spatie.be/docs/laravel-permission)
- [Bootstrap](https://getbootstrap.com/)
- [TaskHub Modules](./modules.md)
- [TaskHub Auth](./authentication.md)

---

*© 2025 TrønderData AS – TaskHub Widget System Guide*