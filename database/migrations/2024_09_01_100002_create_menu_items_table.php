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
        // Opprett "menus" tabellen
        Schema::create('menus', function (Blueprint $table) {
            $table->id(); // Automatisk generert primærnøkkel
            $table->string('name')->unique(); // Unik navn for menyen
            $table->string('slug')->unique(); // Slug for å identifisere menyen
            $table->string('description')->nullable(); // Valgfri beskrivelse
            $table->timestamps();
        });

        // Sjekk om Admin-menyen eksisterer, og opprett den hvis den ikke finnes
        $adminMenuExists = DB::table('menus')->where('slug', 'admin')->exists();
        if (!$adminMenuExists) {
            DB::table('menus')->insert([
                'id' => 1, // Sørg for at Admin-menyen får ID 1
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'The admin menu',
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
