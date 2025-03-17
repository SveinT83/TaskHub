<?php

namespace Modules\Projects\Providers;

use Illuminate\Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ✅ Make sure Laravel knows where to find views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'projects'); // 🔥 Updated Path
        
        // ✅ Ensure routes are also loaded in `boot()`
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
    
    public function register()
    {
        // No need to load anything here
    }
}