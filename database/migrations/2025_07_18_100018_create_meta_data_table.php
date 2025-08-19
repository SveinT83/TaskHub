<?php
/**
 * Migration: Create Meta Data System Table
 * 
 * This migration creates the meta_data table which serves as a flexible key-value storage
 * system for TaskHub Core. The meta data system allows any entity (users, tasks, tickets,
 * clients, etc.) to store additional custom attributes without modifying the core table
 * structure. This provides extensibility for modules and custom implementations.
 * 
 * The system supports polymorphic relationships, allowing any model type to have associated
 * metadata. Values are stored as JSON for maximum flexibility while maintaining query
 * performance through proper indexing.
 * 
 * Created: 2025-07-18
 * Purpose: Flexible metadata storage for all system entities
 * Dependencies: None (standalone table)
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
     * Execute the migration to create the meta data storage table.
     * 
     * This method creates a comprehensive metadata storage system with the following features:
     * 1. Polymorphic relationship support (parent_type + parent_id)
     * 2. Flexible key-value storage with JSON value support
     * 3. Module-specific metadata organization
     * 4. Optimized indexing for query performance
     * 5. Unique constraints to prevent duplicate meta keys
     * 
     * The table structure supports various use cases:
     * - User preferences and settings
     * - Task custom fields and attributes
     * - Module-specific configuration data
     * - Dynamic form field storage
     * - Extension and plugin data storage
     * 
     * @return void
     */
    public function up(): void
    {
        // Create the meta_data table if it doesn't exist
        if (!Schema::hasTable('meta_data')) {
            Schema::create('meta_data', function (Blueprint $table) {
                // Primary key - auto-incrementing identifier
                $table->id();
                
                // Polymorphic relationship fields for parent entity identification
                $table->string('parent_type')
                      ->comment('The class name of the parent entity (e.g., App\\Models\\User, App\\Models\\Task)');
                
                $table->bigInteger('parent_id')
                      ->comment('The ID of the parent entity record');
                
                // Metadata key-value storage
                $table->string('key')
                      ->comment('The metadata key identifier (e.g., theme_preference, custom_field_1)');
                
                $table->json('value')
                      ->nullable()
                      ->comment('The metadata value stored as JSON (supports strings, numbers, arrays, objects)');
                
                // Module organization and categorization
                $table->string('module')
                      ->nullable()
                      ->comment('Optional module identifier for organizing metadata (e.g., td-tasks, td-clients)');
                
                // Standard Laravel timestamps
                $table->timestamps();

                // Create a unique constraint to prevent duplicate meta keys for the same parent entity
                // This ensures data integrity and prevents conflicting metadata entries
                $table->unique(['parent_type', 'parent_id', 'key'], 'meta_data_parent_key_unique');
                
                // Create a composite index for optimized parent entity lookups
                // This dramatically improves performance when retrieving all metadata for a specific entity
                $table->index(['parent_type', 'parent_id'], 'meta_data_parent_lookup');
                
                // Create an additional index for module-based queries
                // This optimizes queries that filter metadata by specific modules
                $table->index(['module'], 'meta_data_module_index');
            });
        }
    }

    /**
     * Reverse the migration by dropping the meta data table.
     * 
     * This method removes the meta_data table and all associated indexes and constraints.
     * 
     * WARNING: This will permanently delete all stored metadata across the entire system.
     * This includes user preferences, custom field data, module configurations, and any
     * other metadata stored by the application. Ensure proper backup procedures are in
     * place before executing this rollback.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop the meta_data table (automatically removes all indexes and constraints)
        Schema::dropIfExists('meta_data');
    }
};
