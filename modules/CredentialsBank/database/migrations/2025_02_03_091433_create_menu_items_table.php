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
            // Add a new menu item for the Credentials Bank module
            DB::table('menu_items')->insert([
                'title' => 'Credentials Bank',
                'url' => '/credentials-bank', // Path for the Facebook module
                'menu_id' => $menuId,
                'parent_id' => null, // Directly under Admin settings
                'icon' => '', // Icon for the menu item (optional)
                'is_parent' => false, // Not a parent menu item
                'order' => 11, // Add it at the end of the existing menu
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
        // Remove the Credentials Bank menu item
        DB::table('menu_items')
            ->where('url', '/credentials-bank')
            ->delete();
    }
};
