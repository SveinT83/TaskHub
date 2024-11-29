<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - INTEGRATIONS CONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling integrations related actions such as displaying the integrations view.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegrationsController extends Controller {

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // --------------------------------------------------------------------------------------------------
    // This function returns the integrations view.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Return the integrations view
        // -------------------------------------------------
        return view('admin.integrations.index');
    }
}
