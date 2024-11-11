<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - APPEARANCECONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller handles the Appearance view and any logic related to Appearance.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Appearance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    // -------------------------------------------------
    // INDEX
    // -------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Return the Configurations view
        // -------------------------------------------------
        return view('admin.Appearance.index');
    }
}
