<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - MENU
//
// The menu at the header. This controller is responsible for handling the menu.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers;

// -------------------------------------------------
// MODELL - MENU
// -------------------------------------------------
// This controller handles the operations related to the Menu model.
// It provides functionalities to manage menu items within the application.
// 
// The Menu model represents the structure and behavior of the menu items
// stored in the database.
// -------------------------------------------------
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
        // Retrieve the menu items.
        // -------------------------------------------------
        // This function is responsible for fetching the menu items from the database
        // or any other data source and returning them to the caller.
        // 
        // @return \Illuminate\Http\Response
        // -------------------------------------------------
        $menus = Menu::with(['items' => function ($query) {
            $query->whereNull('parent_id') // Fetch only top-level menu items
                  ->with('children');      // Fetch all child items
        }])->get();

        // -------------------------------------------------
        // Past the meny items to the view.
        // -------------------------------------------------
        return view('layouts.sidebar', compact('menus'));
    }
}
