<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'core');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    
    
        // Register Middleware
        $this->app['router']->aliasMiddleware('role', EnsureUserHasRole::class);
    }

    public function register()
    {
        // Register core services
    }
}