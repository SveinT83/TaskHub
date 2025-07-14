# TaskHub Modules - Setup and Development Guide

This is a comprehensive guide for working with TaskHub modules as separate Git repositories.

## 📋 Overview

TaskHub is built with a modular architecture where each functionality is organized as separate Composer packages with their own Git repositories. This provides flexibility, reusability, and independent development.

## 🏗️ Existing Modules

TaskHub currently consists of the following modules:

| Module | Description | Namespace |
|--------|-------------|-----------|
| **td-categories** | Category management | `TronderData\Categories` |
| **td-clients** | Client administration | `TronderData\TdClients` |
| **td-equipment** | Equipment and inventory | `TronderData\Equipment` |
| **td-kbartickles** | Knowledge base articles | `tronderdata\kbartickles` |
| **td-ocab** | OCAB integration | `TronderData\TdOcab` |
| **td-task** | Task management | `tronderdata\tdTask` |
| **td-tickets** | Ticket system | `tronderdata\TdTickets` |

## 🚀 Development Setup

### 1. Clone all modules to this directory

```bash
cd /var/Projects/TaskHub/Dev/modules

# Clone each module (replace with actual repository URLs when created)
git clone git@github.com:TronderData/td-categories.git
git clone git@github.com:TronderData/td-clients.git
git clone git@github.com:TronderData/td-equipment.git
git clone git@github.com:TronderData/td-kbartickles.git
git clone git@github.com:TronderData/td-ocab.git
git clone git@github.com:TronderData/td-task.git
git clone git@github.com:TronderData/td-tickets.git
```

### 2. Install dependencies

```bash
cd /var/Projects/TaskHub/Dev
composer install
php artisan package:discover
```

### 3. Run database migrations

```bash
php artisan migrate
```

## 📁 Module Structure

Each module should follow this standard structure:

```
td-example/
├── composer.json              # Package configuration
├── README.md                  # Module documentation
├── src/                       # Source code
│   ├── Providers/            
│   │   └── ExampleServiceProvider.php
│   ├── Http/
│   │   └── Controllers/
│   │       └── ExampleController.php
│   ├── Models/
│   │   └── ExampleModel.php
│   ├── resources/
│   │   └── views/
│   │       └── example/
│   └── routes/
│       └── web.php
├── database/
│   └── migrations/
└── tests/
    ├── Feature/
    └── Unit/
```

## 📦 Creating a New Module

### 1. Create module directory and structure

```bash
cd /var/Projects/TaskHub/Dev/modules
mkdir td-example
cd td-example

# Create directory structure
mkdir -p src/{Providers,Http/Controllers,Models,resources/views,routes}
mkdir -p database/migrations
mkdir -p tests/{Feature,Unit}
```

### 2. Create composer.json for the module

```json
{
    "name": "tronderdata/td-example",
    "description": "Example module for TaskHub",
    "type": "library",
    "license": "MIT",
    "autoload": {
        "psr-4": {
            "TronderData\\TdExample\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "TronderData\\TdExample\\Tests\\": "tests/"
        }
    },
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0"
    },
    "extra": {
        "laravel": {
            "providers": [
                "TronderData\\TdExample\\Providers\\TdExampleServiceProvider"
            ]
        }
    }
}
```

### 3. Create ServiceProvider

```php
<?php
namespace TronderData\TdExample\Providers;

use Illuminate\Support\ServiceProvider;
use TaskHub\Facades\Menu;

class TdExampleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tdexample');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        
        // Register menu items
        Menu::register(
            menu: 'main',
            title: 'Examples',
            route: 'examples.index',
            icon: 'bi-collection',
            order: 50,
            permissions: ['examples.view']
        );
    }

    public function register(): void
    {
        // Register services here
    }
}
```

### 4. Create Controller

```php
<?php
namespace TronderData\TdExample\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function index()
    {
        return view('tdexample::examples.index');
    }

    public function create()
    {
        return view('tdexample::examples.create');
    }

    public function store(Request $request)
    {
        // Store logic
    }

    public function show($id)
    {
        return view('tdexample::examples.show', compact('id'));
    }

    public function edit($id)
    {
        return view('tdexample::examples.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update logic
    }

    public function destroy($id)
    {
        // Delete logic
    }
}
```

