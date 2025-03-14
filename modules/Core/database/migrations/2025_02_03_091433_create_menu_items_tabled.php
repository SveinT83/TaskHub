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
            // Define menu items for each module
            $menuItems = [
                [
                    'title' => 'Project Management',
                    'url' => '/projects',
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'icon' => 'fas fa-folder', // Example icon
                    'is_parent' => false,
                    'order' => 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Inventory',
                    'url' => '/inventory',
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'icon' => 'fas fa-box', // Example icon
                    'is_parent' => false,
                    'order' => 11,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Audit Logs',
                    'url' => '/auditlogs',
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'icon' => 'fas fa-history', // Example icon
                    'is_parent' => false,
                    'order' => 12,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Invoicing',
                    'url' => '/invoices',
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'icon' => 'fas fa-file-invoice', // Example icon
                    'is_parent' => false,
                    'order' => 13,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            // Insert menu items
            DB::table('menu_items')->insert($menuItems);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the system menu items
        DB::table('menu_items')
            ->whereIn('url', ['/projects', '/inventory', '/auditlogs', '/invoicing'])
            ->delete();
    }
};