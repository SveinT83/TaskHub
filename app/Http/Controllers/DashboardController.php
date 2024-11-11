<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - DASHBOARDCONTROLLER
//
// This controller is responsible for handling the dashboard related actions such as displaying the dashboard.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // --------------------------------------------------------------------------------------------------
    // This function returns the dashboard view.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Retrieve the authenticated user.
        // -------------------------------------------------
        $user = auth()->user();

        // -------------------------------------------------
        // Return the view with the user's information.
        // -------------------------------------------------
        return view('dashboard');
    }
}
