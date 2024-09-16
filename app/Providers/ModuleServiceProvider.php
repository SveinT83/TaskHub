<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (Schema::hasTable('modules')) {
            $modules = Module::where('is_active', true)->get();

            foreach ($modules as $module) {
                $modulePath = resource_path('modules/' . $module->slug . '/default');

                // Last inn rutene fra modulen
                if (File::exists($modulePath . '/routes/web.php')) {
                    Route::middleware('web')
                        ->namespace("Modules\\{$module->name}\\default\\Controllers")
                        ->group($modulePath . '/routes/web.php');
                }

                // Last inn views fra modulen
                if (File::exists($modulePath . '/views')) {
                    $this->loadViewsFrom($modulePath . '/views', $module->slug);
                }
            }
        }
    }

    public function register()
    {
        //
    }
}
