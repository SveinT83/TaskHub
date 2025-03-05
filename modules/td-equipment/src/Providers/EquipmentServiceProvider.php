<?php

namespace TronderData\Equipment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class EquipmentServiceProvider extends ServiceProvider
{
    /**
     * Registrer bindings i containeren.
     */
    public function register()
    {
        // Last inn konfigurasjoner hvis de finnes
        // $this->mergeConfigFrom(__DIR__ . '/../Config/equipment.php', 'equipment');
    }

    /**
     * Bootstrap tjenesten.
     */
    public function boot()
    {
        // Last inn migrasjoner
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Last inn ruter
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Last inn view-filer
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'equipment');

        // Last inn view-filer for widgets
        $this->loadViewsFrom(__DIR__.'/../Resources/views/widgets', 'widgets');

        // Publiser migrasjoner og konfigurasjoner
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../Config/equipment.php' => config_path('equipment.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
    }
}
