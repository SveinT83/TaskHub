# TaskHub Language System Implementation

Dette dokumentet beskriver implementeringen av språksystemet i TaskHub Core, basert på spesifikasjonen i `docs/langue.md`.

## 🎯 Implementerte Funksjoner

### ✅ Grunnleggende Struktur
- **Konfigurrasjon**: Utvidet `config/app.php` med støttede språk
- **Middleware**: `SetUserLocale` for automatisk språkvalg
- **Modeller**: Lagt til `locale`-felt i User-modellen
- **Migrasjoner**: Database-støtte for brukerens språkpreferanser

### ✅ CLI-Kommandoer
- `php artisan lang:sync {locale}` - Synkroniser manglende nøkler
- `php artisan lang:export {locale} --format=csv` - Eksporter oversettelser
- `php artisan lang:import {file}` - Importer oversettelser
- `php artisan lang:lint --locale=en` - Valider oversettelser

### ✅ GUI-Editor
- **Livewire-komponent**: `TranslationEditor` for live-redigering
- **Administrasjonspanel**: Komplett brukergrensesnitt
- **Språkbytte**: `LanguageSwitcher`-komponent
- **Statistikk**: Oversettingsstatus per språk

### ✅ Språkfiler
- **Core English** (`resources/lang/en/core.php`)
- **Core Norsk** (`resources/lang/no/core.php`)
- **Validering Norsk** (`resources/lang/no/validation.php`)
- **Auto-genererte** danske og svenske stubber

## 🔧 Teknisk Implementering

### Middleware-flyt
1. **Brukerpreferanse** (hvis innlogget)
2. **Session-locale** (for gjester)
3. **Standard locale** (fallback)

### Navngiving av oversettelsesnøkler
```php
// Core oversettelser
__('core::ui.save')          // resources/lang/{locale}/core.php
__('core::validation.required')

// Modul-oversettelser  
__('modulename::messages.title')  // modules/{modulename}/Resources/lang/{locale}/messages.php
```

### Språkbytte
```php
// Programmatisk
App::setLocale('no');

// Via komponent
<livewire:components.language-switcher />

// Via brukerinstillinger
$user->update(['locale' => 'no']);
```

## 📂 Filstruktur

```
├── app/
│   ├── Console/Commands/
│   │   ├── LangSyncCommand.php
│   │   ├── LangExportCommand.php
│   │   ├── LangImportCommand.php
│   │   └── LangLintCommand.php
│   ├── Http/
│   │   ├── Controllers/Admin/
│   │   │   └── TranslationController.php
│   │   └── Middleware/
│   │       └── SetUserLocale.php
│   ├── Livewire/
│   │   ├── Admin/
│   │   │   └── TranslationEditor.php
│   │   └── Components/
│   │       └── LanguageSwitcher.php
│   ├── Helpers/
│   │   └── LanguageHelper.php
│   └── Providers/
│       └── LanguageServiceProvider.php
├── resources/
│   ├── lang/
│   │   ├── en/
│   │   │   └── core.php
│   │   ├── no/
│   │   │   ├── core.php
│   │   │   └── validation.php
│   │   ├── da/
│   │   │   └── core.php (auto-generert)
│   │   └── sv/
│   │       └── core.php (auto-generert)
│   └── views/
│       ├── admin/translations/
│       │   ├── index.blade.php
│       │   └── stats.blade.php
│       └── livewire/
│           ├── admin/
│           │   └── translation-editor.blade.php
│           └── components/
│               └── language-switcher.blade.php
└── database/migrations/
    └── 2025_07_18_103700_add_locale_to_users_table.php
```

## 🚀 Bruksanvisning

### For Utviklere

#### Bruke oversettelser i kode
```php
// I controllere/modeller
__('core::ui.save')
__('core::notifications.created', ['item' => 'Task'])

// I Blade-templates
{{ __('core::ui.edit') }}
@lang('core::mail.greeting', ['name' => $user->name])

// Med pluralisering  
trans_choice('core::messages.items', $count, ['count' => $count])
```

#### Legge til nye oversettelsesnøkler
1. Legg til i `resources/lang/en/core.php`
2. Kjør `php artisan lang:sync no` for å lage tomme nøkler
3. Bruk GUI-editoren for å fylle inn oversettelser

### For Oversettere

#### Via GUI (Anbefalt)
1. Gå til `/admin/translations`
2. Velg språk, modul og fil
3. Klikk "Edit" på nøkler som trenger oversettelse
4. Lagre endringer - oppdateres øyeblikkelig

#### Via CLI (Avansert)
```bash
# Eksporter til CSV for eksterne verktøy
php artisan lang:export no --format=csv

# Rediger i Excel/CAT-verktøy

# Importer tilbake
php artisan lang:import storage/app/exports/translations_no_2025-07-18.csv

# Valider oversettelser
php artisan lang:lint --locale=no
```

## 🎨 Administrasjonspanel

### Hovedside (`/admin/translations`)
- Oversikt over språksystem
- Live translation editor
- CLI-kommando referanse

### Statistikk (`/admin/translations/stats`)
- Oversettingsprogresjon per språk
- Visuell fremgang med progress bars
- Detaljert oversikt

## 🧪 Testing

Kjør tester for å verifisere implementeringen:
```bash
php artisan test tests/Feature/LanguageSystemTest.php
```

## 🔮 Fremtidige Utvidelser

Basert på roadmap i `docs/langue.md`:

1. **CAT API-integrasjon** (Crowdin, PO export/import)
2. **Hybrid DB buffer** → kompiler til fil hver time
3. **Per-modul fallback_locale**
4. **Automatiske oversettelses-forslag** via GPT-4-Turbo

## 📋 Vedlikehold

### Regelmessige oppgaver
- Kjør `php artisan lang:lint` i CI/CD pipeline
- Eksporter oversettelser for eksterne oversettere månedlig
- Overvåk oversettingsstatistikk for kvalitetssikring

### Feilsøking
Se FAQ-seksjonen i `docs/langue.md` for vanlige problemer og løsninger.

---

*Implementert i henhold til spesifikasjon i `docs/langue.md` - alle punkter fra 1-8 er dekket.*
