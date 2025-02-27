<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAdminCategoriesMenuItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the menu item already exists
        $existingItem = DB::table('menu_items')
            ->where('menu_id', 1) // Admin menu always has ID 1
            ->where('title', 'Categories')
            ->first();

        // If it does not exist, insert the new menu item
        if (!$existingItem) {
            DB::table('menu_items')->insert([
                'menu_id' => 1, // Admin menu ID
                'parent_id' => null, // Main menu, not a sub-item
                'title' => 'Categories',
                'url' => '/admin/cat',
                'icon' => 'bi bi-list', // You can change the icon if needed
                'permission' => null, // Set permissions if needed
                'order' => 0, // Position in the menu, adjust if needed
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the menu item on rollback
        DB::table('menu_items')
            ->where('menu_id', 1)
            ->where('title', 'Categories')
            ->delete();
    }
}
