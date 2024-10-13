<?php
namespace tronderdata\kbartickles\Providers;

use Illuminate\Support\ServiceProvider;

class KbarticklesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'kbartickles');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    public function register()
    {
        // Optional service bindings
    }
}
