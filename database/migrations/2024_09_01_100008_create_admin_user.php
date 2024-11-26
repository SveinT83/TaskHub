<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    public function up()
    {
        // Opprett en standard admin-bruker hvis den ikke finnes
        $adminEmail = 'admin@example.com';
        $adminExists = DB::table('users')->where('email', $adminEmail)->exists();

        if (!$adminExists) {
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => $adminEmail,
                'password' => Hash::make('JEstayeqU8h'), // Hashet passord
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        // Fjern standard admin-bruker
        DB::table('users')->where('email', 'admin@example.com')->delete();
    }
};
