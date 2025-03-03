<?php

namespace tronderdata\TdTask\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class TdTaskServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tdtask');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

         // Superadmin kan lage templates
        Gate::define('superadmin.create', function ($user) {
            // Sjekk om tillatelsen finnes før vi prøver å sjekke den
            if (Permission::where('name', 'superadmin.create')->exists()) {
                return $user->hasPermissionTo('superadmin.create');
            }
            return false; // Returner false hvis tillatelsen ikke finnes
        });

        // Task admin kan også lage templates
        Gate::define('task.admin', function ($user) {
            // Sjekk om tillatelsen finnes før vi prøver å sjekke den
            if (Permission::where('name', 'task.admin')->exists()) {
                return $user->hasPermissionTo('task.admin');
            }
            return false; // Returner false hvis tillatelsen ikke finnes
        });
    }

    public function register()
    {
        //
    }
}
