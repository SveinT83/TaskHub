<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Sjekk om users-tabellen allerede finnes
        if (!Schema::hasTable('users')) {
            // Opprett users-tabellen
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

        // Sjekk om roles-tabellen eksisterer
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        }

        // Sjekk om model_has_roles-tabellen eksisterer
        if (!Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->primary(['role_id', 'model_id', 'model_type']);
            });
        }

        // Sjekk om det finnes noen brukere i systemet
        $userCount = DB::table('users')->count();

        // E-postadressen til "Super Admin"
        $adminEmail = 'admin@taskhub.com';

        // Sjekk om "Super Admin"-brukeren allerede finnes
        $adminId = DB::table('users')->where('email', $adminEmail)->value('id');

        // Opprett brukeren "Super Admin" dersom den ikke finnes eller det ikke er noen brukere i systemet
        if (!$adminId || $userCount === 0) {
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

        // Opprett rollen hvis den ikke finnes
        if (!$roleId) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'superadmin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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
        $adminEmail = 'admin@taskhub.com';
        $adminId = DB::table('users')->where('email', $adminEmail)->value('id');

        if ($adminId) {
            DB::table('model_has_roles')
                ->where('model_id', $adminId)
                ->where('model_type', 'App\Models\User')
                ->delete();
        }

        // Slett "Super Admin"-brukeren
        DB::table('users')->where('email', $adminEmail)->delete();

        // Slett tabellene
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
    }
};