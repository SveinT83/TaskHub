<?php

namespace Modules\CredentialsBank\Providers;

use Illuminate\Support\ServiceProvider;

class CredentialsBankServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register any bindings or services if needed.
    }

    public function boot()
    {
        // Only load routes if they are not cached.
        if (!$this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }

        // Load the views from the module's Resources/views folder.
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'credentialsbank');
    }
}