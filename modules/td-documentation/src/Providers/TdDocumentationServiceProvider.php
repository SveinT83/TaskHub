<?php

namespace tronderdata\TdDocumentation\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class TdDocumentationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Registrer Livewire-komponenter
        Livewire::component('documentation-list', \tronderdata\TdDocumentation\Http\Livewire\DocumentationList::class);
        Livewire::component('documentation-view', \tronderdata\TdDocumentation\Http\Livewire\DocumentationView::class);

        // Last inn ruter, views og migreringer
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'td-documentation');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function register()
    {
        //
    }
}
