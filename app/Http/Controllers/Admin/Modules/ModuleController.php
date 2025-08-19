<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ModuleController extends Controller
{
    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // --------------------------------------------------------------------------------------------------
    // This function returns the view for the module index.
    // It can be used to list all modules or provide an overview of available modules.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {
        $modules = Module::all();
        return view('admin.modules.index', ['modules' => $modules]);
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SHOW
    // --------------------------------------------------------------------------------------------------
    // This function displays the details for a specific module.
    // It can be used to show module-specific metadata or other relevant information.
    // @param string $slug The module slug
    // @return \Illuminate\View\View
    // --------------------------------------------------------------------------------------------------
    public function show($slug)
    {
        $module = Module::where('slug', $slug)->firstOrFail();
        return view('admin.modules.show', ['module' => $module]);
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RESCAN
    // --------------------------------------------------------------------------------------------------
    // Scan the filesystem for modules and update the database.
    // --------------------------------------------------------------------------------------------------
    public function rescan()
    {
        $modulesPath = base_path('modules');
        if (!is_dir($modulesPath)) {
            return back()->with('error', 'Modules directory not found.');
        }

        foreach (File::directories($modulesPath) as $dir) {
            $json = $dir . '/module.json';
            if (File::exists($json)) {
                $meta = json_decode(File::get($json), true);
                $slug = basename($dir);

                Module::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $meta['name'] ?? $slug,
                        'version' => $meta['version'] ?? null,
                        'description' => $meta['description'] ?? null,
                        'path' => $dir,
                        'enabled' => $meta['enabled'] ?? true,
                    ]
                );
            }
        }

        return redirect()->route('admin.modules.index')->with('success', 'Modules rescanned and database updated.');
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - ENABLE
    // --------------------------------------------------------------------------------------------------
    // Enable a module.
    // --------------------------------------------------------------------------------------------------
    public function enable($slug)
    {
        Module::where('slug', $slug)->update(['enabled' => true]);
        return back()->with('success', 'Module enabled.');
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - DISABLE
    // --------------------------------------------------------------------------------------------------
    // Disable a module.
    // --------------------------------------------------------------------------------------------------
    public function disable($slug)
    {
        Module::where('slug', $slug)->update(['enabled' => false]);
        return back()->with('success', 'Module disabled.');
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - DESTROY
    // --------------------------------------------------------------------------------------------------
    // Delete a module (from DB and optionally filesystem).
    // --------------------------------------------------------------------------------------------------
    public function destroy($slug)
    {
        $module = Module::where('slug', $slug)->first();
        if ($module) {
            // Optionally: File::deleteDirectory($module->path);
            $module->delete();
        }
        return back()->with('success', 'Module deleted from database.');
    }
}