<?php

namespace tronderdata\kbartickles\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class KbarticklesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Livewire::component('article-list', \tronderdata\kbartickles\Http\Livewire\ArticleList::class);
        Livewire::component('article-form', \tronderdata\kbartickles\Http\Livewire\ArticleForm::class);
        Livewire::component('article-view', \tronderdata\kbartickles\Http\Livewire\ArticleView::class);

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'kbartickles');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function register()
    {
        // Registration of any services
    }
}