### 5. Create routes

```php
<?php
// src/routes/web.php
use Illuminate\Support\Facades\Route;
use TronderData\TdExample\Http\Controllers\ExampleController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('examples', ExampleController::class);
});
```

## 🔧 Register Module in TaskHub

### 1. Add to root composer.json

Edit `/var/Projects/TaskHub/Dev/composer.json` and add:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./modules/td-example"
        }
    ],
    "require": {
        "tronderdata/td-example": "dev-main"
    },
    "autoload": {
        "psr-4": {
            "TronderData\\TdExample\\": "modules/td-example/src/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "TronderData\\TdExample\\Providers\\TdExampleServiceProvider"
            ]
        }
    }
}
```

### 2. Update autoloader

```bash
cd /var/Projects/TaskHub/Dev
composer dump-autoload
php artisan package:discover
php artisan config:clear
```

## 🔄 Development Workflow

### Daily development

1. **Work on modules**:
   ```bash
   cd modules/td-example
   git checkout -b feature/new-functionality
   # Make changes
   git add .
   git commit -m "Add new functionality"
   git push origin feature/new-functionality
   ```

2. **Test in TaskHub**:
   ```bash
   cd /var/Projects/TaskHub/Dev
   php artisan serve
   ```

3. **Commit TaskHub changes**:
   ```bash
   cd /var/Projects/TaskHub/Dev
   git add .
   git commit -m "Update for new module functionality"
   ```

### Versioning

- **Modules**: Use semantic versioning (v1.0.0, v1.1.0, etc.)
- **TaskHub Core**: Update module versions in composer.json when necessary

## 🧪 Testing

### Test individual modules

```bash
cd modules/td-example
./vendor/bin/phpunit
```

### Test entire TaskHub

```bash
cd /var/Projects/TaskHub/Dev
php artisan test
```

## 📋 Namespace Conventions

- **Main namespace**: `TronderData\[ModuleName]`
- **Example**: `TronderData\TdClients`, `TronderData\Equipment`
- **Legacy modules**: Some use `tronderdata\[modulename]` (lowercase)
- **Consistency**: New modules should use `TronderData\[ModuleName]`

## 🔍 Debugging

### Common issues

1. **"Class not found" errors**:
   ```bash
   composer dump-autoload
   php artisan config:clear
   ```

2. **Routes not found**:
   ```bash
   php artisan route:clear
   php artisan route:list --name=modulename
   ```

3. **Views not found**:
   - Check that views are registered in ServiceProvider
   - Use correct namespace in blade: `@extends('tdexample::layout')`

## 💡 Best Practices

### Module Design

- ✅ Keep modules small and focused
- ✅ Use consistent namespace conventions  
- ✅ Include comprehensive tests
- ✅ Document API and functionality
- ✅ Follow Laravel conventions

### Git Workflow

- ✅ One feature per branch
- ✅ Meaningful commit messages
- ✅ Code review before merge
- ✅ Tag releases with version number

### Database

- ✅ Use descriptive migration names
- ✅ Include `down()` methods
- ✅ Test migrations both up and down

## 📞 Support

- **Documentation**: See TaskHub main project README
- **Issues**: Report bugs in respective module repositories
- **Discussions**: TaskHub team Slack/Discord
- **Code Review**: Pull requests require approval

---

## 📄 Example Repository URLs

When modules are set up as separate repositories:

```bash
# TrønderData organization on GitHub
git@github.com:TronderData/td-categories.git
git@github.com:TronderData/td-clients.git
git@github.com:TronderData/td-equipment.git
git@github.com:TronderData/td-kbartickles.git
git@github.com:TronderData/td-ocab.git
git@github.com:TronderData/td-task.git
git@github.com:TronderData/td-tickets.git
```

---

*© 2025 TrønderData AS - TaskHub Modular Architecture Guide*
