<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // E-postadressen til "Super Admin"
        $adminEmail = 'admin@admin.com';

        // Sjekk om "Super Admin"-brukeren allerede finnes
        $adminId = DB::table('users')->where('email', $adminEmail)->value('id');

        // Opprett brukeren "Super Admin" dersom den ikke finnes
        if (!$adminId) {
            $adminId = DB::table('users')->insertGetId([
                'name' => 'Super Admin',
                'email' => $adminEmail,
                'password' => Hash::make('JEstayeqU8h'), // Hashet passord
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Sjekk om rollen "superadmin" finnes
        $roleId = DB::table('roles')->where('name', 'superadmin')->value('id');

        if (!$roleId) {
            throw new Exception('Rollen "superadmin" finnes ikke. Vennligst opprett rollen før migrering.');
        }

        // Koble brukeren til "superadmin"-rollen i model_has_roles-tabellen
        $roleAssignmentExists = DB::table('model_has_roles')
            ->where('role_id', $roleId)
            ->where('model_id', $adminId)
            ->exists();

        if (!$roleAssignmentExists) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => 'App\Models\User',
                'model_id' => $adminId,
            ]);
        }
    }

    public function down()
    {
        // Fjern tilknytningen mellom brukeren og rollen i model_has_roles-tabellen
        $adminEmail = 'admin@admin.com';
        $adminId = DB::table('users')->where('email', $adminEmail)->value('id');

        if ($adminId) {
            DB::table('model_has_roles')
                ->where('model_id', $adminId)
                ->where('model_type', 'App\Models\User')
                ->delete();
        }

        // Slett "Super Admin"-brukeren
        DB::table('users')->where('email', $adminEmail)->delete();
    }
};