<?php

namespace Modules\Customers\Providers;

use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views/customers', 'customers');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
}