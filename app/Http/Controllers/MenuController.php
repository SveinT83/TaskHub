<?php
namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends Controller
{
    public function show()
    {
        // Henter alle hovedmenyene med tilhørende menyelementer
        $menus = Menu::with(['items' => function ($query) {
            $query->whereNull('parent_id') // Henter kun toppnivåmenyelementene
                  ->with('children');      // Henter alle underordnede elementer (child)
        }])->get();

        // Passer menyene til viewet
        return view('layouts.sidebar', compact('menus'));
    }
}
