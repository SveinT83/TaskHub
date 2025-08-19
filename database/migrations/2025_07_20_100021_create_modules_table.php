<?php
/**
 * Migration: Create Modules Registry Table
 * 
 * This migration creates the modules table which serves as the central registry for
 * all TaskHub Core modules. The module system enables extensible functionality through
 * a plugin-like architecture where individual modules can be installed, enabled,
 * disabled, and managed independently.
 * 
 * Modules in TaskHub Core include core functionality like td-tasks, td-clients,
 * td-tickets, td-equipment, and third-party extensions. This table tracks their
 * installation status, version information, and provides the foundation for
 * module-based permission systems and feature toggles.
 * 
 * Created: 2025-07-20
 * Purpose: Module registration, version tracking, and state management
 * Dependencies: None (foundational table)
 * 
 * @package TaskHub\Database\Migrations
 * @version 1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Execute the migration to create the modules registry table.
     * 
     * This method creates a comprehensive module management system with the following features:
     * 1. Unique module identification through slug system
     * 2. Human-readable naming and description
     * 3. Version tracking for updates and compatibility
     * 4. File system path mapping for module loading
     * 5. Enable/disable toggle for runtime control
     * 6. Installation timestamp tracking
     * 
     * The table structure supports various module types:
     * - Core TaskHub modules (td-tasks, td-clients, td-tickets)
     * - Third-party extensions and plugins
     * - Custom organizational modules
     * - Integration modules (API connectors, webhooks)
     * - Theme and UI enhancement modules
     * 
     * @return void
     */
    public function up(): void
    {
        // Create the modules table if it doesn't exist
        if (!Schema::hasTable('modules')) {
            Schema::create('modules', function (Blueprint $table) {
                // Primary key - auto-incrementing identifier
                $table->id();
                
                // Module identification and uniqueness
                $table->string('slug')
                      ->unique()
                      ->comment('Unique module identifier (e.g., td-tasks, td-clients, custom-billing)');
                
                // Display and descriptive information
                $table->string('name')
                      ->comment('Human-readable module name displayed in admin interface');
                
                $table->string('version')
                      ->nullable()
                      ->comment('Current module version (semantic versioning: 1.0.0, 2.1.3-beta)');
                
                $table->text('description')
                      ->nullable()
                      ->comment('Detailed description of module functionality and features');
                
                // File system and loading configuration
                $table->string('path')
                      ->comment('Relative path to module directory from modules root (e.g., td-tasks, custom/billing)');
                
                // Runtime state management
                $table->boolean('enabled')
                      ->default(true)
                      ->comment('Whether the module is currently active and available for use');
                
                // Standard Laravel timestamps
                $table->timestamps();
                
                // Create indexes for optimized queries
                $table->index(['enabled'], 'modules_enabled_index');
                $table->index(['slug'], 'modules_slug_index');
            });
        }
    }

    /**
     * Reverse the migration by dropping the modules registry table.
     * 
     * This method removes the modules table and all associated indexes and constraints.
     * 
     * CRITICAL WARNING: This will permanently delete the entire module registry system.
     * This action will have severe consequences for the TaskHub Core system:
     * 
     * Impact on system functionality:
     * - Module loading and initialization will fail
     * - Permission systems tied to modules will break
     * - Module-specific routes and features will become unavailable
     * - Admin interface module management will cease to function
     * - Third-party extensions will not be recognized
     * 
     * Data integrity concerns:
     * - Module-specific data may become orphaned
     * - Meta data and custom fields linked to modules may lose context
     * - Integration configurations may become invalid
     * 
     * This rollback should only be performed in development environments or during
     * complete system reinstallation. Ensure comprehensive backup procedures are in
     * place and consider the impact on dependent systems and users.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop the modules table (automatically removes all indexes and constraints)
        Schema::dropIfExists('modules');
    }
};
