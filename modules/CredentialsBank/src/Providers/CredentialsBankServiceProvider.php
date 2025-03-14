<?php

namespace Modules\CredentialsBank\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\CredentialsBank\Models\CredentialsBank;
use Modules\CredentialsBank\Policies\CredentialsBankPolicy;

class CredentialsBankServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        // Register the policy
        Gate::policy(CredentialsBank::class, CredentialsBankPolicy::class);

        // Only load routes if they are not cached
        if (!$this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }

        // Load views
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'credentialsbank');
    }
}