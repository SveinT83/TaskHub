<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LangExportCommand extends Command
{
    protected $signature = 'lang:export {locale : The locale to export} {--format=csv : Export format (csv, json)}';
    protected $description = 'Export key/value pairs for external CAT tools';

    public function handle()
    {
        $locale = $this->argument('locale');
        $format = $this->option('format');
        
        $supportedLocales = config('app.supported_locales', []);
        if (!array_key_exists($locale, $supportedLocales)) {
            $this->error("Locale '{$locale}' is not supported.");
            return Command::FAILURE;
        }

        $supportedFormats = ['csv', 'json'];
        if (!in_array($format, $supportedFormats)) {
            $this->error("Format '{$format}' is not supported.");
            $this->info('Supported formats: ' . implode(', ', $supportedFormats));
            return Command::FAILURE;
        }

        $translations = $this->collectTranslations($locale);
        $filename = "translations_{$locale}_" . date('Y-m-d_H-i-s') . ".{$format}";
        $filepath = storage_path("app/exports/{$filename}");
        
        // Ensure export directory exists
        File::ensureDirectoryExists(dirname($filepath));

        if ($format === 'csv') {
            $this->exportToCsv($translations, $filepath);
        } else {
            $this->exportToJson($translations, $filepath);
        }

        $this->info("Exported {$locale} translations to: {$filepath}");
        $this->info("Total keys exported: " . count($translations));
        
        return Command::SUCCESS;
    }

    private function collectTranslations(string $locale): array
    {
        $translations = [];
        
        // Core translations
        $corePath = resource_path("lang/{$locale}");
        if (File::exists($corePath)) {
            $this->collectFromDirectory($corePath, 'core', $translations);
        }
        
        // Module translations
        $modulesPath = base_path('modules');
        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);
            
            foreach ($modules as $modulePath) {
                $moduleName = basename($modulePath);
                $langPath = "{$modulePath}/Resources/lang/{$locale}";
                
                if (File::exists($langPath)) {
                    $this->collectFromDirectory($langPath, $moduleName, $translations);
                }
            }
        }
        
        return $translations;
    }

    private function collectFromDirectory(string $path, string $namespace, array &$translations): void
    {
        $files = File::files($path);
        
        foreach ($files as $file) {
            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $data = include $file->getPathname();
            
            $this->flattenArray($data, $translations, "{$namespace}::{$filename}");
        }
    }

    private function flattenArray(array $array, array &$result, string $prefix = ''): void
    {
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            
            if (is_array($value)) {
                $this->flattenArray($value, $result, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }
    }

    private function exportToCsv(array $translations, string $filepath): void
    {
        $handle = fopen($filepath, 'w');
        
        // Write header
        fputcsv($handle, ['Key', 'Translation']);
        
        // Write data
        foreach ($translations as $key => $value) {
            fputcsv($handle, [$key, $value]);
        }
        
        fclose($handle);
    }

    private function exportToJson(array $translations, string $filepath): void
    {
        $json = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        File::put($filepath, $json);
    }
}
