<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateUsersTable2 extends Migration
{
    public function up()
    {
        // Sjekk om users tabellen allerede finnes
        if (!Schema::hasTable('users')) {
            // Opprett users tabellen
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('nextcloud_token')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Sjekk om superadmin-brukeren allerede finnes
        $adminEmail = 'admin@example.com';
        $adminExists = DB::table('users')->where('email', $adminEmail)->exists();

        if (!$adminExists) {
            // Opprett admin-brukeren
            $adminId = DB::table('users')->insertGetId([
                'name' => 'Super Admin',
                'email' => $adminEmail,
                'password' => Hash::make('JEstayeqU8h'), // Hashet passord
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Finn superadmin tillatelsene fra permissions-tabellen
            $permissions = DB::table('permissions')
                ->whereIn('name', ['superadmin.view', 'superadmin.create', 'superadmin.edit', 'superadmin.delete'])
                ->pluck('id');

            // Sjekk om superadmin-rolle allerede finnes
            $roleId = DB::table('roles')->where('name', 'superadmin')->value('id');

            if (!$roleId) {
                // Opprett superadmin-rollen hvis den ikke finnes
                $roleId = DB::table('roles')->insertGetId([
                    'name' => 'superadmin',
                    'guard_name' => 'web', // Anta at vi bruker 'web' guard
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Koble admin-brukeren til superadmin-rollen
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => 'App\Models\User',
                'model_id' => $adminId,
            ]);

            // Gi superadmin-brukeren tillatelser
            foreach ($permissions as $permissionId) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            }
        }
    }

    public function down()
    {
        // Slett users-tabellen
        Schema::dropIfExists('users');
    }
}
