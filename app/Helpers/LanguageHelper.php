<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

class LanguageHelper
{
    /**
     * Get all available locales with their display names.
     */
    public static function getAvailableLocales(): array
    {
        return config('app.supported_locales', []);
    }

    /**
     * Get current locale.
     */
    public static function getCurrentLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Get fallback locale.
     */
    public static function getFallbackLocale(): string
    {
        return config('app.fallback_locale', 'en');
    }

    /**
     * Check if a locale is supported.
     */
    public static function isLocaleSupported(string $locale): bool
    {
        return array_key_exists($locale, self::getAvailableLocales());
    }

    /**
     * Get locale display name.
     */
    public static function getLocaleDisplayName(string $locale): string
    {
        $locales = self::getAvailableLocales();
        return $locales[$locale] ?? $locale;
    }

    /**
     * Get all translation keys for a specific namespace and locale.
     */
    public static function getTranslationKeys(string $namespace = 'core', string $locale = null): array
    {
        $locale = $locale ?? self::getCurrentLocale();
        $translations = [];

        if ($namespace === 'core') {
            $path = resource_path("lang/{$locale}");
        } else {
            $path = base_path("modules/{$namespace}/Resources/lang/{$locale}");
        }

        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $data = include $file->getPathname();
                    if (is_array($data)) {
                        $flattened = self::flattenArray($data, "{$namespace}::{$filename}");
                        $translations = array_merge($translations, $flattened);
                    }
                }
            }
        }

        return $translations;
    }

    /**
     * Flatten a nested array with dot notation.
     */
    public static function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $result = array_merge($result, self::flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    /**
     * Unflatten a dot notation array back to nested array.
     */
    public static function unflattenArray(array $array): array
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

    /**
     * Get missing translation keys for a locale.
     */
    public static function getMissingTranslations(string $locale, string $baseLocale = 'en'): array
    {
        $baseTranslations = self::getAllTranslations($baseLocale);
        $localeTranslations = self::getAllTranslations($locale);

        $missing = [];

        foreach ($baseTranslations as $key => $value) {
            if (!isset($localeTranslations[$key]) || empty($localeTranslations[$key])) {
                $missing[$key] = $value;
            }
        }

        return $missing;
    }

    /**
     * Get all translations for a locale across all namespaces.
     */
    public static function getAllTranslations(string $locale): array
    {
        $translations = [];

        // Core translations
        $translations = array_merge($translations, self::getTranslationKeys('core', $locale));

        // Module translations
        $modulesPath = base_path('modules');
        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);
            foreach ($modules as $module) {
                $moduleName = basename($module);
                $moduleTranslations = self::getTranslationKeys($moduleName, $locale);
                $translations = array_merge($translations, $moduleTranslations);
            }
        }

        return $translations;
    }

    /**
     * Format a translation key for display.
     */
    public static function formatKeyForDisplay(string $key): string
    {
        // Remove namespace prefix for display
        $parts = explode('::', $key);
        $keyPart = end($parts);
        
        // Convert dot notation to readable format
        return str_replace('.', ' → ', $keyPart);
    }

    /**
     * Get translation statistics for a locale.
     */
    public static function getTranslationStats(string $locale): array
    {
        $baseTranslations = self::getAllTranslations('en');
        $localeTranslations = self::getAllTranslations($locale);

        $total = count($baseTranslations);
        $translated = count(array_filter($localeTranslations, function ($value) {
            return !empty($value);
        }));

        return [
            'total' => $total,
            'translated' => $translated,
            'missing' => $total - $translated,
            'percentage' => $total > 0 ? round(($translated / $total) * 100, 2) : 0,
        ];
    }
}
