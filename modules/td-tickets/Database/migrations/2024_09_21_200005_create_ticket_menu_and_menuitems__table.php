<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // Sjekk om "Tickets" menyen allerede finnes i "menus"-tabellen
            $menu = DB::table('menus')->where('slug', 'tickets')->first();

            if (!$menu) {
                // Opprett "Tickets" menyen hvis den ikke finnes
                $menuId = DB::table('menus')->insertGetId([
                    'name' => 'Tickets',
                    'slug' => 'tickets',
                    'description' => 'Tickets, categories and statuses',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Lag meny-elementer for Tickets uten parent_id
                DB::table('menu_items')->insert([
                    [
                        'menu_id' => $menuId,
                        'parent_id' => null,
                        'title' => 'Tickets',
                        'url' => '/tickets',
                        'permission' => 'tickets.view',
                        'order' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'menu_id' => $menuId,
                        'parent_id' => null,
                        'title' => 'Create Ticket',
                        'url' => '/tickets/create',
                        'permission' => 'tickets.create',
                        'order' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);

            }

            // Legg til admin-meny i menus-tabellen (menu_id=1 for admin)
            $adminParentId = DB::table('menu_items')->insertGetId([
                'menu_id' => 1, // Admin meny er alltid 1
                'parent_id' => null,
                'title' => 'Tickets',
                'url' => '#',
                'permission' => 'tickets.admin',
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Legg til Config under Tickets i admin-menyen
            DB::table('menu_items')->insert([
                'menu_id' => 1, // Admin meny er alltid 1
                'parent_id' => $adminParentId,
                'title' => 'Config',
                'url' => '/admin/tickets/config',
                'permission' => 'tickets.admin',
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            // Fjern "Tickets" menyen og elementene fra både tickets- og admin-menyen
            DB::table('menus')->where('slug', 'tickets')->delete();
            DB::table('menu_items')->where('menu_id', 1)->where('title', 'Tickets')->delete(); // For admin-menyen
        });
    }
};
