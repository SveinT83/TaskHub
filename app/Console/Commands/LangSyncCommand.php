<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LangSyncCommand extends Command
{
    protected $signature = 'lang:sync {locale : The locale to sync}';
    protected $description = 'Stub missing keys for a locale with empty values';

    public function handle()
    {
        $locale = $this->argument('locale');
        $supportedLocales = config('app.supported_locales', []);
        
        if (!array_key_exists($locale, $supportedLocales)) {
            $this->error("Locale '{$locale}' is not supported.");
            $this->info('Supported locales: ' . implode(', ', array_keys($supportedLocales)));
            return Command::FAILURE;
        }

        $basePath = resource_path('lang/en');
        $targetPath = resource_path("lang/{$locale}");

        if (!File::exists($basePath)) {
            $this->error("Base locale directory not found: {$basePath}");
            return Command::FAILURE;
        }

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
            $this->info("Created directory: {$targetPath}");
        }

        $files = File::files($basePath);
        $synced = 0;
        
        foreach ($files as $file) {
            $filename = $file->getFilename();
            $baseTranslations = include $file->getPathname();
            $targetFile = "{$targetPath}/{$filename}";
            
            $targetTranslations = File::exists($targetFile) 
                ? include $targetFile 
                : [];

            $merged = $this->mergeTranslations($baseTranslations, $targetTranslations);
            
            $content = "<?php\n\nreturn " . var_export($merged, true) . ";\n";
            File::put($targetFile, $content);
            
            $this->info("Synced: {$filename}");
            $synced++;
        }

        // Also check modules
        $modulesPath = base_path('modules');
        if (File::exists($modulesPath)) {
            $this->syncModules($locale, $modulesPath);
        }

        $this->info("Successfully synced {$synced} files for locale: {$locale}");
        return Command::SUCCESS;
    }

    private function mergeTranslations(array $base, array $target): array
    {
        foreach ($base as $key => $value) {
            if (is_array($value)) {
                $target[$key] = $this->mergeTranslations(
                    $value, 
                    $target[$key] ?? []
                );
            } else {
                if (!isset($target[$key]) || $target[$key] === '') {
                    $target[$key] = '';
                }
            }
        }
        return $target;
    }

    private function syncModules(string $locale, string $modulesPath): void
    {
        $modules = File::directories($modulesPath);
        
        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $langPath = "{$modulePath}/Resources/lang";
            
            if (!File::exists($langPath)) {
                continue;
            }
            
            $baseLangPath = "{$langPath}/en";
            $targetLangPath = "{$langPath}/{$locale}";
            
            if (!File::exists($baseLangPath)) {
                continue;
            }
            
            if (!File::exists($targetLangPath)) {
                File::makeDirectory($targetLangPath, 0755, true);
            }
            
            $files = File::files($baseLangPath);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $baseTranslations = include $file->getPathname();
                $targetFile = "{$targetLangPath}/{$filename}";
                
                $targetTranslations = File::exists($targetFile) 
                    ? include $targetFile 
                    : [];

                $merged = $this->mergeTranslations($baseTranslations, $targetTranslations);
                
                $content = "<?php\n\nreturn " . var_export($merged, true) . ";\n";
                File::put($targetFile, $content);
            }
            
            $this->info("Synced module: {$moduleName}");
        }
    }
}
