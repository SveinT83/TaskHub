<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'inventory');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
}