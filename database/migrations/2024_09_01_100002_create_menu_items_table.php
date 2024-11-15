<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sjekk om tabellen allerede eksisterer før du oppretter den
        if (!Schema::hasTable('menus')) {
            // Opprett "menus" tabellen
            Schema::create('menus', function (Blueprint $table) {
                $table->id(); // Automatisk generert primærnøkkel
                $table->string('name')->unique(); // Unik navn for menyen
                $table->string('slug')->unique(); // Slug for å identifisere menyen
                $table->string('url')->nullable(); // Valgfri URL for menyen
                $table->string('description')->nullable(); // Valgfri beskrivelse
                $table->timestamps();
            });
        }

        // Sjekk om det finnes en meny med slug "admin" eller "settings", og opprett den hvis den ikke finnes
        $settingsMenuExists = DB::table('menus')->where('slug', 'settings')->exists();

        if (!$settingsMenuExists) {
            DB::table('menus')->insert([
                'id' => 99, // Sett ID til 99 for å plassere menyen sist
                'name' => 'Settings', // Navnet på menyen
                'slug' => 'settings', // Slug for å identifisere menyen
                'url' => '/settings', // URL-en vi lager en rute til senere
                'description' => 'Settings menu for managing application settings',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
