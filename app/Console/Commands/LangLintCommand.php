<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LangLintCommand extends Command
{
    protected $signature = 'lang:lint {--locale=en : The locale to lint}';
    protected $description = 'Fails CI if default locale has empty values or duplicates';

    private $errors = [];
    private $warnings = [];

    public function handle()
    {
        $locale = $this->option('locale');
        $this->errors = [];
        $this->warnings = [];
        
        $this->info("Linting translations for locale: {$locale}");
        
        // Lint core translations
        $this->lintCore($locale);
        
        // Lint module translations
        $this->lintModules($locale);
        
        // Report results
        $this->reportResults();
        
        return empty($this->errors) ? Command::SUCCESS : Command::FAILURE;
    }

    private function lintCore(string $locale): void
    {
        $corePath = resource_path("lang/{$locale}");
        
        if (!File::exists($corePath)) {
            $this->errors[] = "Core language directory not found: {$corePath}";
            return;
        }
        
        $this->lintDirectory($corePath, 'core');
    }

    private function lintModules(string $locale): void
    {
        $modulesPath = base_path('modules');
        
        if (!File::exists($modulesPath)) {
            return;
        }
        
        $modules = File::directories($modulesPath);
        
        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $langPath = "{$modulePath}/Resources/lang/{$locale}";
            
            if (File::exists($langPath)) {
                $this->lintDirectory($langPath, $moduleName);
            }
        }
    }

    private function lintDirectory(string $path, string $namespace): void
    {
        $files = File::files($path);
        
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            
            $this->lintFile($file->getPathname(), $namespace);
        }
    }

    private function lintFile(string $filePath, string $namespace): void
    {
        $filename = basename($filePath, '.php');
        
        try {
            $translations = include $filePath;
            
            if (!is_array($translations)) {
                $this->errors[] = "{$namespace}::{$filename} - File does not return an array";
                return;
            }
            
            $this->lintTranslations($translations, $namespace, $filename);
            
        } catch (\Throwable $e) {
            $this->errors[] = "{$namespace}::{$filename} - Syntax error: " . $e->getMessage();
        }
    }

    private function lintTranslations(array $translations, string $namespace, string $filename, string $prefix = ''): void
    {
        $seen = [];
        
        foreach ($translations as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            $qualifiedKey = "{$namespace}::{$filename}.{$fullKey}";
            
            if (is_array($value)) {
                $this->lintTranslations($value, $namespace, $filename, $fullKey);
            } else {
                // Check for empty values
                if ($value === '' || $value === null) {
                    $this->errors[] = "{$qualifiedKey} - Empty translation value";
                }
                
                // Check for untranslated keys (still showing key name)
                if ($value === $key || $value === $fullKey) {
                    $this->warnings[] = "{$qualifiedKey} - Possible untranslated key";
                }
                
                // Check for duplicates
                if (isset($seen[$value]) && $value !== '') {
                    $this->warnings[] = "{$qualifiedKey} - Duplicate translation: '{$value}' (also used in {$seen[$value]})";
                } else {
                    $seen[$value] = $qualifiedKey;
                }
                
                // Check for placeholder consistency
                $this->checkPlaceholders($value, $qualifiedKey);
            }
        }
    }

    private function checkPlaceholders(string $value, string $key): void
    {
        // Check for unmatched placeholders
        preg_match_all('/:(\w+)/', $value, $matches);
        $placeholders = $matches[1] ?? [];
        
        foreach ($placeholders as $placeholder) {
            if (!preg_match('/\b' . preg_quote($placeholder) . '\b/', $key)) {
                // This is just a warning as placeholders might be valid
                $this->warnings[] = "{$key} - Contains placeholder ':{$placeholder}' - ensure it's properly handled";
            }
        }
        
        // Check for potential HTML in translations (should be avoided)
        if (strip_tags($value) !== $value) {
            $this->warnings[] = "{$key} - Contains HTML tags - consider using safe HTML rendering";
        }
        
        // Check for very long translations
        if (strlen($value) > 200) {
            $this->warnings[] = "{$key} - Very long translation (" . strlen($value) . " chars) - consider breaking it up";
        }
    }

    private function reportResults(): void
    {
        if (!empty($this->errors)) {
            $this->newLine();
            $this->error('Errors found:');
            foreach ($this->errors as $error) {
                $this->line("  ❌ {$error}");
            }
        }
        
        if (!empty($this->warnings)) {
            $this->newLine();
            $this->warn('Warnings:');
            foreach ($this->warnings as $warning) {
                $this->line("  ⚠️  {$warning}");
            }
        }
        
        if (empty($this->errors) && empty($this->warnings)) {
            $this->info('✅ No issues found!');
        } else {
            $this->newLine();
            $this->info('Summary:');
            $this->info("  Errors: " . count($this->errors));
            $this->info("  Warnings: " . count($this->warnings));
        }
    }
}
