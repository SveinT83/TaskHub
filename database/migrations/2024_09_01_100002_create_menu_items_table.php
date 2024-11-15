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
        // Sjekk om tabellen "menu_items" allerede er opprettet
        if (!Schema::hasTable('menu_items')) {
            // Opprett "menu_items"-tabellen
            Schema::create('menu_items', function (Blueprint $table) {
                $table->id();  // Primærnøkkel, automatisk generert
                $table->string('title');  // Navn på menyelementet, f.eks. "Users", "Admin", osv.
                $table->string('url');  // URL til menyelementet
                $table->unsignedBigInteger('menu_id')->nullable();  // Referanse til en overordnet meny (hvis aktuelt)
                $table->unsignedBigInteger('parent_id')->nullable();  // Referanse til et overordnet menyelement (hvis det er et underordnet)
                $table->integer('order')->default(0);  // Sorteringsrekkefølge
                $table->timestamps();  // Opprettet/oppdatert tidspunkter
            });
        }

        // Fjern menyelementet med ID 1 (Admin) hvis det allerede eksisterer
        DB::table('menu_items')->where('id', 1)->delete();

        // Sett inn nye menyelementer
        DB::table('menu_items')->insert([
            [
                'id' => 2,
                'title' => 'Users',
                'url' => '/admin/users/users',  // URL for Users-menyen
                'menu_id' => 1,  // Toppnivåadmin-menyen (ID 1)
                'parent_id' => null,  // Dette er et toppnivåelement
                'order' => 2,  // Sorteringsrekkefølge for toppnivåelementene
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'title' => 'Roles',
                'url' => '/admin/roles/roles',  // URL for Roles
                'menu_id' => 1,  // Parent-meny ID for Admin-menu
                'parent_id' => 2,  // Dette er et underordnet element for "Users"
                'order' => 1,  // Første child under "Users"
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'title' => 'Integrations',
                'url' => '/admin/integration',  // URL for Integrations-menyen
                'menu_id' => 1,  // Parent-meny ID (fortsatt under Admin via menu_id 1)
                'parent_id' => null,  // Dette er et toppnivåelement
                'order' => 3,  // Sorteringsrekkefølge for toppnivåelementene (etter Users)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'title' => 'Nextcloud',
                'url' => '/admin/integration/nextcloud',
                'menu_id' => 1,
                'parent_id' => 4,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'title' => 'Configurations',
                'url' => '/admin/configurations',
                'menu_id' => 1,
                'parent_id' => null,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'title' => 'Email Accounts',
                'url' => '/admin/configurations/email/email_accounts',
                'menu_id' => 1,
                'parent_id' => 6,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'title' => 'Menu',
                'url' => '/admin/configurations/menu',
                'menu_id' => 1,
                'parent_id' => 6,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'title' => 'Appearance',
                'url' => '/admin/appearance',
                'menu_id' => 1,
                'parent_id' => null,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fjern de aktuelle radene vi satt inn med ID-er 2, 3, 4, og 5
        DB::table('menu_items')->whereIn('id', [2, 3, 4, 5])->delete();

        // Valgfritt: Gjenopprett det opprinnelige menyelementet (Admin) hvis du kjører rollback
        // DB::table('menu_items')->insert([
        //     'id' => 1,
        //     'title' => 'Admin',
        //     'url' => '/admin',
        //     'menu_id' => null,
        //     'parent_id' => null,
        //     'order' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
};
