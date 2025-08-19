<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MIGRATION - CREATE MENU_ITEMS TABLE
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This migration creates the menu_items table in the database structure.
// It only handles the table creation - no data insertion.
// Data insertion is handled by a separate migration file (2024_09_01_100003_insert_menu_items.php).
// ---------------------------------------------------------------------------------------------------------------------------------------------------

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     * 
     * This method creates the menu_items table if it doesn't already exist.
     * The table will store individual menu items that belong to menus.
     * 
     * @return void
     */
    public function up(): void
    {
        // Check if the menu_items table already exists before attempting to create it
        // This prevents errors when running migrations multiple times
        if (!Schema::hasTable('menu_items')) {
            
            // Create the menu_items table with all required columns
            Schema::create('menu_items', function (Blueprint $table) {
                
                // Primary key - auto-incrementing ID
                $table->id();

                // Title of the menu item - this will be displayed in the UI
                // Should be a translation key for multi-language support
                $table->string('title');

                // URL that this menu item points to
                // For admin items, should start with /admin/[module]/[page]
                $table->string('url');

                // Foreign key to the menus table
                // Defines which top-level menu this item belongs to
                // Admin menu should always have menu_id = 1
                $table->unsignedBigInteger('menu_id')->nullable();

                // Self-referencing foreign key for hierarchical menu structure
                // If this item is a child of another menu item, this contains the parent's ID
                // NULL for top-level menu items within a menu
                $table->unsignedBigInteger('parent_id')->nullable();

                // CSS class for the menu item icon
                // Default: Bootstrap Icons (e.g., "bi bi-home")
                // Can be adapted for other icon libraries
                $table->string('icon')->nullable();

                // Boolean flag indicating if this item can have children
                // TRUE: This item can contain sub-menu items
                // FALSE: This is a leaf node in the menu tree
                $table->boolean('is_parent')->default(false);

                // Sort order within the same menu level
                // Lower numbers appear first in the menu
                // Allows for custom ordering of menu items
                $table->integer('order')->default(0);

                // Module ownership tracking
                // NULL: Core system menu items
                // String: Module slug that created this menu item
                // Used for cleanup when modules are disabled/uninstalled
                $table->string('module')->nullable()->index();

                // Laravel timestamps - created_at and updated_at
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * This method drops the menu_items table if it exists.
     * Warning: This will delete all menu item data permanently.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop the menu_items table and all its data
        Schema::dropIfExists('menu_items');
    }
};