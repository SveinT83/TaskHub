<?php

namespace Modules\Core\src\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'core');
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}