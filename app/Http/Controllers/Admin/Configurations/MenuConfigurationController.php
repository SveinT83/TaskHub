<?php
namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuConfigurationController extends Controller
{
    // Vis liste over menyer og opprettelsesformular
    public function index()
    {
        $menus = Menu::all(); // Henter alle menyer
        return view('admin.configurations.menus.index', compact('menus'));
    }

    // Lagre en ny meny
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:menus,slug',
            'description' => 'nullable'
        ]);

        Menu::create($request->all());

        return redirect()->route('admin.configurations.menus')->with('success', 'Menu created successfully');
    }

    // Vis menyens elementer
    public function show(Menu $menu)
    {
        $menuItems = $menu->items; // Henter alle elementene til menyen
        return view('admin.configurations.menus.items', compact('menu', 'menuItems'));
    }

    // Lagre nytt menyelement
    public function storeItem(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer'
        ]);

        $menu->items()->create($request->all());

        return redirect()->route('admin.configurations.menus.items', $menu)->with('success', 'Menu item created successfully');
    }

    // Rediger menyelement
    public function editItem(Menu $menu, MenuItem $item)
    {
        return view('admin.configurations.menus.edit_item', compact('menu', 'item'));
    }

    // Oppdater menyelement
    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer'
        ]);

        $item->update($request->all());

        //return redirect()->route('items', $menu)->with('success', 'Menu item updated successfully');
        $menus = Menu::all(); // Henter alle menyer
        return view('admin.configurations.menus.index', compact('menus'))->with('success', 'Menu item updated successfully');
    }
}
