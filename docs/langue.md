# langue.md

## 1. Introduction & Audience
This specification describes how **TaskHub handles language (internati## 7. Best Practices

* **Use correct keys without namespace prefix**: Use `__('core.ui.save')` NOT `__('core::ui.save')`. The double-colon syntax is incorrect in TaskHub Core.
* **Module keys**: For modules, use the module's key directly, e.g., `__('blog.posts.title')` NOT `__('blog::posts.title')`.  
* **Pluralisation**: leverage Laravel's `|` syntax: "apple" => "apple|apples".  
* **Variables**: always pass named replacements, e.g. `__('core.mail.greeting', ['name' => $user->name])`.  
* **Dates & numbers**: format in controllers using `Carbon::locale()` or the new `Illuminate\Support\Locale` helpers, not hard‑coded strings.  
* **Tone**: TaskHub uses a friendly but professional "you" voice. Avoid slang.tion, i18n)** for both *core* and *module* code.

**Audience**  
* Laravel / TaskHub backend developers.  
* Front‑end or module developers writing Blade, Livewire, or Vue.  
* Translators and power‑users who use the built‑in GUI editor.

Snippets marked with `<!-- copilot:example -->` are designed for GitHub Copilot: they include context, are immediately compilable, and contain placeholder tokens (`{{ module_slug }}`, `{{ locale }}`) for quick replacement.

---

## 2. Directory & Naming Conventions

### 2.1 Core
```
resources/lang/{locale}/core.php        # or core.json
```

### 2.2 Modules
```
Modules/{{ module_slug }}/Resources/lang/{locale}/messages.php
```

For very text‑heavy pages or features, create additional files (e.g. `emails.php`, `wiki.php`). Keep names semantically scoped rather than generic.

### 2.3 Overrides
If an installation needs to override a core string without touching vendor files, place a file at:

```
lang/vendor/core/{locale}/core.php
```

Laravel automatically merges this over the shipped core value.

---

## 3. Configuration

### 3.1 Global defaults (`config/app.php`)
```php
'locale'          => env('APP_LOCALE', 'en'),
'fallback_locale' => 'en',
```

Set per environment via `.env`:

```
APP_LOCALE=no            # default language for anonymous users
```

### 3.2 Per‑user locale  
Add a middleware and register it in `app/Http/Kernel.php`.

<!-- copilot:example -->
```php
// app/Http/Middleware/SetUserLocale.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetUserLocale
{
    public function handle($request, Closure $next)
    {
        if ($user = Auth::user()) {
            App::setLocale($user->locale ?? config('app.locale'));
        }

        return $next($request);
    }
}
```

---

## 4. Runtime Lookup & Fallback Order

1. **Active locale** – set globally or by middleware.  
2. **Fallback locale** – value of `app.fallback_locale`.  
3. **Literal key** – shown if no translation exists, making missing values easy to spot.

---

## 5. GUI Editor Workflow (Translator View)

| Step | Action | Behind the scenes |
|------|--------|-------------------|
| 1 | Open **Translations** panel | Scans active and fallback locale files across core + enabled modules. |
| 2 | Edit value | State updated in browser. |
| 3 | Save | Writes value to the correct file path. Creates folder/file when missing. |
| 4 | Flush & broadcast | Runs `Lang::flush()` and `cache()->tags('locale')->flush()`, then sends Livewire echo to refresh all open tabs. |
| 5 | Live! | New string rendered instantly; no deploy, no CLI. |

---

## 6. CLI Utilities

All commands live in the `TaskHub\Lang` namespace.

| Command | Purpose |
|---------|---------|
| `php artisan lang:sync {locale}` | Stub missing keys for a locale with empty values. |
| `php artisan lang:export {locale} --format=csv` | Export key/value pairs for external CAT tools. |
| `php artisan lang:import {file}` | Import translations and write to correct files. |
| `php artisan lang:lint` | Fails CI if default locale has empty values or duplicates. |

---

## 7. Best Practices

* **Use namespaced keys**: `__('core::ui.save')`, `__('blog::posts.title')`.  
* **Pluralisation**: leverage Laravel's `|` syntax: "apple" => "apple|apples".  
* **Variables**: always pass named replacements, e.g. `__('core::mail.greeting', ['name' => $user->name])`.  
* **Dates & numbers**: format in controllers using `Carbon::locale()` or the new `Illuminate\Support\Locale` helpers, not hard‑coded strings.  
* **Tone**: TaskHub uses a friendly but professional “you” voice. Avoid slang.

---

## 8. FAQ / Troubleshooting

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| Key name printed literally | Value missing in active & fallback locale | Add to file or run `lang:sync`. |
| Translation saved but not visible | Cache still warm | Ensure `Lang::flush()` executed; check `APP_LOCALE_CACHE` env. |
| File not writable | Container volume read‑only | Mount `storage/framework/lang` as RW or adjust ACL. |

---

## 9. Roadmap

* CAT API integration (Crowdin, PO export/import).  
* Hybrid DB buffer → compile to file hourly for collaborative live editing.  
* Per‑module fallback_locale.  
* Automatic translation suggestions via GPT‑4‑Turbo.
