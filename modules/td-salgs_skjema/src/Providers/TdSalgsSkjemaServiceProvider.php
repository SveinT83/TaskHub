<?php

namespace tronderdata\TdSalgsSkjema\Providers;

use Illuminate\Support\ServiceProvider;
use tronderdata\TdSalgsSkjema\Livewire\FindCustomerForm;
use Livewire\Livewire;

class TdSalgsSkjemaServiceProvider extends ServiceProvider
{
    public function boot()
    {

        // --------------------------------------------------------------------------------------------------
        // Load the routes, views, migrations and publish the config
        // --------------------------------------------------------------------------------------------------

        // -------------------------------------------------
        // Load the Livewire components
        // -------------------------------------------------
        Livewire::component('FindCustomerForm', FindCustomerForm::class);

        // -------------------------------------------------
        // Load the routes
        // -------------------------------------------------
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // -------------------------------------------------
        // Load the views
        // -------------------------------------------------
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'TdSalgsSkjema');

        // -------------------------------------------------
        // Load the migrations
        // -------------------------------------------------
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function register()
    {
        // Registration of any services
    }
}
