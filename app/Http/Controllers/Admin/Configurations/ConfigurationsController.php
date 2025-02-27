<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - CONFIGURATIONSCONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller handles the Configurations view and any logic related to Configurations.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


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
        return view('admin.configurations.index');
    }
}
