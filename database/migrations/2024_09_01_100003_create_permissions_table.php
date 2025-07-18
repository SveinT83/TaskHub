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
        if (!Schema::hasTable('permissions')) {
            // Opprett tabellen "permissions"
            Schema::create('permissions', function (Blueprint $table) {
                $table->id(); // Primærnøkkel
                $table->string('name')->index(); // Navn på tillatelsen
                $table->string('guard_name')->index(); // Guard for tillatelsen
                $table->timestamps(); // Opprettet og oppdatert tidspunkter
            });
        }

        // Sett inn standard superadmin tillatelser hvis de ikke allerede finnes
        $superadminPermissions = [
            ['name' => 'superadmin.view', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'superadmin.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'superadmin.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'superadmin.delete', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($superadminPermissions as $permission) {
            // Sjekk om tillatelsen allerede finnes
            $exists = DB::table('permissions')->where('name', $permission['name'])->exists();
            if (!$exists) {
                DB::table('permissions')->insert($permission);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Slett tabellen hvis den eksisterer
        Schema::dropIfExists('permissions');
    }
};