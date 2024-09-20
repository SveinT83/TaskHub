<?php

namespace tronderdata\TdTickets\Providers;

use Illuminate\Support\ServiceProvider;

class TdTicketsServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tdtickets');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function register()
    {
        // Registreringslogikk...
    }

    protected function registerSeeders()
    {
        $this->loadSeedersFrom(__DIR__ . '/../database/seeders');
    }
}
