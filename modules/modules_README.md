# TaskHub Modules - Setup og Utvikling Guide

Dette er en komplett guide for å jobbe med TaskHub-moduler som separate Git-repositories.

## 📋 Oversikt

TaskHub er bygget med en modulær arkitektur der hver funksjonalitet er organisert som separate Composer-pakker med egne Git-repositories. Dette gir fleksibilitet, gjenbruk og uavhengig utvikling.

## 🏗️ Eksisterende Moduler

TaskHub består for øyeblikket av følgende moduler:

| Modul | Beskrivelse | Namespace |
|-------|-------------|-----------|
| **td-categories** | Kategori-håndtering | `TronderData\Categories` |
| **td-clients** | Klient-administrasjon | `TronderData\TdClients` |
| **td-equipment** | Utstyr og inventar | `TronderData\Equipment` |
| **td-kbartickles** | Kunnskapsbase artikler | `tronderdata\kbartickles` |
| **td-ocab** | OCAB integrasjon | `TronderData\TdOcab` |
| **td-task** | Oppgave-håndtering | `tronderdata\tdTask` |
| **td-tickets** | Ticket-system | `tronderdata\TdTickets` |

## 🚀 Setup for Utvikling

### 1. Klon alle moduler til denne mappen

```bash
cd /var/Projects/TaskHub/Dev/modules

# Klon hver modul (erstatt med faktiske repository URLs når de er opprettet)
git clone git@github.com:TronderData/td-categories.git
git clone git@github.com:TronderData/td-clients.git
git clone git@github.com:TronderData/td-equipment.git
git clone git@github.com:TronderData/td-kbartickles.git
git clone git@github.com:TronderData/td-ocab.git
git clone git@github.com:TronderData/td-task.git
git clone git@github.com:TronderData/td-tickets.git
```

### 2. Installer avhengigheter

```bash
cd /var/Projects/TaskHub/Dev
composer install
php artisan package:discover
```

### 3. Kjør database-migrasjoner

```bash
php artisan migrate
```

## 📁 Modul Struktur

Hver modul skal følge denne standardstrukturen:

```
td-example/
├── composer.json              # Pakke-konfigurasjon
├── README.md                  # Modul-dokumentasjon
├── src/                       # Kildekode
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

## 📦 Opprette en Ny Modul

### 1. Opprett modul-mappen og struktur

```bash
cd /var/Projects/TaskHub/Dev/modules
mkdir td-example
cd td-example

# Opprett mappestruktur
mkdir -p src/{Providers,Http/Controllers,Models,resources/views,routes}
mkdir -p database/migrations
mkdir -p tests/{Feature,Unit}
```

### 2. Opprett composer.json for modulen

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

### 3. Opprett ServiceProvider

```php
<?php
namespace TronderData\TdExample\Providers;

use Illuminate\Support\ServiceProvider;
use TaskHub\Facades\Menu;

class TdExampleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Last ruter
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        
        // Last views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tdexample');
        
        // Last migrasjoner
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        
        // Registrer meny-elementer
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
        // Registrer tjenester her
    }
}
```

### 4. Opprett Controller

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
        // Lagre logikk
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
        // Oppdater logikk
    }

    public function destroy($id)
    {
        // Slett logikk
    }
}
```

### 5. Opprett ruter

```php
<?php
// src/routes/web.php
use Illuminate\Support\Facades\Route;
use TronderData\TdExample\Http\Controllers\ExampleController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('examples', ExampleController::class);
});
```

## 🔧 Registrere Modul i TaskHub

### 1. Legg til i root composer.json

Rediger `/var/Projects/TaskHub/Dev/composer.json` og legg til:

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

### 2. Oppdater autoloader

```bash
cd /var/Projects/TaskHub/Dev
composer dump-autoload
php artisan package:discover
php artisan config:clear
```

## 🔄 Arbeidsflyt for Utvikling

### Daglig utvikling

1. **Jobb på moduler**:
   ```bash
   cd modules/td-example
   git checkout -b feature/ny-funksjon
   # Gjør endringer
   git add .
   git commit -m "Legg til ny funksjon"
   git push origin feature/ny-funksjon
   ```

2. **Test i TaskHub**:
   ```bash
   cd /var/Projects/TaskHub/Dev
   php artisan serve
   ```

3. **Commit TaskHub endringer**:
   ```bash
   cd /var/Projects/TaskHub/Dev
   git add .
   git commit -m "Oppdater for ny modul-funksjon"
   ```

### Versjonering

- **Moduler**: Bruk semantisk versjonering (v1.0.0, v1.1.0, etc.)
- **TaskHub Core**: Oppdater modul-versjoner i composer.json når nødvendig

## 🧪 Testing

### Test individuelle moduler

```bash
cd modules/td-example
./vendor/bin/phpunit
```

### Test hele TaskHub

```bash
cd /var/Projects/TaskHub/Dev
php artisan test
```

## 📋 Namespace Konvensjoner

- **Hovednamespace**: `TronderData\[ModulNavn]`
- **Eksempel**: `TronderData\TdClients`, `TronderData\Equipment`
- **Eldre moduler**: Noen bruker `tronderdata\[modulnavn]` (lowercase)
- **Konsistens**: Nye moduler skal bruke `TronderData\[ModulNavn]`

## 🔍 Debugging

### Vanlige problemer

1. **"Class not found" feil**:
   ```bash
   composer dump-autoload
   php artisan config:clear
   ```

2. **Ruter ikke funnet**:
   ```bash
   php artisan route:clear
   php artisan route:list --name=modulnavn
   ```

3. **Views ikke funnet**:
   - Sjekk at views er registrert i ServiceProvider
   - Bruk riktig namespace i blade: `@extends('tdexample::layout')`

## 💡 Best Practices

### Modul Design

- ✅ Hold moduler små og fokuserte
- ✅ Bruk konsistente namespace-konvensjoner  
- ✅ Inkluder comprehensive tests
- ✅ Dokumenter API og funksjonalitet
- ✅ Følg Laravel-konvensjoner

### Git Workflow

- ✅ En feature per branch
- ✅ Meaningful commit messages
- ✅ Code review før merge
- ✅ Tag releases med versjonsnummer

### Database

- ✅ Bruk beskrivende migration-navn
- ✅ Inkluder `down()` metoder
- ✅ Test migrasjoner both up og down

## 📞 Support

- **Dokumentasjon**: Se TaskHub hovedprosjekt README
- **Issues**: Rapporter bugs i respektive modul-repositories
- **Diskusjoner**: TaskHub team Slack/Discord
- **Code Review**: Pull requests requires approval

---

## 📄 Eksempel Repository URLs

Når modulene er satt opp som separate repositories:

```bash
# TrønderData organisasjon på GitHub
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
