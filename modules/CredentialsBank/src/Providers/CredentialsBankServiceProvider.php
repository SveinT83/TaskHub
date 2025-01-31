<?php

namespace Modules\CredentialsBank\Providers;

use Illuminate\Support\ServiceProvider;

class CredentialsBankServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register any bindings or services here
    }

    public function boot()
    {
        // Load routes and views
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'credentialsbank');
    }
}
