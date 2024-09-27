<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Opprett menu_items tabellen
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id'); // Refererer til 'menus' tabellen
            $table->unsignedBigInteger('parent_id')->nullable(); // Kan referere til et annet menu_item som parent
            $table->string('title');
            $table->string('url');
            $table->string('permission')->nullable(); // Kan være null hvis ikke kreves tillatelser
            $table->integer('order')->default(0);
            $table->timestamps();

            // Fremmednøkkel mot menus-tabellen
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });

        // Legg til menyelementer under Admin-menyen (menu_id = 1)
        $adminMenuId = 1; // Admin-meny er alltid ID 1

        // Opprett hovedmeny-element for "Integrations"
        $integrationsParentId = DB::table('menu_items')->insertGetId([
            'menu_id' => $adminMenuId,
            'parent_id' => null,
            'title' => 'Integrations',
            'url' => '#', // Ikke en spesifikk URL, bare en dropdown
            'permission' => 'admin.access',
            'order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Legg til Nextcloud under Integrations
        DB::table('menu_items')->insert([
            [
                'menu_id' => $adminMenuId,
                'parent_id' => $integrationsParentId,
                'title' => 'Nextcloud Settings',
                'url' => '/admin/integrations/nextcloud',
                'permission' => 'admin.access',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $adminMenuId,
                'parent_id' => $integrationsParentId,
                'title' => 'Toggle Nextcloud Integration',
                'url' => '/admin/integrations/nextcloud/toggle',
                'permission' => 'admin.access',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Opprett menyelementer for "Roles" og "Permissions"
        $rolesParentId = DB::table('menu_items')->insertGetId([
            'menu_id' => $adminMenuId,
            'parent_id' => null,
            'title' => 'Roles & Permissions',
            'url' => '#',
            'permission' => 'admin.access',
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Legg til under-menyelementer for roller og tillatelser
        DB::table('menu_items')->insert([
            [
                'menu_id' => $adminMenuId,
                'parent_id' => $rolesParentId,
                'title' => 'Manage Roles',
                'url' => '/roles',
                'permission' => 'roles.manage',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $adminMenuId,
                'parent_id' => $rolesParentId,
                'title' => 'Create Permission',
                'url' => '/permissions/create',
                'permission' => 'permissions.create',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $adminMenuId,
                'parent_id' => $rolesParentId,
                'title' => 'Edit Permission',
                'url' => '/permissions/{permission}/edit',
                'permission' => 'permissions.edit',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Opprett menyelement for "Menu Configurations"
        DB::table('menu_items')->insert([
            'menu_id' => $adminMenuId,
            'parent_id' => null,
            'title' => 'Menu Configurations',
            'url' => '/configurations/menu',
            'permission' => 'menu.config',
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
