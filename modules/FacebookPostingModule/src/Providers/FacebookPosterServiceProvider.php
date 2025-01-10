<?php

namespace Modules\FacebookPostingModule\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FacebookPosterServiceProvider extends ServiceProvider
{
    /**
     * Boot the module services.
     *
     * This method is used to register any services, routes, views, or configs
     * necessary for the module. It ensures everything is set up when the
     * application boots.
     */
    public function boot()
    {
        \Log::info('FacebookPosterServiceProvider booted');

        // Register and publish config files for the module
        $this->registerConfig();

        // Register and publish views
        $this->registerViews();

        // Register routes for the module
        $this->registerRoutes();
    }

    /**
     * Register the module configuration.
     *
     * This method publishes the module's configuration file to the application's
     * config directory so that the app can be customized by the user.
     */
    protected function registerConfig()
    {
        // Publish the configuration file from the module to the config directory
        $this->publishes([
            base_path('modules/FacebookPostingModule/src/Config/config.php') => config_path('facebookposter.php'),
        ], 'config');

        // Merge the module's configuration with the app's config, allowing overrides
        $this->mergeConfigFrom(
            base_path('modules/FacebookPostingModule/src/Config/config.php'),
            'facebookposter'
        );
    }

    /**
     * Register the module views.
     *
     * This method is responsible for loading the views from the module and
     * making them available to the application.
     */
    public function registerViews()
    {
        // Define paths to the source and destination view directories
        $viewPath = resource_path('views/modules/facebookposter');
        $sourcePath = base_path('modules/FacebookPostingModule/src/Resources/views');

        // Publish the views to the application's resources/views directory
        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        // Load views from the source path, naming them 'facebookposter'
        $this->loadViewsFrom($sourcePath, 'facebookposter');
    }

    /**
     * Register the module routes.
     *
     * This method registers the routes for the module. If the routes are not
     * cached, they are loaded from the defined route file.
     */
    protected function registerRoutes()
    {
        // Only load routes if they are not already cached
        if (!$this->app->routesAreCached()) {
            Route::middleware('web')
                ->namespace('Modules\FacebookPostingModule\Http\Controllers')
                ->group(base_path('modules/FacebookPostingModule/src/Routes/web.php'));
        }
    }

    /**
     * Register bindings and services.
     *
     * This method is used to register any custom services or bindings into the
     * application's service container. This could include things like custom
     * services, repositories, or other dependencies.
     */
    public function register()
    {
        // Register any services the module may require here (currently no bindings needed)
    }

    /**
     * Get the services provided by the provider.
     *
     * This method defines what services this provider is providing to the application.
     */
    public function provides()
    {
        // Since no services are explicitly registered, we return an empty array.
        return [];
    }
}
