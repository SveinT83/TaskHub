<?php
namespace tronderdata\TdClients\Providers;

use Illuminate\Support\ServiceProvider;

class TdClientsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tdclients');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tdClients');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tdSites');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tdUsers');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    public function register()
    {
        // Optional service bindings
    }
}
