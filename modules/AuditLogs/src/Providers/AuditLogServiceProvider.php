<?php

namespace Modules\AuditLogs\Providers;

use Illuminate\Support\ServiceProvider;

class AuditLogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'auditlogs');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
}