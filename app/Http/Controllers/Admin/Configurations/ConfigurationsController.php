<?php

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER FOR Configurations
//
// This controller handles the Configurations view and any logic related to Configurations.
// ---------------------------------------------------------------------------------------------------------------------------------------------------
class ConfigurationsController extends Controller
{
    // -------------------------------------------------
    // INDEX
    // -------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Return the Configurations view
        // -------------------------------------------------
        return view('admin.Configurations.index');
    }
}
