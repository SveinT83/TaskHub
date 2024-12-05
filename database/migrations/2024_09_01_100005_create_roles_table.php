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
        // Sjekk om tabellen allerede eksisterer
        if (!Schema::hasTable('roles')) {
            // Opprett tabellen "roles"
            Schema::create('roles', function (Blueprint $table) {
                $table->id(); // Primærnøkkel
                $table->string('name')->index(); // Navn på rollen
                $table->string('guard_name')->index(); // Guard navn (eks: web)
                $table->timestamps(); // Opprettet og oppdatert tidspunkter
            });
        }

        // Sjekk om admin-rollen allerede finnes
        $adminRoleExists = DB::table('roles')->where('name', 'admin')->exists();

        if (!$adminRoleExists) {
            // Opprett admin-rollen hvis den ikke finnes
            DB::table('roles')->insert([
                'name' => 'superadmin',
                'guard_name' => 'web', // Typisk guard for webapplikasjoner
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
        // Slett tabellen hvis den eksisterer
        Schema::dropIfExists('roles');
    }
};
