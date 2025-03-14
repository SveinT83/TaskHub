namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $modulesPath = base_path('Modules');
        if (!File::exists($modulesPath)) {
            return;
        }

        $modules = File::directories($modulesPath);
        foreach ($modules as $module) {
            $moduleName = basename($module);
            $providerClass = "Modules\\{$moduleName}\\src\\Providers\\{$moduleName}ServiceProvider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    public function boot()
    {
        //
    }
}