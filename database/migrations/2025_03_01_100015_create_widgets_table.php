<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MIGRATION - CREATE WIDGETS TABLE
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This migration creates the widgets table in the database structure.
// Widgets are reusable UI components that can be displayed on dashboards, sidebars, or other areas.
// The table stores widget definitions, settings, and permissions for the TaskHub widget system.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This method creates the widgets table if it doesn't already exist.
     * The table will store widget definitions and their configuration options.
     * 
     * @return void
     */
    public function up(): void
    {
        // Check if the widgets table already exists before attempting to create it
        // This prevents errors when running migrations multiple times or in different environments
        if (!Schema::hasTable('widgets')) {
            
            // Create the widgets table with all required columns for widget management
            Schema::create('widgets', function (Blueprint $table) {
                
                // Primary key - auto-incrementing ID
                $table->id();

                // Widget name - human readable name for the widget
                // This will be displayed in the widget selector and admin interface
                $table->string('name');

                // Widget description - optional detailed description of what the widget does
                // Helps administrators understand the widget's purpose and functionality
                $table->text('description')->nullable();

                // View path - relative path to the widget's Blade template or Livewire component
                // Example: 'widgets.dashboard.stats' or 'livewire.widgets.user-count'
                $table->string('view_path', 255);

                // Module ownership - which module/package registered this widget
                // Used for cleanup when modules are disabled or uninstalled
                // NULL for core system widgets, module slug for third-party widgets
                $table->string('module');

                // Widget category - groups widgets for better organization in the UI
                // Examples: 'dashboard', 'sidebar', 'analytics', 'reports', etc.
                $table->string('category', 100)->default('general');

                // Configuration flag - indicates if this widget has configurable settings
                // TRUE: Widget has settings that can be customized by users
                // FALSE: Widget has no configurable options (static widget)
                $table->boolean('is_configurable')->default(false);

                // Default settings - JSON object containing default configuration values
                // Only used when is_configurable is TRUE
                // Stores default values for widget-specific settings like colors, limits, etc.
                $table->json('default_settings')->nullable();

                // Widget icon - CSS class or icon identifier for UI display
                // Examples: 'bi bi-graph-up', 'fas fa-chart-bar', 'lucide-users'
                // Used in widget selectors and admin interfaces
                $table->string('icon', 100)->nullable();

                // Preview image - path or URL to a preview image of the widget
                // Helps users visualize what the widget looks like before adding it
                // Can be a relative path to storage or an external URL
                $table->text('preview_image')->nullable();

                // Authentication requirement - indicates if widget requires user authentication
                // TRUE: Only show to authenticated users
                // FALSE: Can be shown to anonymous visitors
                $table->boolean('requires_auth')->default(false);

                // Permission requirements - JSON array of required permissions to view this widget
                // Examples: ['users.view'], ['reports.access', 'analytics.view']
                // NULL means no specific permissions required (beyond auth if requires_auth is true)
                $table->json('permissions')->nullable();

                // Active status - controls whether this widget is available for use
                // TRUE: Widget is available in widget selectors
                // FALSE: Widget is hidden and cannot be added to layouts
                $table->boolean('is_active')->default(true);

                // Laravel timestamps - created_at and updated_at
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * This method drops the widgets table if it exists.
     * Warning: This will delete all widget definitions and their data permanently.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop the widgets table and all its data
        Schema::dropIfExists('widgets');
    }
};