<?php

namespace Tronderdata\Skeleton\Providers;

use Illuminate\Support\ServiceProvider;

class SkeletonProvider extends ServiceProvider
{
    public function boot()
    {

        // --------------------------------------------------------------------------------------------------
        // Load the routes, views, migrations and publish the config
        // --------------------------------------------------------------------------------------------------

        // -------------------------------------------------
        // Load the routes
        // -------------------------------------------------
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // -------------------------------------------------
        // Load the views
        // -------------------------------------------------
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'Skeleton');

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
