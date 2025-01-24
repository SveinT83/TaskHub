<?php

namespace Modules\FacebookPostingModule\Providers;

use Illuminate\Support\ServiceProvider;

class FacebookPosterServiceProvider extends ServiceProvider
{
    /**
     * Boot the module services.
     */
    public function boot()
    {
        \Log::info('FacebookPosterServiceProvider booted');
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
            base_path('modules/FacebookPostingModule/src/Config/config.php') => config_path('facebookposter.php'),
        ], 'config');

        $this->mergeConfigFrom(
            base_path('modules/FacebookPostingModule/src/Config/config.php'),
            'facebookposter'
        );
    }

    /**
     * Register the module views.
     */
    protected function registerViews()
    {
        $viewPath = resource_path('views/modules/facebookposter');
        $sourcePath = base_path('modules/FacebookPostingModule/src/Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        $this->loadViewsFrom($sourcePath, 'FacebookPostingModule');
    }

    /**
     * Register the module routes.
     */
    protected function registerRoutes()
    {
        if (!$this->app->routesAreCached()) {
            \Route::middleware('web')
                ->namespace('Modules\FacebookPostingModule\Http\Controllers')
                ->group(base_path('modules/FacebookPostingModule/src/Routes/web.php'));
        }
    }

    /**
     * Register bindings and services.
     */
    public function register()
    {
        $this->app->singleton(\Modules\FacebookPostingModule\Services\FacebookApiService::class, function ($app) {
            // Retrieve access token from config or environment
            $defaultAccessToken = config('facebookposter.facebook_api.default_access_token');
            return new \Modules\FacebookPostingModule\Services\FacebookApiService($defaultAccessToken);
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides()
    {
        return [];
    }
}
