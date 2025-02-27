<?php

namespace App\Providers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\ServiceProvider;
use App\Providers\NextcloudProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Utvid Socialite med Nextcloud-provideren
        Socialite::extend('nextcloud', function ($app) {
            $config = $app['config']['services.nextcloud'];
            return Socialite::buildProvider(NextcloudProvider::class, $config);
        });
    }
}
