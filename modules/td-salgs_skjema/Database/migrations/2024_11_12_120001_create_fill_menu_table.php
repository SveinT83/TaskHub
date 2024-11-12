<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sjekk om tabellen 'menu' eksisterer
        if (Schema::hasTable('menus')) {
            // Sjekk om raden med 'name' = 'TD Salgs Skjema' finnes
            $exists = DB::table('menus')->where('name', 'TD Salgs Skjema')->exists();

            if (!$exists) {
                // Opprett ny rad
                DB::table('menus')->insert([
                    'id' => DB::table('menus')->max('id') + 1, // Setter en ny id
                    'name' => 'TD Salgs Skjema',
                    'slug' => 'td-salgs-skjema',
                    'url' => '/tdsalgsskjema',
                    'description' => 'Meny for TD Salgs Skjema',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fjern raden dersom den eksisterer (valgfritt)
        if (Schema::hasTable('menus')) {
            DB::table('menus')->where('name', 'TD Salgs Skjema')->delete();
        }
    }
};