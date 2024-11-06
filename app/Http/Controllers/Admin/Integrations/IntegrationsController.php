<?php

namespace App\Http\Controllers\Admin\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER FOR INTEGRATIONS
//
// This controller handles the integrations view and any logic related to integrations.
// ---------------------------------------------------------------------------------------------------------------------------------------------------
class IntegrationsController extends Controller {

    // -------------------------------------------------
    // INDEX
    // -------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Return the integrations view
        // -------------------------------------------------
        return view('admin.integrations.index');
    }
}
