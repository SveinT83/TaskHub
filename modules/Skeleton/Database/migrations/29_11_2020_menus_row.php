<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Opprett en ny meny i `menus`
        $menuId = DB::table('menus')->insertGetId([
            'name' => 'Skeleton',
            'slug' => 'skeleton',
            'description' => 'Meny for Skeleton-modulen',
            'url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Opprett en ny meny-item i `menu_items` knyttet til den nye menyen
        DB::table('menu_items')->insert([
            'title' => 'Hello World',
            'url' => '/skeleton',
            'menu_id' => $menuId,
            'parent_id' => null, // Ingen parent
            'icon' => 'bi bi-0-circle',
            'is_parent' => 0, // Ikke en parent
            'order' => 1, // Første element
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Fjern menyen og tilhørende meny-item
        $menuId = DB::table('menus')->where('slug', 'skeleton')->value('id');

        if ($menuId) {
            DB::table('menu_items')->where('menu_id', $menuId)->delete();
            DB::table('menus')->where('id', $menuId)->delete();
        }
    }
};
