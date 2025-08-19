<?php

namespace App\Livewire\Admin\Modules;

use App\Models\Module;
use Livewire\Component;
use Illuminate\Support\Facades\File;

class ModuleManager extends Component
{
    public $modules;
    public $loading = false;
    public $selectedModule = null;

    public function mount()
    {
        $this->modules = Module::all();
    }

    public function rescan()
    {
        $this->loading = true;
        
        $modulesPath = base_path('modules');
        if (!is_dir($modulesPath)) {
            session()->flash('error', 'Modules directory not found.');
            $this->loading = false;
            return;
        }

        // Get existing modules before scan
        $existingModules = Module::pluck('slug')->toArray();
        $foundSlugs = [];
        $newModules = 0;
        $updatedModules = 0;

        // Scan filesystem for modules
        foreach (File::directories($modulesPath) as $dir) {
            $json = $dir . '/module.json';
            if (File::exists($json)) {
                $meta = json_decode(File::get($json), true);
                $slug = basename($dir);
                $foundSlugs[] = $slug;

                $existingModule = Module::where('slug', $slug)->first();
                
                if ($existingModule) {
                    // Update existing module
                    $existingModule->update([
                        'name' => $meta['name'] ?? $slug,
                        'version' => $meta['version'] ?? null,
                        'description' => $meta['description'] ?? null,
                        'path' => $dir,
                        'enabled' => $meta['enabled'] ?? $existingModule->enabled, // Keep current enabled state
                    ]);
                    $updatedModules++;
                } else {
                    // Create new module
                    Module::create([
                        'slug' => $slug,
                        'name' => $meta['name'] ?? $slug,
                        'version' => $meta['version'] ?? null,
                        'description' => $meta['description'] ?? null,
                        'path' => $dir,
                        'enabled' => $meta['enabled'] ?? true,
                    ]);
                    $newModules++;
                }
            }
        }

        // Remove modules that are no longer in filesystem
        $removedSlugs = array_diff($existingModules, $foundSlugs);
        $removedCount = 0;
        if (!empty($removedSlugs)) {
            Module::whereIn('slug', $removedSlugs)->delete();
            $removedCount = count($removedSlugs);
        }

        // Generate appropriate message
        $messages = [];
        if ($newModules > 0) {
            $messages[] = "{$newModules} new module(s) found";
        }
        if ($updatedModules > 0) {
            $messages[] = "{$updatedModules} module(s) updated";
        }
        if ($removedCount > 0) {
            $messages[] = "{$removedCount} module(s) removed (no longer in filesystem)";
        }

        if (empty($messages)) {
            $message = "No changes detected. All modules are up to date.";
        } else {
            $message = "Rescan complete: " . implode(', ', $messages) . ".";
        }

        $this->modules = Module::all();
        session()->flash('success', $message);
        $this->loading = false;
    }

    public function toggleModule($slug)
    {
        $module = Module::where('slug', $slug)->first();
        if ($module) {
            $module->update(['enabled' => !$module->enabled]);
            $this->modules = Module::all();
            
            $status = $module->enabled ? 'disabled' : 'enabled';
            session()->flash('success', "Module '{$module->name}' has been {$status}.");
        }
    }

    public function deleteModule($slug)
    {
        $module = Module::where('slug', $slug)->first();
        if ($module) {
            $moduleName = $module->name;
            $module->delete();
            $this->modules = Module::all();
            session()->flash('success', "Module '{$moduleName}' deleted from database.");
        }
    }

    public function showModuleDetails($slug)
    {
        $this->selectedModule = Module::where('slug', $slug)->first();
    }

    public function closeModal()
    {
        $this->selectedModule = null;
    }

    public function render()
    {
        return view('livewire.admin.modules.module-manager');
    }
}
