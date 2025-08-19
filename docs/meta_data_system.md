# 📦 TaskHub Meta Data System

> **Audience**: Laravel/TaskHub developers using GitHub Copilot, building modules that extend TaskHub with flexible, entity-bound metadata.

This document explains the full meta data system in TaskHub. It enables modules and core features to attach custom key–value data to *any* model, with full traceability, field definitions, validation, GUI editing, and uninstall-aware cleanup.

**Think WordPress meta tables** – but for Laravel, with proper validation, GUI management, and module-aware cleanup.

---

## 🎯 What is the Meta Data System?

The Meta Data System allows you to:
- **Add custom fields** to any existing model without database migrations
- **Store module-specific data** that gets cleaned up when modules are uninstalled
- **Define field types and validation** once, use everywhere
- **Build dynamic forms** based on field definitions
- **Store configuration data** like WordPress `wp_options` table

### Real-world Examples:

**Client Management Module:**
```php
// Define fields once
MetaField::create([
    'key' => 'billing_contact_email',
    'label' => 'Billing Contact Email',
    'type' => 'string',
    'rules' => 'required|email',
    'module' => 'td-clients'
]);

// Use on any client
$client->setMetaValue('billing_contact_email', 'billing@acme.com', 'td-clients');
$client->setMetaValue('preferred_currency', 'NOK', 'td-clients');
```

**SMTP Configuration:**
```php
// Instead of a separate smtp_settings table
$site->setMetaValue('smtp_host', 'smtp.gmail.com', 'email-settings');
$site->setMetaValue('smtp_port', 587, 'email-settings');
$site->setMetaValue('smtp_credentials', ['user' => 'admin@site.com'], 'email-settings');
```

**User Preferences:**
```php
// WordPress user_meta equivalent
$user->setMetaValue('dashboard_layout', 'compact', 'core');
$user->setMetaValue('notification_settings', ['email' => true, 'sms' => false], 'notifications');
```

**Plugin/Module Settings:**
```php
// Like WordPress options table
$site->setMetaValue('maintenance_mode', true, 'core');
$site->setMetaValue('site_logo', '/uploads/logo.png', 'core');
```

**Transient/Cache-like Data:**
```php
$system->setMetaValue('api_cache_weather', $weatherData, 'weather-widget');
$system->setMetaValue('last_backup_timestamp', now(), 'backup-module');
```

**Feature Flags & A/B Testing:**
```php
$user->setMetaValue('beta_features_enabled', true, 'core');
$site->setMetaValue('new_ui_variant', 'v2', 'ab-testing');
```

---

## 📁 File Locations & Structure

### 📂 Backend
```
app/
├── Models/
│   └── Configurations/
│       └── Meta/
│           ├── MetaField.php
│           └── MetaData.php
├── Services/
│   └── MetaDataService.php
├── Livewire/
│   └── Admin/
│       └── Configurations/
│           └── Meta/
│               ├── Index.php
│               └── FieldManager.php
├── Http/
│   └── Controllers/
│       └── Admin/
│           └── Configurations/
│               └── Meta/
│                   └── MetaController.php
```

### 📂 Frontend Views
```
resources/views/admin/configurations/meta/
├── index.blade.php                     # Wrapper-view med layout

resources/views/livewire/admin/configurations/meta/
├── table.blade.php                     # Listevisning
├── form.blade.php                      # Skjema for add/edit
├── fields.blade.php                    # Definisjonsskjema for MetaField
```

### 📂 Migrations
```
database/migrations/
├── xxxx_xx_xx_create_meta_data_table.php
└── xxxx_xx_xx_create_meta_fields_table.php
```

### 📂 Routes (routes/admin.php)

Legg inn disse i din eksisterende rutestruktur i `routes/web.php`:

```php
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::prefix('configurations')->group(function () {
        Route::prefix('meta')->name('admin.configurations.meta.')->group(function () {
            Route::get('/{model}/{id}', \App\Livewire\Admin\Configurations\Meta\Index::class)->name('index');
            Route::get('/fields', \App\Livewire\Admin\Configurations\Meta\FieldManager::class)->name('fields');
        });
    });
});
```

> **NB:** Alle meta-ruter skal nå ligge i `routes/web.php` og ikke i en egen `admin.php`.

---

## 🧠 Table: `meta_data`

| Column        | Type     | Description                          |
|---------------|----------|--------------------------------------|
| id            | bigint   | Primary Key                          |
| parent_type   | string   | Full model class (e.g. `App\Models\Client`) |
| parent_id     | bigint   | Foreign key to model                 |
| key           | string   | Key for the field (matches MetaField.key) |
| value         | json     | Raw value (stored as JSON)           |
| module        | string   | Owning module (for cleanup)         |
| created_at    | timestamp| Created                              |
| updated_at    | timestamp| Updated                              |

---

## 📐 Table: `meta_fields`

| Column        | Type     | Description                              |
|---------------|----------|------------------------------------------|
| id            | bigint   | Primary Key                              |
| key           | string   | Unique key (snake_case recommended)      |
| label         | string   | Display label for GUI                    |
| description   | text     | Optional field help text                 |
| type          | string   | Data type (`string`, `int`, `boolean`, `json`, `select`) |
| rules         | string   | Laravel validation rules (optional)      |
| default_value | json     | Default value                            |
| options       | json     | For `select` type (key => label)         |
| module        | string   | Declaring module                         |
| created_at    | timestamp| Created                                  |
| updated_at    | timestamp| Updated                                  |

> Enables GUI-driven meta config, validation, and user-friendly display.

---

## 🛠️ Usage Examples (Code)

### Get all meta fields for a model
```php
$fields = MetaField::where('module', 'td-clients')->get();
```

### Save meta value via service (with validation)
```php
MetaDataService::save($model, 'internal_code', 'ABC123');
```

---

## 🖥️ Admin GUI (Livewire)

### Blade Wrapper:
```blade
{{-- resources/views/admin/configurations/meta/index.blade.php --}}
<x-admin-layout title="Metadata">
    <livewire:admin.configurations.meta.index :model="$model" :id="$id" />
</x-admin-layout>
```

### Komponentene gjør følgende:
- `Index`: viser og lar deg endre alle verdier på en modellinstans
- `FieldManager`: lar deg definere nye meta-felt i systemet

### Visningsfiler brukes internt i komponentene:
- `table.blade.php`: viser verdiene
- `form.blade.php`: add/edit-form
- `fields.blade.php`: for MetaField-definisjoner

---

## 🧹 Cleanup on Module Removal

Meta records og definisjoner kan fjernes via:
```php
MetaData::where('module', 'td-asset')->delete();
MetaField::where('module', 'td-asset')->delete();
```

---

## 🧪 Best Practices

- Alltid definer `MetaField` før du bruker `MetaData`
- Bruk `snake_case` for alle nøkler
- Sett `type` og `rules` for validering i GUI
- Bruk `options` for `select`-felter
- Bruk `module`-tagging for ryddig opprydding senere

---

## 🚧 Planned Enhancements

- [ ] GUI import/export av meta definitions
- [ ] API endpoints for CRUD (fields + values)
- [ ] Inline-felt på client/ticket-forms
- [ ] Field-level permissions / visibility

---

© 2025 Trønder Data AS — TaskHub Meta System
