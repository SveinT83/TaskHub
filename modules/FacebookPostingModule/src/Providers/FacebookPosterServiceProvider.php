<?php

namespace Modules\FacebookPostingModule\src\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class FacebookPosterServiceProvider extends ServiceProvider
{
    /**
     * Boot the module services.
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerRoutes();
    }

    /**
     * Register the module configuration.
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('facebookposter.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'facebookposter'
        );
    }

    /**
     * Register the module views.
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/facebookposter');
        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        $this->loadViewsFrom($sourcePath, 'facebookposter');
    }

    /**
     * Register module routes.
     */
    protected function registerRoutes()
    {
        if (!$this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        }
    }

    /**
     * Register bindings and services.
     */
    public function register()
    {
        // Register additional bindings or services if necessary
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides()
    {
        return [];
    }
}
