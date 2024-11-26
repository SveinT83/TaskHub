<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - ADMINCONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling admin related actions such as displaying the admin dashboard.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // --------------------------------------------------------------------------------------------------
    // This function returns the view for the admin dashboard.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Return the view for the admin dashboard.
        // -------------------------------------------------
        return view('admin.index');
    }
}
