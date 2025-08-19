# TaskHub Modules – Developer Guide

> **Version:** 1.0  |  **Last updated:** 2025‑07‑17

This guide covers everything a developer needs to know to create, s---

## 9  Routing & Controllers

Use a unique prefix to avoid global conflicts:

```php
Rout---

## 11  Data---

## 12  Configuration

Default values live in `Config/<slug>.php`; admins can override via GUI.

---

## 13  Testing & CI

- Place unit tests in `tests/` and run with `php artisan test`.
- Include `lang:lint` and `phpstan analyse` in CI.

---

## 14  Git Workflow
- Always guard against existing tables with `if (!Schema::hasTable('…'))`.
- Prefer reusing TaskHub shared tables (`meta_data`, `comments`, etc.) when possible.

---

## 12  Configurationare(['web', 'auth'])
     ->prefix('td-<slug>')
     ->name('td-<slug>.')
     ->group(function () {
         Route::get('/', [HomeController::class, 'index'])
              ->middleware('can:td-<slug>.view')
              ->name('index');
     });
```

---

## 10  Menu Integration

### 10.1  Registering Menu Items

Modules must register menu items with the `module` field to enable proper cleanup:

```php
use App\Models\MenuItem;

// In your ServiceProvider boot() method or seeder
MenuItem::create([
    'title' => 'My Module',
    'url' => '/admin/td-<slug>',
    'menu_id' => 1,  // 1 = Admin menu
    'parent_id' => null,  // Top-level item
    'icon' => 'bi bi-puzzle',
    'order' => 100,
    'module' => 'td-<slug>',  // CRITICAL: Must match your module slug
]);

// For sub-menu items
MenuItem::create([
    'title' => 'Settings',
    'url' => '/admin/td-<slug>/settings',
    'menu_id' => 1,
    'parent_id' => $parentMenuItem->id,
    'icon' => 'bi bi-gear',
    'order' => 1,
    'module' => 'td-<slug>',  // Same module slug
]);
```

### 10.2  Menu Cleanup Rules

- **Module disabled:** Menu items are hidden (`is_active = false`)
- **Module uninstalled:** Menu items are permanently deleted
- **Core items:** Never set `module` field (leave NULL)

### 10.3  Migration Example

```php
// In your module's migration or seeder
DB::table('menu_items')->insert([
    'title' => 'Task Management',
    'url' => '/admin/td-tasks',
    'menu_id' => 1,
    'parent_id' => null,
    'icon' => 'bi bi-check2-square',
    'order' => 50,
    'module' => 'td-tasks',  // Required for proper cleanup
    'created_at' => now(),
    'updated_at' => now(),
]);
```

---

## 11  Database MigrationsskHub modules.

---

## 1  What Is a Module?

A **module** is a self‑contained Laravel package that plugs into TaskHub to add new features, pages, widgets, or API endpoints. Administrators can enable, disable, upgrade, or uninstall modules at runtime.

---

## 2  Standard Directory Layout

```
modules/td‑<slug>/
├── module.json               # Metadata & permissions
├── composer.json             # PSR‑4, dependencies
├── README.md                 # Usage & docs link
├── docs/                     # Extended documentation
├── src/
│   ├── Providers/<Slug>ServiceProvider.php
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Livewire/
│   ├── Models/
│   ├── Resources/
│   │   ├── views/
│   │   └── lang/<locale>/messages.php
│   ├── Routes/web.php
│   └── Config/<slug>.php
├── database/migrations/
└── public/                   # CSS/JS/images (optional)
```

---

## 3  Mandatory Files

| File                | Purpose                                                                                      |
| ------------------- | -------------------------------------------------------------------------------------------- |
| **module.json**     | Declares name, version, dependency list, permissions, widgets, and any required `.env` keys. |
| **composer.json**   | Registers PSR‑4 autoload, external packages, and package name (`tronderdata/td‑<slug>`).     |
| **ServiceProvider** | Boots routes, views, translations, migrations, config, menus, and widgets.                   |

---

## 4  Language Support *(NEW)*

Every module **must** follow TaskHub’s file‑based i18n standard. See `langue.md` for the full spec.

### 4.1  Location & Naming

```
Modules/td‑<slug>/Resources/lang/{locale}/messages.php
```

- Small modules = a single `messages.php` (or `messages.json`).
- Text‑heavy features may add extra files, e.g. `emails.php`.

### 4.2  Namespace Keys

Use the module namespace to avoid collisions:

```php
__('td‑<slug>::messages.welcome');
```

### 4.3  Minimum Requirement

Ship **English (`en`) as default**. Other locales are optional; missing keys automatically fall back to English.

### 4.4  CLI Helpers

| Command                            | What it does                                 |
| ---------------------------------- | -------------------------------------------- |
| `php artisan lang:sync <locale>`   | Creates empty keys for a new locale.         |
| `php artisan lang:lint`            | Fails CI if default locale has empty values. |
| `php artisan lang:export <locale>` | Dumps keys/values to CSV for translators.    |

### 4.5  GUI Integration

The built‑in Translation Editor automatically scans module files. No extra code required.

---

## 5  Dependencies & Compatibility

Declare hard requirements and optional integrations in **module.json**:

```json
{
  "depends_on": ["td‑clients >=1.2", "core >=11"],
  "compatible_with": ["td‑nextcloud"]
}
```

TaskHub warns admins if they disable a module that others need.

---

## 6  ServiceProvider Quick‑Start

```php
public function boot(): void
{
    // Routes, views, migrations
    $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    $this->loadViewsFrom(__DIR__.'/../Resources/views', 'td-<slug>');
    $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

    // Config merge
    $this->mergeConfigFrom(__DIR__.'/../Config/<slug>.php', '<slug>');

    // Translations (namespace = td-<slug>)
    $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'td-<slug>');

    // Menu & Widget registration (optional)
    Menu::register([...]);
    Widget::register([...]);
}
```

---

## 7  Assets (CSS / JS / Images)

Publish to the public folder:

```php
$this->publishes([
    __DIR__.'/../../public' => public_path('vendor/td-<slug>'),
], 'public');
```

---

## 8  Project Registration

Add the module repo to **composer.json** and install:

```json
"repositories": [
  {"type": "path", "url": "./modules/td-<slug>"}
],
"require": {
  "tronderdata/td-<slug>": "dev-main"
}
```

```bash
composer update tronderdata/td-<slug>
php artisan config:clear
```

---

## 9  Routing & Controllers

Use a unique prefix to avoid global conflicts:

```php
Route::middleware(['web', 'auth'])
     ->prefix('td-<slug>')
     ->name('td-<slug>.')
     ->group(function () {
         Route::get('/', [HomeController::class, 'index'])
              ->middleware('can:td-<slug>.view')
              ->name('index');
     });
```

---

## 10  Database Migrations

- Always guard against existing tables with `if (!Schema::hasTable('…'))`.
- Prefer reusing TaskHub shared tables (`meta_data`, `comments`, etc.) when possible.

---

## 11  Configuration

Default values live in `Config/<slug>.php`; admins can override via GUI.

---

## 12  Testing & CI

- Place unit tests in `tests/` and run with `php artisan test`.
- Include `lang:lint` and `phpstan analyse` in CI.

---

## 13  Git Workflow

- **One feature per branch**.
- Semantic commit messages.
- Submit PRs for review before merging to `main`.

---

© 2025 Trønder Data AS — Happy coding!
