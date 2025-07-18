<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register language-related services
        $this->commands([
            \App\Console\Commands\LangSyncCommand::class,
            \App\Console\Commands\LangExportCommand::class,
            \App\Console\Commands\LangImportCommand::class,
            \App\Console\Commands\LangLintCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Registrer core namespace for oversettelser ← Legg til dette
        Lang::addNamespace('core', resource_path('lang'));

        // Share current locale with all views
        View::composer('*', function ($view) {
            $view->with([
                'currentLocale' => App::getLocale(),
                'supportedLocales' => config('app.supported_locales', []),
                'fallbackLocale' => config('app.fallback_locale'),
            ]);
        });

        // Load custom validation messages if they exist
        $this->loadCustomValidationMessages();
    }

    /**
     * Load custom validation messages for the current locale.
     */
    protected function loadCustomValidationMessages(): void
    {
        $locale = App::getLocale();
        $validationPath = resource_path("lang/{$locale}/validation.php");
        
        if (file_exists($validationPath)) {
            $messages = include $validationPath;
            if (is_array($messages)) {
                $this->app['validator']->extend('custom', function () {
                    return true;
                });
            }
        }
    }
}
