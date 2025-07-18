<?php

namespace App\Livewire\Admin\Configurations\Langue;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;

class TranslationEditor extends Component
{
    public $currentLocale = 'en';
    public $selectedNamespace = 'core';
    public $selectedFile = 'core';
    public $searchTerm = '';
    public $editingKey = null;
    public $editingValue = '';
    public $translations = [];
    public $availableLocales = [];
    public $availableNamespaces = [];
    public $availableFiles = [];

    protected $rules = [
        'editingValue' => 'required|string',
    ];

    public function mount()
    {
        $this->availableLocales = config('app.supported_locales', []);
        $this->currentLocale = app()->getLocale();
        $this->loadNamespaces();
        $this->loadFiles();
        $this->loadTranslations();
    }

    public function updatedCurrentLocale()
    {
        $this->loadFiles();
        $this->loadTranslations();
    }

    public function updatedSelectedNamespace()
    {
        $this->loadFiles();
        $this->selectedFile = $this->availableFiles[0] ?? '';
        $this->loadTranslations();
    }

    public function updatedSelectedFile()
    {
        $this->loadTranslations();
    }

    public function loadNamespaces()
    {
        $this->availableNamespaces = ['core'];
        
        // Add modules
        $modulesPath = base_path('modules');
        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);
            foreach ($modules as $module) {
                $moduleName = basename($module);
                $langPath = "{$module}/Resources/lang";
                if (File::exists($langPath)) {
                    $this->availableNamespaces[] = $moduleName;
                }
            }
        }
    }

    public function loadFiles()
    {
        $this->availableFiles = [];
        
        if ($this->selectedNamespace === 'core') {
            $path = resource_path("lang/{$this->currentLocale}");
        } else {
            $path = base_path("modules/{$this->selectedNamespace}/Resources/lang/{$this->currentLocale}");
        }
        
        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $this->availableFiles[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                }
            }
        }
        
        if (empty($this->availableFiles)) {
            $this->availableFiles = ['core'];
        }
    }

    public function loadTranslations()
    {
        $this->translations = [];
        
        if ($this->selectedNamespace === 'core') {
            $filePath = resource_path("lang/{$this->currentLocale}/{$this->selectedFile}.php");
        } else {
            $filePath = base_path("modules/{$this->selectedNamespace}/Resources/lang/{$this->currentLocale}/{$this->selectedFile}.php");
        }
        
        if (File::exists($filePath)) {
            $data = include $filePath;
            if (is_array($data)) {
                $this->translations = $this->flattenArray($data);
            }
        }
    }

    private function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        
        return $result;
    }

    public function editTranslation($key)
    {
        $this->editingKey = $key;
        $this->editingValue = $this->translations[$key] ?? '';
    }

    public function saveTranslation()
    {
        $this->validate();
        
        if (!$this->editingKey) {
            return;
        }
        
        // Update the translation
        $this->translations[$this->editingKey] = $this->editingValue;
        
        // Save to file
        $this->saveToFile();
        
        // Clear cache
        $this->clearTranslationCache();
        
        // Reset editing state
        $this->editingKey = null;
        $this->editingValue = '';
        
        $this->dispatch('translation-saved', [
            'key' => $this->editingKey,
            'locale' => $this->currentLocale,
        ]);
        
        session()->flash('message', __('core::notifications.updated', ['item' => 'Translation']));
    }

    public function cancelEdit()
    {
        $this->editingKey = null;
        $this->editingValue = '';
    }

    private function saveToFile()
    {
        if ($this->selectedNamespace === 'core') {
            $filePath = resource_path("lang/{$this->currentLocale}/{$this->selectedFile}.php");
        } else {
            $filePath = base_path("modules/{$this->selectedNamespace}/Resources/lang/{$this->currentLocale}/{$this->selectedFile}.php");
        }
        
        // Ensure directory exists
        File::ensureDirectoryExists(dirname($filePath));
        
        // Convert flat array back to nested
        $nested = $this->unflattenArray($this->translations);
        
        // Write to file
        $content = "<?php\n\nreturn " . var_export($nested, true) . ";\n";
        File::put($filePath, $content);
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

    private function clearTranslationCache()
    {
        Lang::flush();
        Cache::tags(['locale'])->flush();
    }

    public function getFilteredTranslationsProperty()
    {
        if (empty($this->searchTerm)) {
            return $this->translations;
        }
        
        return array_filter($this->translations, function ($value, $key) {
            return stripos($key, $this->searchTerm) !== false || 
                   stripos($value, $this->searchTerm) !== false;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function render()
    {
        return view('livewire.admin.configurations.langue.translation-editor');
    }
}
