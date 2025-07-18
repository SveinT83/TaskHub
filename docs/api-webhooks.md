# TaskHub – API & Webhook Documentation

This document provides an overview of the API and webhook architecture used in TaskHub, focusing on modular extensibility and best practices for both internal and third-party developers.

---

## 📡 Overview

TaskHub provides a hybrid API system consisting of:

- RESTful API endpoints served via Laravel controllers
- Module-specific routes and endpoints
- Optional webhook registration for outbound event notifications
- Planned GUI-based documentation view of all API/webhook endpoints per module

All APIs require **authentication** and most are **permission-guarded** using Spatie’s `can:` middleware or policies.

---

## 🔐 Authentication

All API access requires an API token issued via Laravel Sanctum.

### Token Usage

- Pass token in header:
  ```
  Authorization: Bearer {token}
  Accept: application/json
  ```

- Issued via standard login (or via OAuth in future)

---

## 🌐 REST API Endpoints (Core)

| Method | URI                          | Description                    |
|--------|------------------------------|--------------------------------|
| GET    | /api/user                    | Get current user data          |
| GET    | /api/menus                   | Get full menu tree             |
| GET    | /api/widgets                 | Get available widgets          |
| POST   | /api/widgets/{key}/configure | Update widget configuration    |

> Additional module-specific endpoints are defined in each module’s `routes/api.php` file.

---

## 🔌 Module API Endpoints

Each module can expose its own API routes by including a `routes/api.php` file.

### Best Practices

- Use `Route::prefix('api/{module}')` to namespace API routes
- Group routes inside:
  ```php
  Route::middleware(['api', 'auth:sanctum'])->group(function () {
      // Routes here
  });
  ```
- Avoid exposing routes without permission guards

---

## 📬 Webhooks

Webhooks allow TaskHub to notify external systems when certain events occur, such as:

- Ticket created
- Client added
- File uploaded
- Status changed

> The webhook system is currently **under design** and not yet part of core. Modules can implement their own logic for now.

### Planned Design

- A central `webhooks` table with:
  - `event` name
  - `target_url`
  - `module`
  - `enabled`
  - `secret` (for signature)

- Event dispatchers will check this table and send POST requests accordingly.
- Admin GUI for managing registered webhooks per event/module

---

## 🏦 GUI: API & Webhook Explorer

A planned GUI will let administrators browse and test API endpoints and manage webhook events visually.

### Route & Component
- Route: `admin.api.index`
- Livewire Component: `App\Livewire\Admin\Api\Index`
- View: `resources/views/livewire/admin/api/index.blade.php`

### Features
- Table of all registered API endpoints
- Columns: HTTP method, URI, permission, description, module
- Webhook events listed per module
- Filter by module
- Toggle to show only authenticated or permission-restricted routes
- Future: Try-it-out mode with API token input

---

## 📁 File Structure & Module Conventions

```
modules/td-<slug>/
├── routes/
│   └── api.php                        # API routes
├── src/
│   ├── Http/Controllers/Api/         # API Controllers
│   ├── Events/                       # Optional: Event definitions
│   └── Listeners/                    # Optional: Webhook triggers
├── docs/
│   └── api.md                        # Optional: Markdown reference
├── module.json
```

---

## 🔍 module.json Format (Webhooks)

```json
{
  "webhooks": [
    {
      "event": "ticket.created",
      "label": "Ticket Created",
      "description": "Triggered when a new ticket is created",
      "permissions": ["tickets.view"]
    }
  ]
}
```

Modules should list available events so the admin panel can display them contextually.

---

## ✅ API Standards

- All endpoints must return JSON (`Accept: application/json`)
- Use consistent HTTP verbs:
  - GET for retrieval
  - POST for creation
  - PUT/PATCH for update
  - DELETE for removal
- Always return structured responses with `status`, `data`, and optional `message`

---

## 🧹 Integration Recommendations

- Use **Sanctum tokens** and **permission middleware** for security
- Follow naming conventions for route names: `{module}.{resource}.{action}`
- Provide `module.json` with documented routes & webhook support where applicable
- Consider registering API doc metadata in `docs/api.md`

---

## 📌 Example

```php
// routes/api.php in td-tickets
Route::prefix('api/tickets')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [TicketController::class, 'index'])->name('tickets.api.index');
    Route::post('/', [TicketController::class, 'store'])->middleware('can:tickets.create');
});
```

---

## ⚖️ Future Plans

- GUI for browsing and testing API endpoints (under development)
- Webhook retry queue and error log
- Token management panel for admin users
- SDK generation per module
- Support for GraphQL or JSON:API (TBD)

---

© 2025 TrønderData AS – TaskHub API/Webhook Specification
