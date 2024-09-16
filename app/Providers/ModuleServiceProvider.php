<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\Module;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (Schema::hasTable('modules')) {
            // Hent alle aktive moduler fra databasen
            $modules = Module::where('is_active', true)->get();

            foreach ($modules as $module) {
                $modulePath = resource_path('modules/' . $module->slug . '/default');

                // Debug logging for å se hvilke moduler som blir lastet
                logger()->info("Loading module: " . $module->name);

                // Last inn rutene fra modulen
                if (File::exists($modulePath . '/routes/web.php')) {
                    logger()->info("Loading routes for module: " . $module->name);
                    Route::middleware('web')
                        ->namespace("App\\Http\\Controllers")
                        ->group($modulePath . '/routes/web.php');
                } else {
                    logger()->error("No routes found for module: " . $module->name);
                }

                // Last inn migrasjoner fra modulen (om nødvendig)
                if (File::exists($modulePath . '/migrations')) {
                    logger()->info("Loading migrations for module: " . $module->name);
                    $this->loadMigrationsFrom($modulePath . '/migrations');
                }

                // Last inn views fra modulen
                if (File::exists($modulePath . '/views')) {
                    logger()->info("Loading views for module: " . $module->name);
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
