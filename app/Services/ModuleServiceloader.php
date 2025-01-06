<?php

namespace App\Services;

class ModuleServiceLoader
{
    /**
     * Load and register all service providers from the modules directory.
     *
     * @param \Illuminate\Foundation\Application $app
     * @param string $modulePath
     * @param string $namespace
     * @return void
     */
    public static function register($app, $modulePath, $namespace = 'Modules')
    {
        foreach (scandir($modulePath) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $providerPath = $modulePath . '/' . $module . '/Providers';
            if (is_dir($providerPath)) {
                foreach (scandir($providerPath) as $file) {
                    if (str_ends_with($file, 'ServiceProvider.php')) {
                        $providerClass = $namespace . '\\' . $module . '\\Providers\\' . pathinfo($file, PATHINFO_FILENAME);
                        if (class_exists($providerClass)) {
                            $app->register($providerClass);
                        }
                    }
                }
            }
        }
    }
}
