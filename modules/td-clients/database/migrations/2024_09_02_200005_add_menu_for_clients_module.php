<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMenuForClientsModule extends Migration
{
    public function up()
    {
        // Sjekk om "Clients" menyen allerede finnes i "menus"-tabellen
        $menu = DB::table('menus')->where('slug', 'clients')->first();

        if (!$menu) {
            // Opprett "Clients" menyen hvis den ikke finnes
            $menuId = DB::table('menus')->insertGetId([
                'name' => 'Clients',
                'slug' => 'clients',
                'description' => 'Clients, sites and users',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Lag meny-elementer for Clients-modulen uten parent_id
            DB::table('menu_items')->insert([
                [
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'title' => 'Clients',
                    'url' => '/clients',
                    'permission' => null,
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'title' => 'Sites',
                    'url' => '/client/sites',
                    'permission' => null,
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'title' => 'Users',
                    'url' => '/client/users',
                    'permission' => null,
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Legg til admin-meny i menus-tabellen (menu_id=1 for admin)
        $adminParentId = DB::table('menu_items')->insertGetId([
            'menu_id' => 1, // Admin meny er alltid 1
            'parent_id' => null,
            'title' => 'Clients',
            'url' => 'admin/clients/index',
            'order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Legg til Config under Clients i admin-menyen
        DB::table('menu_items')->insert([
            'menu_id' => 1, // Admin meny er alltid 1
            'parent_id' => $adminParentId,
            'title' => 'Config',
            'url' => '/admin/clients/config',
            'order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        // Fjern "Clients" menyen og elementene fra både clients- og admin-menyen
        DB::table('menus')->where('slug', 'clients')->delete();
        DB::table('menu_items')->where('menu_id', 1)->where('title', 'Clients')->delete(); // For admin-menyen
    }
}
