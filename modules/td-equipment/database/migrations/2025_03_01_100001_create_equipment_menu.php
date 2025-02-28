<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateEquipmentMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Sjekk om menyen allerede finnes
        $existingMenu = DB::table('menus')->where('name', 'Equipment')->first();

        if (!$existingMenu) {
            // Opprett hovedmenyen
            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Equipment',
                'slug' => 'equipment',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $menuId = $existingMenu->id;
        }

        // Sjekk om meny-elementene allerede finnes
        $existingItems = DB::table('menu_items')
            ->where('menu_id', $menuId)
            ->whereIn('title', ['All', '+ New'])
            ->pluck('title')
            ->toArray();

        if (!in_array('All', $existingItems)) {
            DB::table('menu_items')->insert([
                'menu_id' => $menuId,
                'parent_id' => null, // Ingen overordnet
                'title' => 'All',
                'url' => '/equipment',
                'icon' => 'bi bi-list',
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!in_array('+ New', $existingItems)) {
            DB::table('menu_items')->insert([
                'menu_id' => $menuId,
                'parent_id' => null,
                'title' => '+ New',
                'url' => '/equipment/create',
                'icon' => 'bi bi-plus-lg',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $menu = DB::table('menus')->where('name', 'Equipment')->first();

        if ($menu) {
            DB::table('menu_items')->where('menu_id', $menu->id)->delete();
            DB::table('menus')->where('id', $menu->id)->delete();
        }
    }
}
