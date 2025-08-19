<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Livewire\Admin\Meta\Index;
use App\Models\MetaData;
use App\Services\MetaDataService;
use Illuminate\Http\Request;

class StoreController extends Controller
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
     * Display the metadata for a module.
     *
     * @param string $module The module name
     * @return \Illuminate\View\View
     */
    public function store()
    {
        return view('admin.modules.store', []);
    }
}