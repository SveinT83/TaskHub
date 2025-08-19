<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MIGRATION - CREATE WIDGET_POSITIONS TABLE
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This migration creates the widget_positions table in the database structure.
// This table manages the placement and configuration of widgets on different pages/routes.
// It controls where widgets appear, their order, size, and individual settings for each placement.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This method creates the widget_positions table if it doesn't already exist.
     * The table manages widget placements across different pages and positions within the application.
     * 
     * @return void
     */
    public function up(): void
    {
        // Check if the widget_positions table already exists before attempting to create it
        // This prevents errors when running migrations multiple times or in different environments
        if (!Schema::hasTable('widget_positions')) {
            
            // Create the widget_positions table to manage widget placements and configurations
            Schema::create('widget_positions', function (Blueprint $table) {
                
                // Primary key - auto-incrementing ID for each widget placement
                $table->id();

                // Route identifier - specifies which page/route this widget placement applies to
                // Examples: 'dashboard', 'admin.users.index', 'home', '*' (for all pages)
                // Allows widgets to be shown on specific pages or globally
                $table->string('route', 255);

                // Widget name - optional name for this specific widget placement
                // Kept for backward compatibility with existing implementations
                // Can be used to give custom names to widget instances
                $table->string('name')->nullable();

                // Position key - defines where on the page this widget should be displayed
                // Examples: 'header', 'sidebar-left', 'main-content', 'footer', 'sidebar-right'
                // Corresponds to widget areas defined in Blade templates
                $table->string('position_key')->default('main-content');

                // Sort order - controls the display order of widgets within the same position
                // Lower numbers appear first (0, 1, 2, etc.)
                // Allows administrators to customize widget ordering
                $table->integer('sort_order')->default(0);

                // Active status - controls whether this widget placement is currently active
                // TRUE: Widget is displayed in its assigned position
                // FALSE: Widget is hidden but configuration is preserved
                $table->boolean('is_active')->default(true);

                // Widget-specific settings - JSON object containing configuration for this placement
                // Stores customized settings that override the widget's default settings
                // Examples: {"color": "blue", "limit": 10, "show_icons": true}
                $table->json('settings')->nullable();

                // Widget size - controls the display size/width of the widget in its position
                // Examples: 'small' (25%), 'medium' (50%), 'large' (75%), 'full' (100%)
                // Allows flexible layout control for different widget types
                $table->string('size', 20)->default('medium');

                // Foreign key to the widgets table
                // Links this placement to a specific widget definition
                // When a widget is deleted, all its placements are also deleted (cascade)
                $table->foreignId('widget_id')
                    ->constrained('widgets')
                    ->onDelete('cascade');

                // Laravel timestamps - created_at and updated_at
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * This method drops the widget_positions table if it exists.
     * Warning: This will delete all widget placements and configurations permanently.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop the widget_positions table and all widget placement data
        Schema::dropIfExists('widget_positions');
    }
};