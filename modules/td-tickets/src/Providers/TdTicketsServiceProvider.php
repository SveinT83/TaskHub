<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTickets\Providers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use tronderdata\TdClients\Models\Client;
use Livewire\Livewire;
use tronderdata\TdTickets\Http\Livewire\TicketConfig;
use tronderdata\TdTickets\Http\Livewire\KbWidget;

class TdTicketsServiceProvider extends ServiceProvider
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION BOOT
    //
    // Boot the service provider
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function boot()
    {

        // --------------------------------------------------------------------------------------------------
        // Load the routes, views, migrations and publish the config
        // --------------------------------------------------------------------------------------------------

        // -------------------------------------------------
        // Load the Livewire components
        // -------------------------------------------------
        Livewire::component('ticket-config', TicketConfig::class);
        Livewire::component('kb-widget', KbWidget::class);

        // -------------------------------------------------
        // Load the routes
        // -------------------------------------------------
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // -------------------------------------------------
        // Load the views
        // -------------------------------------------------
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tdtickets');

        // -------------------------------------------------
        // Load the migrations
        // -------------------------------------------------
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // -------------------------------------------------
        // Eksplisitt binding av {client} til Client-modellen
        // -------------------------------------------------
        Route::model('client', Client::class);
    }

    public function register()
    {
        // Registreringslogikk...
    }
}
