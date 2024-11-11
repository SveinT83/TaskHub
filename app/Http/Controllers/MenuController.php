<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - MENU
//
// The menu at the header. This controller is responsible for handling the menu.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends Controller
{

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SHOW
    // Shows the menu
    // --------------------------------------------------------------------------------------------------
    public function show()
    {
        // -------------------------------------------------
        // Get
        // -------------------------------------------------
        $menus = Menu::with(['items' => function ($query) {
            $query->whereNull('parent_id') // Henter kun toppnivåmenyelementene
                  ->with('children');      // Henter alle underordnede elementer (child)
        }])->get();

        // Passer menyene til viewet
        return view('layouts.sidebar', compact('menus'));
    }
}
