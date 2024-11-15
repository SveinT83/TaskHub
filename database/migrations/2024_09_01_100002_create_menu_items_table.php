<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MIGRATION - MENU_ITEMS
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This migration creates the menu_items table in the database. It also inserts some initial menu items into the table.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

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

        // --------------------------------------------------------------------------------------------------
        // Create the menu_items table if it doesn't already exist.
        // --------------------------------------------------------------------------------------------------
        if (!Schema::hasTable('menu_items')) {
            Schema::create('menu_items', function (Blueprint $table) {

                // -------------------------------------------------
                // ID = Primary key
                // -------------------------------------------------
                $table->id();  // Primærnøkkel

                // -------------------------------------------------
                // title = Name of the menu item
                // -------------------------------------------------
                $table->string('title');  // Navn på menyelementet

                // -------------------------------------------------
                // url = URL of the menu item
                // if it is an admin menu item, then the url should start whit /admin /module name/side name
                // -------------------------------------------------
                $table->string('url');  // URL til menyelementet

                // -------------------------------------------------
                // menu_id = Parent menu
                // The id of the meny this item is part of. The Admin menu should always have the id 1
                // -------------------------------------------------
                $table->unsignedBigInteger('menu_id')->nullable();  // Overordnet meny

                // -------------------------------------------------
                // parent_id = Parent menu item
                // If this is a child menu item, this should be the id of the parent menu item
                // -------------------------------------------------
                $table->unsignedBigInteger('parent_id')->nullable();  // Overordnet menyelement

                // -------------------------------------------------
                // icon = Icon class, e.g. "bi bi-home"
                // By default we use the bootstrap icons, but you can use any icon library you want
                // -------------------------------------------------
                $table->string('icon')->nullable();  // Icon-klassen, f.eks. "bi bi-home"

                // -------------------------------------------------
                // is_parent = Is this a parent menu?
                // If this is a parent menu item, then this should be true
                // -------------------------------------------------
                $table->boolean('is_parent')->default(false);  // Om dette er en overordnet meny

                // -------------------------------------------------
                // order = Sort order
                // If yoy want to change the order of the menu items, you can do that by changing this value
                // -------------------------------------------------
                $table->integer('order')->default(0);  // Sorteringsrekkefølge

                // -------------------------------------------------
                // timestamps = Time stamps
                // Automatically created timestamps
                // -------------------------------------------------
                $table->timestamps();  // Tidsstempler
            });
        }        

        // -------------------------------------------------
        // Remove the existing menu item with ID 1
        // -------------------------------------------------
        DB::table('menu_items')->where('id', 1)->delete();

        // --------------------------------------------------------------------------------------------------
        // Insert the initial menu items into the menu_items table
        // --------------------------------------------------------------------------------------------------
        DB::table('menu_items')->insert([
            [
                // -------------------------------------------------
                // Users menu item
                // -------------------------------------------------
                'id' => 2,
                'title' => 'Users',
                'url' => '/admin/users/users',
                'menu_id' => 1,
                'parent_id' => null,
                'icon' => 'bi bi-people',
                'is_parent' => true, //Parent of Roles and Users
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // -------------------------------------------------
                // Roles menu item
                // -------------------------------------------------
                'id' => 3,
                'title' => 'Roles',
                'url' => '/admin/roles/roles',
                'menu_id' => 1,
                'parent_id' => 2, //Child of Users
                'icon' => 'bi bi-ui-checks',
                'is_parent' => false,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // -------------------------------------------------
                // Integrations menu item
                // -------------------------------------------------
                'id' => 4,
                'title' => 'Integrations',
                'url' => '/admin/integration',
                'menu_id' => 1,
                'parent_id' => null,
                'icon' => 'bi bi-arrow-down-up',
                'is_parent' => true, //Parent of Nextcloud
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // -------------------------------------------------
                // Nextcloud menu item
                // -------------------------------------------------
                'id' => 5,
                'title' => 'Nextcloud',
                'url' => '/admin/integration/nextcloud',
                'menu_id' => 1,
                'parent_id' => 4, //Child of Integrations
                'icon' => 'bi bi-cloud',
                'is_parent' => false,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                // -------------------------------------------------
                // Configurations menu item
                // -------------------------------------------------
                'id' => 6,
                'title' => 'Configurations',
                'url' => '/admin/configurations',
                'menu_id' => 1,
                'parent_id' => null,
                'icon' => 'bi bi-gear',
                'is_parent' => true, //Parent of Email Accounts and Menu
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // -------------------------------------------------
                // Email Accounts menu item
                // -------------------------------------------------
                'id' => 7,
                'title' => 'Email Accounts',
                'url' => '/admin/configurations/email/email_accounts',
                'menu_id' => 1,
                'parent_id' => 6, //Child of Configurations
                'icon' => 'bi bi-envelope-check',
                'is_parent' => false,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // -------------------------------------------------
                // Menu menu item
                // -------------------------------------------------
                'id' => 8,
                'title' => 'Menu',
                'url' => '/admin/configurations/menu',
                'menu_id' => 1,
                'parent_id' => 6, //Child of Configurations
                'icon' => 'bi bi-list',
                'is_parent' => false,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // -------------------------------------------------
                // Appearance menu item
                // -------------------------------------------------
                'id' => 9,
                'title' => 'Appearance',
                'url' => '/admin/appearance',
                'menu_id' => 1,
                'parent_id' => null,
                'icon' => 'bi bi-brush',
                'is_parent' => false,
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
        DB::table('menu_items')->whereIn('id', [2, 3, 4, 5, 6, 7, 8, 9])->delete();
    }
};
