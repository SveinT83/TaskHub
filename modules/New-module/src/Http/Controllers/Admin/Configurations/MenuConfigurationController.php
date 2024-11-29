<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - MENU CONFIGURATION CONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling menu related actions such as creating, updating, and deleting menus and menu items.
// ---------------------------------------------------------------------------------------------------------------------------------------------------
namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;

// -------------------------------------------------
// MENU - app/Models/Menu.PHP
// -------------------------------------------------
// This controller handles the configuration of the Menu model in the Admin section.
// It provides functionalities to manage and update menu settings.
// -------------------------------------------------
use App\Models\Menu;

// -------------------------------------------------
// MENU ITEM - app/Models/MenuItem.PHP
// -------------------------------------------------
// The MenuItem model represents an individual item in the menu configuration.
// It includes properties such as the item's name, URL, order, and any other
// relevant attributes that define a menu item within the application.
// -------------------------------------------------
use App\Models\MenuItem;

use Illuminate\Http\Request;

class MenuConfigurationController extends Controller
{
    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with all the menus.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Retrieve all the menus from the database.
        // -------------------------------------------------
        $menus = Menu::all();

        // -------------------------------------------------
        // Return the view with all the menus.
        // -------------------------------------------------
        return view('admin.configurations.menus.index', compact('menus'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - STORE
    // --------------------------------------------------------------------------------------------------
    // This function stores a new menu in the database.
    // --------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {

        // -------------------------------------------------
        // Validate the request data.
        // -------------------------------------------------
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:menus,slug',
            'description' => 'nullable'
        ]);

        // -------------------------------------------------
        // Create a new menu with the request data.
        // -------------------------------------------------
        Menu::create($request->all());

        // -------------------------------------------------
        // Redirect the user back to the menus page with a success message.
        // -------------------------------------------------
        return redirect()->route('admin.configurations.menus')->with('success', 'Menu created successfully');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SHOW
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with all the menu items.
    // --------------------------------------------------------------------------------------------------
    public function show(Menu $menu)
    {
        // -------------------------------------------------
        // Retrieve all the menu items for the specified menu.
        // -------------------------------------------------
        $menuItems = $menu->items;

        // -------------------------------------------------
        // Return the view with the menu and its items.
        // -------------------------------------------------
        return view('admin.configurations.menus.items', compact('menu', 'menuItems'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - STORE ITEM
    // --------------------------------------------------------------------------------------------------
    // This function stores a new menu item in the database.
    // --------------------------------------------------------------------------------------------------
    public function storeItem(Request $request, Menu $menu)
    {

        // -------------------------------------------------
        // Validate the request data.
        // -------------------------------------------------
        $request->validate([
            'title' => 'required',
            'url' => 'required',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer'
        ]);

        // -------------------------------------------------
        // Create a new menu item with the request data.
        // -------------------------------------------------
        $menu->items()->create($request->all());

        // -------------------------------------------------
        // Redirect the user back to the menu items page with a success message.
        // -------------------------------------------------
        return redirect()->route('admin.configurations.menus.items', $menu)->with('success', 'Menu item created successfully');
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - EDIT ITEM
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with the menu item to be edited.
    // --------------------------------------------------------------------------------------------------
    public function editItem(Menu $menu, MenuItem $item)
    {

        // -------------------------------------------------
        // Return the view with the menu and the item to be edited.
        // -------------------------------------------------
        return view('admin.configurations.menus.edit_item', compact('menu', 'item'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE ITEM
    // --------------------------------------------------------------------------------------------------
    // This function updates the menu item in the database.
    // --------------------------------------------------------------------------------------------------
    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {

        // -------------------------------------------------
        // Validate the request data.
        // -------------------------------------------------
        $request->validate([
            'title' => 'required',
            'url' => 'required',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer'
        ]);


        // -------------------------------------------------
        // Update the menu item with the request data.
        // -------------------------------------------------
        $item->update($request->all());

        // -------------------------------------------------
        // Redirect the user back to the menu items page with a success message.
        // -------------------------------------------------
        $menus = Menu::all();
        return view('admin.configurations.menus.index', compact('menus'))->with('success', 'Menu item updated successfully');
    }
}
