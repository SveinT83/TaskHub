<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MIGRATION - INSERT DEFAULT MENU AND MENU ITEMS
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This migration inserts the default admin menu and all default menu items for the admin panel.
// It requires both the menus and menu_items tables to exist (created by previous migrations).
// This creates the complete menu structure for system administration in a single operation.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This method first inserts the default admin menu, then all default admin menu items.
     * It ensures proper menu hierarchy and uses specific IDs for system dependencies.
     * 
     * @return void
     */
    public function up(): void
    {
        // Verify that required tables exist before attempting to insert data
        if (!Schema::hasTable('menus')) {
            throw new Exception('Menus table does not exist. Please run the create_menus_table migration first.');
        }
        
        if (!Schema::hasTable('menu_items')) {
            throw new Exception('Menu_items table does not exist. Please run the create_menu_items_table migration first.');
        }

        // STEP 1: Insert the default admin menu if it doesn't exist
        $adminMenuExists = DB::table('menus')
            ->where('id', 1)
            ->orWhere('slug', 'adminsettings')
            ->exists();
        
        if (!$adminMenuExists) {
            // Reset the AUTO_INCREMENT counter to 1 to ensure the admin menu gets ID 1
            // This is critical because other parts of the system expect the admin menu to have ID 1
            DB::statement('ALTER TABLE menus AUTO_INCREMENT = 1');
            
            // Insert the default admin settings menu
            // This menu serves as the parent for all administrative configuration options
            DB::table('menus')->insert([
                'id' => 1,  // Explicit ID 1 - required by system dependencies
                'name' => 'admin_settings',  // Internal name for the menu
                'slug' => 'adminsettings',  // URL-friendly identifier
                'url' => null,  // No direct URL - this is a parent menu container
                'description' => 'Settings and configuration options for system administrators',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // STEP 2: Insert all default menu items
        // Check if menu items already exist to prevent duplicates
        $menuItemCount = DB::table('menu_items')->count();
        
        // Only insert if no menu items exist yet
        if ($menuItemCount === 0) {
            
            // Clean up any existing items with our target IDs to prevent conflicts
            DB::table('menu_items')->whereIn('id', [2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->delete();
            
            // Reset AUTO_INCREMENT to ensure proper ID assignment
            DB::statement('ALTER TABLE menu_items AUTO_INCREMENT = 2');
            
            // Insert all default admin menu items
            DB::table('menu_items')->insert([
                [
                    // USER MANAGEMENT - Parent menu for user-related functions
                    'id' => 2,
                    'title' => 'users',                    // Translation key for "Users"
                    'url' => '/admin/users/users',         // URL to user management page
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => null,                   // Top-level item
                    'icon' => 'bi bi-people',              // Bootstrap icon for users
                    'is_parent' => true,                   // Has child items (roles)
                    'order' => 2,                          // Display order
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // ROLES - Child of Users menu
                    'id' => 3,
                    'title' => 'roles',                    // Translation key for "Roles"
                    'url' => '/admin/roles/roles',         // URL to roles management
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => 2,                      // Child of Users menu
                    'icon' => 'bi bi-ui-checks',           // Bootstrap icon for checkboxes
                    'is_parent' => false,                  // Leaf node
                    'order' => 1,                          // First child under Users
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // INTEGRATIONS - Parent menu for external service integrations
                    'id' => 4,
                    'title' => 'integrations',             // Translation key for "Integrations"
                    'url' => '/admin/integration',         // URL to integrations page
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => null,                   // Top-level item
                    'icon' => 'bi bi-arrow-down-up',       // Bootstrap icon for data exchange
                    'is_parent' => true,                   // Can have children (various integrations)
                    'order' => 3,                          // Display order
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // CONFIGURATIONS - Parent menu for system configuration options
                    'id' => 6,
                    'title' => 'configurations',           // Translation key for "Configurations"
                    'url' => '/admin/configurations',      // URL to configurations overview
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => null,                   // Top-level item
                    'icon' => 'bi bi-gear',                // Bootstrap icon for settings
                    'is_parent' => true,                   // Has many children
                    'order' => 1,                          // First in menu (most important)
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // EMAIL ACCOUNTS - Child of Configurations
                    'id' => 7,
                    'title' => 'email_accounts',           // Translation key for "Email Accounts"
                    'url' => '/admin/configurations/email/email_accounts', // URL to email configuration
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => 6,                      // Child of Configurations
                    'icon' => 'bi bi-envelope-check',      // Bootstrap icon for email
                    'is_parent' => false,                  // Leaf node
                    'order' => 1,                          // First under Configurations
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // MENU MANAGEMENT - Child of Configurations
                    'id' => 8,
                    'title' => 'menu',                     // Translation key for "Menu"
                    'url' => '/admin/configurations/menu', // URL to menu management
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => 6,                      // Child of Configurations
                    'icon' => 'bi bi-list',                // Bootstrap icon for lists
                    'is_parent' => false,                  // Leaf node
                    'order' => 2,                          // Second under Configurations
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // APPEARANCE - Top-level appearance settings
                    'id' => 9,
                    'title' => 'appearance',               // Translation key for "Appearance"
                    'url' => '/admin/appearance',          // URL to appearance settings
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => null,                   // Top-level item
                    'icon' => 'bi bi-brush',               // Bootstrap icon for design
                    'is_parent' => false,                  // Leaf node (for now)
                    'order' => 4,                          // Display order
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // WIDGET CMS - Widget management system
                    'id' => 10,
                    'title' => 'widget',                   // Translation key for "Widgets"
                    'url' => '/admin/configurations/widgets', // URL to widget management
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => null,                   // Top-level item
                    'icon' => 'bi bi-grid-3x3-gap',        // Bootstrap icon for grid/widgets
                    'is_parent' => false,                  // Leaf node
                    'order' => 5,                          // Display order
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // LANGUAGE MANAGEMENT - Child of Configurations
                    'id' => 11,
                    'title' => 'langue',                   // Translation key for "Language"
                    'url' => '/admin/configurations/langue', // URL to language settings
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => 6,                      // Child of Configurations
                    'icon' => 'bi bi-translate',           // Bootstrap icon for translation
                    'is_parent' => false,                  // Leaf node
                    'order' => 4,                          // Fourth under Configurations
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // CUSTOM FIELDS (META DATA) - Child of Configurations
                    'id' => 12,
                    'title' => 'customfields',             // Translation key for "Custom Fields"
                    'url' => '/admin/configurations/meta/fields', // URL to meta data management
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => 6,                      // Child of Configurations
                    'icon' => 'bi bi-tags',                // Bootstrap icon for tags/fields
                    'is_parent' => false,                  // Leaf node
                    'order' => 5,                          // Fifth under Configurations
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // CURRENCY MANAGEMENT - Child of Configurations
                    'id' => 15,
                    'title' => 'currency',                 // Translation key for "Currency"
                    'url' => '/admin/configurations/currency', // URL to currency management
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => 6,                      // Child of Configurations
                    'icon' => 'bi bi-cash-coin',           // Bootstrap icon for currency
                    'is_parent' => false,                  // Leaf node
                    'order' => 3,                          // Third under Configurations
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // MODULES - Top-level module management
                    'id' => 13,
                    'title' => 'modules',                  // Translation key for "Modules"
                    'url' => '/admin/modules',             // URL to module management
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => null,                   // Top-level item
                    'icon' => 'bi bi-puzzle',              // Bootstrap icon for modules/plugins
                    'is_parent' => false,                  // Leaf node
                    'order' => 6,                          // Display order
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    // STORE - Child of Modules
                    'id' => 14,
                    'title' => 'store',                    // Translation key for "Store"
                    'url' => '/admin/modules/store',       // URL to module store
                    'menu_id' => 1,                        // Belongs to admin menu
                    'parent_id' => 13,                     // Child of Modules
                    'icon' => 'bi bi-shop',                // Bootstrap icon for shop
                    'is_parent' => false,                  // Leaf node
                    'order' => 1,                          // First under Modules
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     * 
     * This method removes both the menu items and the admin menu that were inserted by this migration.
     * It removes menu items first to avoid foreign key constraints.
     * 
     * @return void
     */
    public function down(): void
    {
        // First remove all menu items created by this migration
        DB::table('menu_items')->whereIn('id', [2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->delete();
        
        // Then remove the admin menu that this migration created
        // We identify it by both ID and slug for safety
        DB::table('menus')
            ->where('id', 1)
            ->where('slug', 'adminsettings')
            ->delete();
    }
};