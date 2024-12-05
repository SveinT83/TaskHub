<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sjekk om tabellene "menu_items" og "menus" eksisterer
        if (Schema::hasTable('menu_items') && Schema::hasTable('menus')) {
            // Hent "menu_id" for raden i "menus"-tabellen med navn "TD Salgs Skjema"
            $menuId = DB::table('menus')->where('name', 'TD Salgs Skjema')->value('id');

            if ($menuId) {
                // Opprett "Dashboard"-elementet og hent dets ID
                $dashboardItemId = DB::table('menu_items')->insertGetId([
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'title' => 'Dashboard',
                    'url' => '/tdsalgsskjema',
                    'order' => 1,
                ]);

                // Definer de andre meny-elementene
                $menuItems = [
                    [
                        'menu_id' => $menuId,
                        'parent_id' => $dashboardItemId, // Bruk ID-en til "Dashboard"
                        'title' => 'Nytt skjema',
                        'url' => '/tdsalgsskjema/create',
                        'order' => 2,
                    ],
                    [
                        'menu_id' => 1,
                        'parent_id' => null,
                        'title' => 'TD Salgs Skjema',
                        'url' => '/admin/tdsalgsskjema/settings',
                        'order' => 1,
                    ],
                ];

                // Sett inn de andre elementene hvis de ikke allerede eksisterer
                foreach ($menuItems as $item) {
                    $exists = DB::table('menu_items')
                        ->where('menu_id', $item['menu_id'])
                        ->where('title', $item['title'])
                        ->exists();

                    if (!$exists) {
                        DB::table('menu_items')->insert($item);
                    }
                }
            }
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Slett meny-elementer hvis nødvendig
        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')->where('url', 'LIKE', '/tdsalgsskjema%')->delete();
        }
    }
};
