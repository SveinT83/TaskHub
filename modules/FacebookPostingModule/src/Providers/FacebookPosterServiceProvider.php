<?php

namespace Modules\FacebookPostingModule\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FacebookPosterServiceProvider extends ServiceProvider
{
    /**
     * Boot the module services.
     *
     * This method is called after all other service providers have been registered,
     * allowing you to perform any actions needed for your module.
     */
    public function boot()
    {
        // Log when the provider is booted
        \Log::info('FacebookPosterServiceProvider booted');

        // Register configuration, views, and routes
        $this->registerConfig();
        $this->registerViews();
        $this->registerRoutes();
    }

    /**
     * Register the module configuration.
     *
     * This method will publish the module's config file to the application's config
     * directory and merge any settings from the module config with the app config.
     */
    protected function registerConfig()
    {
        // Publish the module's configuration file
        $this->publishes([
            base_path('modules/FacebookPostingModule/src/Config/config.php') => config_path('facebookposter.php'),
        ], 'config');

        // Merge the module's config with the app's configuration
        $this->mergeConfigFrom(
            base_path('modules/FacebookPostingModule/src/Config/config.php'),
            'facebookposter'
        );
    }

    /**
     * Register the module views.
     *
     * This method will load the module's views and allow them to be published.
     */
    public function registerViews()
    {
        // Define paths for the views
        $viewPath = resource_path('views/modules/facebookposter');
        $sourcePath = base_path('modules/FacebookPostingModule/src/Resources/views');

        // Publish the views to the application
        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        // Load the views from the module's directory
        $this->loadViewsFrom($sourcePath, 'facebookposter');
    }

    /**
     * Register module routes.
     *
     * This method registers the module's routes with the application.
     */
    protected function registerRoutes()
    {
        // Check if routes are already cached
        if (!$this->app->routesAreCached()) {
            // Define the routes for the module
            Route::middleware('web')
                ->namespace('Modules\FacebookPostingModule\Http\Controllers')  // Define the namespace for the controllers
                ->group(base_path('modules/FacebookPostingModule/src/Routes/web.php'));  // Load the module's routes
        }
    }

    /**
     * Register any additional bindings or services.
     *
     * This method is used to bind services or classes into the service container.
     */
    public function register()
    {
        // Register additional bindings or services if necessary
    }

    /**
     * Get the services provided by the provider.
     *
     * This method returns an array of services that are provided by the service provider.
     */
    public function provides()
    {
        return [];
    }
}
