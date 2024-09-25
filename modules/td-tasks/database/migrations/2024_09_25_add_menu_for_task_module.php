<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMenuForTaskModule extends Migration
{
    public function up()
    {
        // Opprett "Tasks" meny for brukermenyen
        $menu = DB::table('menus')->where('slug', 'tasks')->first();

        if (!$menu) {
            // Opprett hovedmenyen for "Tasks"
            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Tasks',
                'slug' => 'tasks',
                'description' => 'Task management and task walls',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Legg til Task og Wall i brukermenyen
            DB::table('menu_items')->insert([
                [
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'title' => 'Tasks',
                    'url' => '/tasks',
                    'icon' => 'bi bi-list-task',
                    'permission' => null,
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'title' => 'Walls',
                    'url' => '/walls',
                    'icon' => 'bi bi-columns',
                    'permission' => null,
                    'order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Opprett "Tasks" meny for Admin-menyen (always menu_id=1 for admin)
        $adminParentId = DB::table('menu_items')->insertGetId([
            'menu_id' => 1, // Admin-meny
            'parent_id' => null,
            'title' => 'Tasks',
            'url' => '#',
            'icon' => 'bi bi-list-task',
            'order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Legg til Config under Tasks i admin-menyen
        DB::table('menu_items')->insert([
            'menu_id' => 1, // Admin-meny
            'parent_id' => $adminParentId,
            'title' => 'Config',
            'url' => '/admin/tasks/config',
            'icon' => 'bi bi-tools',
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        // Fjern "Tasks" menyen og elementene fra både brukermeny og admin-menyen
        DB::table('menus')->where('slug', 'tasks')->delete();
        DB::table('menu_items')->where('menu_id', 1)->where('title', 'Tasks')->delete(); // For admin-menyen
    }
}
