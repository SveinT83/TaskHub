🛠️ TaskHub Admin Panel – Functional Overview

This document provides a comprehensive breakdown of the TaskHub administrative interface, its design principles, modularity, and how it ties into user permissions, configurations, and system maintenance.

📑 Table of Contents

Overview

Admin Menu Structure

Module Admin Integration

Best Practices

GUI-driven Configuration

Admin Widgets & Panels

Visibility Control

Planned Features

📋 Overview

The Admin Panel in TaskHub is the central interface for managing:

Global system settings

Module configurations

User roles and permissions

API/webhook management (planned)

Menu and widget placement

Access to the Admin Panel is controlled by role-based permissions. Only users with explicit admin rights (e.g., superadmin) will see and interact with admin features.

📁 Admin Menu Structure

TaskHub distinguishes between user-facing menu and admin-facing navigation using the menus and menu_items tables. Admin entries typically follow the naming convention:

admin.modulename

admin.configurations.modulename

admin.modules.modulename

admin.appearance.modulename

This taxonomy allows developers to logically group module interfaces:

Category

Description

admin

Root namespace

admin.configurations

Modules modifying core behavior

admin.modules

Administrative module views

admin.appearance

UI/UX-affecting modules

📦 Module Admin Integration

Each module is encouraged to register at least one admin menu item and a corresponding Blade/Livewire view.

Example Migration:

DB::table('menu_items')->insert([
  'menu_id' => $adminMenuId,
  'title' => 'My Module',
  'url' => '/admin/modules/mymodule',
  'icon' => 'bi bi-box',
  'order' => 10,
]);

Example Route:

Route::middleware(['web', 'auth', 'can:admin'])
  ->get('/admin/modules/mymodule', [AdminController::class, 'index'])
  ->name('admin.modules.mymodule');

💡 Best Practices

Register routes under /admin/... paths

Use permission gates for all admin routes

Use logical grouping for menu items (configurations, appearance, modules)

Document available admin routes in module.json

⚙️ GUI-driven Configuration

Settings that affect either the core or module-specific behavior should be manageable via a form-based interface in the admin panel.

Admin GUIs can store data in:

Dedicated settings table (settings)

A config/*.php file (read-only unless published)

A meta_data table (planned) for modular settings

🧩 Admin Widgets & Panels

Widgets registered by modules can also be rendered inside the admin area if explicitly configured to be placed in admin layouts (e.g., admin_dashboard).

Use the WidgetPosition model and GUI to place admin-specific widgets dynamically.

🔐 Visibility Control

Admin menu items, routes, and views are permission-guarded using Spatie’s permission package. For example:

@can('admin.modules.tickets')
  <x-admin-menu-item title="Tickets" route="admin.modules.tickets" />
@endcan

Developers are encouraged to define fine-grained permissions for admin routes, even within the same module.

🚧 Planned Features

Dynamic admin section detection via module.json

Configurable admin dashboard layout (drag/drop)

Theme switching per user or role (appearance)

Full history log per admin interaction (audit trail)

GUI permission editor with live preview

© 2025 TrønderData AS – TaskHub Admin GUI Specification