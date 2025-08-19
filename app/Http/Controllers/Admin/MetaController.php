<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Livewire\Admin\Meta\Index;
use App\Models\MetaData;
use App\Services\MetaDataService;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    protected $metaDataService;
    
    /**
     * Create a new controller instance.
     *
     * @param MetaDataService $metaDataService
     */
    public function __construct(MetaDataService $metaDataService)
    {
        $this->metaDataService = $metaDataService;
    }

    /**
     * Display the metadata for an entity.
     *
     * @param string $type The entity type (e.g., 'clients')
     * @param int $id The entity ID
     * @return \Illuminate\View\View
     */
    public function index($type, $id)
    {
        // This route is used as a fallback if Livewire components aren't preferred
        return view('admin.meta.index', [
            'type' => $type,
            'id' => $id
        ]);
    }

    /**
     * Clean up metadata for a module.
     *
     * @param Request $request
     * @param string $module The module name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cleanupModule(Request $request, $module)
    {
        $count = $this->metaDataService->cleanupModule($module);
        
        return back()->with('success', "{$count} metadata entries were removed for module '{$module}'.");
    }
}
