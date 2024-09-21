<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sjekk om "Clients" menyen allerede finnes i "menus"-tabellen
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

            // Lag meny-elementer for Clients-modulen uten parent_id
            DB::table('menu_items')->insert([
                [
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'title' => 'Tickets',
                    'url' => '/tickets',
                    'icon' => 'bi bi-ticket',
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
                    'icon' => 'bi bi-plus',
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
            'icon' => 'bi bi-ticket',
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
            'icon' => 'bi bi-tools',
            'permission' => 'tickets.admin',
            'order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fjern "Clients" menyen og elementene fra både clients- og admin-menyen
        DB::table('menus')->where('slug', 'tickets')->delete();
        DB::table('menu_items')->where('menu_id', 1)->where('title', 'Tickets')->delete(); // For admin-menyen
    }
};
