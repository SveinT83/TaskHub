<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMenuForTaskModule extends Migration
{
    public function up()
    {
        // Legg til "Tasks" i brukerens meny
        $menu = DB::table('menus')->where('slug', 'tasks')->first();

        if (!$menu) {
            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Tasks',
                'slug' => 'tasks',
                'description' => 'Task management and task walls',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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

        // Legg til "Tasks" i Admin-menyen (menu_id = 1 for admin)
        $adminParentId = DB::table('menu_items')->insertGetId([
            'menu_id' => 1,
            'parent_id' => null,
            'title' => 'Tasks',
            'url' => '#',
            'icon' => 'bi bi-list-task',
            'order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('menu_items')->insert([
            [
                'menu_id' => 1,
                'parent_id' => $adminParentId,
                'title' => 'Config',
                'url' => '/admin/tasks/config',
                'icon' => 'bi bi-tools',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down()
    {
        // Fjern "Tasks" menyen og elementene
        DB::table('menus')->where('slug', 'tasks')->delete();
        DB::table('menu_items')->where('menu_id', 1)->where('title', 'Tasks')->delete(); // Admin-menyen
    }
}
