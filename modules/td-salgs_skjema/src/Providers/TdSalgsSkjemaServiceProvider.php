<?php

namespace tronderdata\TdSalgsSkjema\Providers;

use Illuminate\Support\ServiceProvider;
use tronderdata\TdSalgsSkjema\Livewire\BussinessOrPrivate;
use tronderdata\TdSalgsSkjema\Livewire\aLaCarte;
use tronderdata\TdSalgsSkjema\Livewire\Price_card;
use tronderdata\TdSalgsSkjema\Livewire\antallBrukere;
use tronderdata\TdSalgsSkjema\Livewire\antallDatamaskiner;
use tronderdata\TdSalgsSkjema\Livewire\antallTimerForm;
use tronderdata\TdSalgsSkjema\Livewire\ServiceavtaleForm;
use tronderdata\TdSalgsSkjema\Livewire\serviceavtaleConfigForm;
use tronderdata\TdSalgsSkjema\Livewire\FindCustomerForm;
use tronderdata\TdSalgsSkjema\Livewire\RegisterNewCustomerForm;
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
        Livewire::component('BussinessOrPrivate', BussinessOrPrivate::class);
        Livewire::component('antallBrukere', antallBrukere::class);
        Livewire::component('antallDatamaskiner', antallDatamaskiner::class);
        Livewire::component('antallTimerForm', antallTimerForm::class);
        Livewire::component('ServiceavtaleForm', ServiceavtaleForm::class);
        Livewire::component('serviceavtaleConfigForm', serviceavtaleConfigForm::class);
        Livewire::component('aLaCarte', aLaCarte::class);
        Livewire::component('FindCustomerForm', FindCustomerForm::class);
        Livewire::component('RegisterNewCustomerForm', RegisterNewCustomerForm::class);
        Livewire::component('Price_card', Price_card::class);

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
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');

        // -------------------------------------------------
        // Load the images
        // -------------------------------------------------
        $this->publishes([__DIR__ . '/../resources/images' => public_path('modules/TdSalgsSkjema/images'),], 'public');
    }

    public function register()
    {
        // Registration of any services
    }
}
