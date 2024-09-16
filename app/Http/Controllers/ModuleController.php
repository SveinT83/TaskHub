<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ModuleController extends Controller
{
    public function index()
    {
        // Hent alle installerte moduler fra databasen
        $modules = Module::all();
        
        return view('modules.index', compact('modules'));
    }

    public function toggleStatus($id)
    {
        $module = Module::findOrFail($id);
        $module->is_active = !$module->is_active;
        $module->save();

        return redirect()->route('modules.index')->with('status', 'Module status updated!');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'module' => 'required|mimes:zip',
        ]);

        $file = $request->file('module');
        $moduleName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Lagre den opplastede modulen midlertidig
        $path = $file->storeAs('temp', $file->getClientOriginalName());

        // Pakk ut zip-filen til resources/modules
        $zip = new ZipArchive;
        if ($zip->open(storage_path('app/' . $path)) === TRUE) {
            $zip->extractTo(resource_path('modules/' . $moduleName));
            $zip->close();

            // Fjern den midlertidige zip-filen
            Storage::delete($path);

            // Oppdater databasen hvis nødvendig
            Module::create([
                'name' => ucfirst($moduleName),
                'slug' => strtolower($moduleName),
                'description' => 'Newly uploaded module',
                'is_active' => false,  // Sett som inaktiv til den aktiveres manuelt
            ]);

            return redirect()->route('modules.index')->with('status', 'Module uploaded successfully!');
        }
        
        return redirect()->route('modules.index')->with('error', 'Failed to upload module.');
    }

}
