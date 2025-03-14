<?php

namespace Modules\Invoicing\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Invoicing\src\Console\Commands\SyncTripletexInvoices;

class InvoicingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'invoicing');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        $this->commands([
            SyncTripletexInvoices::class,
        ]);
    }
}