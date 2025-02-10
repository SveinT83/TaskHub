<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Modules\CredentialsBank\Providers\CredentialsBankServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (App::runningInConsole() || request()->is('credentials-bank/*')) {
            $this->app->register(CredentialsBankServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            database_path('migrations'),
            base_path('modules/FacebookPostingModule/database/migrations'),
            base_path('modules/CredentialsBank/database/migrations'),
        ]);
    }

}
