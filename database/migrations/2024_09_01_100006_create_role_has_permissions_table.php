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
        // Sjekk om tabellen "role_has_permissions" allerede eksisterer
        if (!Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade'); // Foreign key til permissions-tabellen
                $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Foreign key til roles-tabellen
                $table->primary(['permission_id', 'role_id']); // Primærnøkkel er en kombinasjon av permission_id og role_id
            });
        }

        // Hent rollen "superadmin" fra "roles"-tabellen
        $superadminRole = DB::table('roles')->where('name', 'superadmin')->first();

        if ($superadminRole) {
            // Hent alle "superadmin"-tillatelser fra "permissions"-tabellen
            $superadminPermissions = DB::table('permissions')
                ->whereIn('name', ['superadmin.view', 'superadmin.create', 'superadmin.edit', 'superadmin.delete'])
                ->pluck('id'); // Vi henter bare IDene

            // Sjekk om tillatelsene allerede er tilknyttet superadmin-rollen, hvis ikke, legg dem til
            foreach ($superadminPermissions as $permissionId) {
                $exists = DB::table('role_has_permissions')
                    ->where('role_id', $superadminRole->id)
                    ->where('permission_id', $permissionId)
                    ->exists();

                if (!$exists) {
                    DB::table('role_has_permissions')->insert([
                        'role_id' => $superadminRole->id,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Slett tabellen hvis den eksisterer
        Schema::dropIfExists('role_has_permissions');
    }
};
