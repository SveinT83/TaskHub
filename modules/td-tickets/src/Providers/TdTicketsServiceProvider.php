<?php

namespace tronderdata\TdTickets\Providers;

use Illuminate\Support\ServiceProvider;

class TdTicketsServiceProvider extends ServiceProvider
{
    public function boot()
    {

        //Ruter
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        //Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tdtickets');

        //Migrasjoner
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function register()
    {
        // Registreringslogikk...
    }
}
