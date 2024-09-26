<?php

namespace tronderdata\TdTask\Providers;

use Illuminate\Support\ServiceProvider;

class TdTaskServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tdtask');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');
    }

    public function register()
    {
        //
    }
}
