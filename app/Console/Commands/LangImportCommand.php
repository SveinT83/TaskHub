<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LangImportCommand extends Command
{
    protected $signature = 'lang:import {file : Path to the import file}';
    protected $description = 'Import translations and write to correct files';

    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return Command::FAILURE;
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        switch ($extension) {
            case 'csv':
                $translations = $this->importFromCsv($filePath);
                break;
            case 'json':
                $translations = $this->importFromJson($filePath);
                break;
            default:
                $this->error("Unsupported file format: {$extension}");
                $this->info('Supported formats: csv, json');
                return Command::FAILURE;
        }

        if (empty($translations)) {
            $this->error('No translations found in the file.');
            return Command::FAILURE;
        }

        $this->writeTranslations($translations);
        
        $this->info("Successfully imported " . count($translations) . " translations.");
        return Command::SUCCESS;
    }

    private function importFromCsv(string $filePath): array
    {
        $translations = [];
        $handle = fopen($filePath, 'r');
        
        // Skip header
        fgetcsv($handle);
        
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 2) {
                $translations[$row[0]] = $row[1];
            }
        }
        
        fclose($handle);
        return $translations;
    }

    private function importFromJson(string $filePath): array
    {
        $content = File::get($filePath);
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON format: ' . json_last_error_msg());
            return [];
        }
        
        return $data ?? [];
    }

    private function writeTranslations(array $translations): void
    {
        $grouped = $this->groupTranslationsByFile($translations);
        
        foreach ($grouped as $fileInfo => $data) {
            [$namespace, $locale, $filename] = explode('|', $fileInfo);
            
            if ($namespace === 'core') {
                $filePath = resource_path("lang/{$locale}/{$filename}.php");
            } else {
                $filePath = base_path("modules/{$namespace}/Resources/lang/{$locale}/{$filename}.php");
            }
            
            // Ensure directory exists
            File::ensureDirectoryExists(dirname($filePath));
            
            // Load existing translations
            $existing = File::exists($filePath) ? include $filePath : [];
            
            // Merge with new translations
            $merged = $this->unflattenArray($data);
            $final = array_merge_recursive($existing, $merged);
            
            // Write to file
            $content = "<?php\n\nreturn " . var_export($final, true) . ";\n";
            File::put($filePath, $content);
            
            $this->info("Updated: {$filePath}");
        }
    }

    private function groupTranslationsByFile(array $translations): array
    {
        $grouped = [];
        
        foreach ($translations as $key => $value) {
            // Parse key format: namespace::filename.key.subkey
            if (!preg_match('/^([^:]+)::([^.]+)\.(.+)$/', $key, $matches)) {
                $this->warn("Skipping invalid key format: {$key}");
                continue;
            }
            
            $namespace = $matches[1];
            $filename = $matches[2];
            $translationKey = $matches[3];
            
            // Extract locale from context or assume 'en'
            $locale = $this->option('locale') ?? 'en';
            
            $fileInfo = "{$namespace}|{$locale}|{$filename}";
            $grouped[$fileInfo][$translationKey] = $value;
        }
        
        return $grouped;
    }

    private function unflattenArray(array $array): array
    {
        $result = [];
        
        foreach ($array as $key => $value) {
            $keys = explode('.', $key);
            $current = &$result;
            
            foreach ($keys as $k) {
                if (!isset($current[$k])) {
                    $current[$k] = [];
                }
                $current = &$current[$k];
            }
            
            $current = $value;
        }
        
        return $result;
    }
}
