TaskHub – Core (Hub) Documentation

Version: Dev branch as of 14 July 2025Stack: Laravel 11, PHP 8.2+, MySQL/MariaDB, Livewire 3, Vite + Bootstrap 5, Sanctum, Socialite, Spatie Permission

1 📚 What is TaskHub?

TaskHub is a monolithic Laravel application that acts as a central hub for multiple domain modules. The core provides shared infrastructure such as:

Authentication (email/password, OIDC/OAuth via Socialite) and API tokens (Sanctum)

RBAC roles and permissions (Spatie Permission)

CMS functionality: menu management, widgets, media library

Shared Livewire components, Blade layouts, message/notification bus

A REST API exposing user, menu and widget data

A plug‑in framework that allows external modules to register routes, menu items, migrations, policies, etc.

The hub therefore isolates domain logic into module packages while handling cross‑cutting concerns like security, layout and configuration itself.

2 🔧 Technology Overview

Layer

Tool

Purpose

Back‑end

Laravel 11 (minimal bootstrap)

HTTP engine, IoC, migrations



PHP ≥ 8.2

Language requirement



Spatie Permission v6

Roles/permissions



Sanctum v4

SPA & API tokens

Front‑end

Livewire 3

Reactive UI



Bootstrap 5 + Vite

Styling / bundling

Dev‑ops

Laravel Sail (Docker)

Local runtime



Pint / Pest / PHPUnit 11

Quality tools

3 🏗️ Architecture

3.1 Directory Structure

.
├─ app/               # Core only (User model, Policies, Providers)
├─ modules/           # Path packages for each domain module (not covered here)
├─ taskhub/           # Core library (menu, widget, helpers)
├─ routes/            # Empty – modules register their own routes
└─ database/          # Hub migrations (users, menus, widgets …)

Note: Laravel 11 runs in the modern single‑file bootstrap; route files are added via bootstrap/app.php.

3.2 Hub ⇄ Module Contract

Composer path repository: Every module is placed under modules/<slug> and listed in the root repositories section.

ServiceProvider convention: The module’s provider must:

register routes via $this->loadRoutesFrom()

publish migrations & views via $this->publishes()

call Menu::register() and/or Widget::register() (see § 5 and § 6)

PSR‑4: The module’s src/ is mapped to its own namespace in the hub composer.json.

4 🗄️ Core Database

Table

Key columns

Description

users

id, name, email, password

Standard Laravel

roles / permissions / model_has_roles

–

Spatie RBAC

menus

id, name, slug

Defines top‑level menus

menu_items

id, menu_id, parent_id, title, route, order

N‑level tree structure

widgets

id, key, component, config (JSON)

Registered Livewire widgets

Migrations live under database/migrations or are published by modules.

5 📂 Menu System

5.1 Structure

Menu – a top‑level grouping (“Main”, “Admin”, …)

MenuItem – individual link or dropdown item

parent_id enables unlimited nesting.

5.2 API

use TaskHub\\Facades\\Menu;

Menu::register(
    menu:  'admin',                    // slug matches a row in `menus`
    title: 'Equipment',                // Displayed in UI
    route: 'equipment.index',          // Laravel route name
    icon:  'lucide-tool',              // (optional) icon key
    order: 30,                         // Sort order
    permissions: ['equipment.view']    // Spatie permission gate
);

Call this in the module’s ServiceProvider → boot().

6 📦 Widget Framework

A Widget is a reusable Livewire component that can be shown on dashboards, sidebars, etc.

Widget::register(
    key:       'tickets.open',            // Unique identifier
    component: \\Modules\\TdTickets\\Livewire\\Widgets\\OpenTickets::class,
    title:     'Open Tickets',
    placement: ['dashboard'],             // Where it can be placed
    permissions: ['tickets.view'],
);

The configuration is stored in the widgets table as JSON so admin users can enable/disable & reorder widgets from the UI.

7 🔑 Authentication & Authorization

Login: POST /login (email + password) – returns a Sanctum token.

OAuth: GET /auth/{provider}/redirect → Socialite flow.

Token refresh: POST /token/refresh (planned)

RBAC: Checks via middleware permission:<slug> or Livewire directive @can.

8 🌐 REST API (Highlights)

Method

Endpoint

Description

GET

/api/user

Authenticated user data

GET

/api/menus

Full menu tree including children

GET

/api/widgets

Available widgets with config

POST

/api/widgets/{key}/configure

Update a widget instance

Run php artisan route:list --path=api for the full reference.

All endpoints require Accept: application/json and a Sanctum token in Authorization: Bearer <token>.

9 🛠️ Creating a New Module (Quick Version)

Create folder modules/td-<slug>

Run composer init and set:

name: "tronderdata/td-<slug>"

autoload.psr-4: { "TronderData\\\\<StudlySlug>\\\\": "src/" }

Create src/Providers/<StudlySlug>ServiceProvider.php and register:

class TdFooServiceProvider extends ServiceProvider {
    public function boot(): void {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Menu::register(...);
        Widget::register(...);
    }
}

Update the root composer.json with the path repo (usually already handled by a wildcard) and run composer update tronderdata/td-foo.

Add Livewire components, policies, tests.

A detailed module guide will be provided in a separate document.

10 ⚙️ Installation & Local Run

# 1. Clone the repo
$ git clone git@github.com:SveinT83/TaskHub.git && cd TaskHub

# 2. Install dependencies
$ composer install
$ npm install && npm run dev     # hot reload

# 3. Copy env file & generate key
$ cp .env.example .env
$ php artisan key:generate

# 4. Start Docker containers (Sail)
$ ./vendor/bin/sail up -d

# 5. Run migrations & seed
$ ./vendor/bin/sail artisan migrate --seed

Tip: Using GitHub Codespaces or Laravel Herd can eliminate the Docker setup.

11 ✅ Testing & QA

Unit tests – PHPUnit 11, located in /tests/

Pest – optional BDD layer

Code style – run ./vendor/bin/pint

Run all tests:

$ ./vendor/bin/sail artisan test

12 ➡️ Roadmap



Contributions are welcome – feel free to open a Pull Request or Issue!

© 2025 TrønderData AS  |  License: MIT

