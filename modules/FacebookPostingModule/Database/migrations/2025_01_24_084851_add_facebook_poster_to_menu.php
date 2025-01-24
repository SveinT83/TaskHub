<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fetch the Admin settings menu ID
        $menuId = DB::table('menus')->where('slug', 'adminsettings')->value('id');

        if ($menuId) {
            // Add a new menu item for the Facebook Poster module
            DB::table('menu_items')->insert([
                'title' => 'Facebook Module',
                'url' => '/facebook-poster/post-form', // Path for the Facebook module
                'menu_id' => $menuId,
                'parent_id' => null, // Directly under Admin settings
                'icon' => 'bi bi-facebook', // Icon for the menu item (optional)
                'is_parent' => false, // Not a parent menu item
                'order' => 10, // Add it at the end of the existing menu
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
        // Remove the Facebook Poster menu item
        DB::table('menu_items')
            ->where('url', '/facebook-poster/post-form')
            ->delete();
    }
};
