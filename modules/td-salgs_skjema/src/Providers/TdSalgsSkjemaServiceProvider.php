<?php

namespace tronderdata\TdSalgsSkjema\Providers;

use Illuminate\Support\ServiceProvider;

class TdSalgsSkjemaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'TdSalgsSkjema');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function register()
    {
        // Registration of any services
    }
}
