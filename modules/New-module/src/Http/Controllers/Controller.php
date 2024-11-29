<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\View;

abstract class Controller
{
    public function __construct()
    {
        // Hent alle hovedmenyene med tilhørende menyelementer
        $menus = Menu::with(['items' => function ($query) {
            $query->whereNull('parent_id')->with('children');
        }])->get();

        // Debugging: Kommenter ut disse linjene for å unngå konflikter med visningen
        // foreach ($menus as $menu) {
        //     echo "<h1>{$menu->name}</h1>";
        //     foreach ($menu->items as $item) {
        //         echo "<p>{$item->title}</p>";
        //     }
        // }

        // Del menyene med alle views
        View::share('menus', $menus);
    }
}

