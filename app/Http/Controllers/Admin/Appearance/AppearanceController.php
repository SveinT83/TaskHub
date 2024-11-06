<?php

namespace App\Http\Controllers\Admin\Appearance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER FOR Appearance
//
// This controller handles the Appearance view and any logic related to Appearance.
// ---------------------------------------------------------------------------------------------------------------------------------------------------
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
