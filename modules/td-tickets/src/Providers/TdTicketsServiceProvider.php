<?php

namespace tronderdata\TdTickets\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use tronderdata\TdClients\Models\Client;

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

        // Eksplisitt binding av {client} til Client-modellen
        Route::model('client', Client::class);
    }

    public function register()
    {
        // Registreringslogikk...
    }
}
