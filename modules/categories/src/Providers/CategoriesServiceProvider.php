<?php
namespace TronderData\Categories\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TronderData\Categories\Http\Livewire\CategoryManager;

class CategoriesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
        Livewire::component('categories', CategoryManager::class);

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'categories');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    public function register()
    {
        // Optional service bindings
    }
}