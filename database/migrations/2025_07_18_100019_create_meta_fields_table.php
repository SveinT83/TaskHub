<?php
/**
 * Migration: Create Meta Fields Definition Table
 * 
 * This migration creates the meta_fields table which serves as the schema definition
 * system for custom fields in TaskHub Core. While the meta_data table stores the actual
 * values, this table defines the structure, validation rules, and display properties
 * of custom fields that can be attached to any entity in the system.
 * 
 * This table enables dynamic form generation, field validation, and consistent UI
 * rendering for custom fields across different modules. It supports various field
 * types including text, numbers, dates, selections, and complex data structures.
 * 
 * Created: 2025-07-18
 * Purpose: Custom field schema definitions and validation rules
 * Dependencies: Works in conjunction with meta_data table
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
     * Execute the migration to create the meta fields definition table.
     * 
     * This method creates a comprehensive field definition system with the following features:
     * 1. Unique field identification and labeling
     * 2. Field type specification (text, number, date, select, etc.)
     * 3. Validation rule definitions for data integrity
     * 4. Default value configuration for new records
     * 5. Dynamic option sets for selection fields
     * 6. Module-based field organization
     * 
     * The table structure supports various field types and configurations:
     * - Simple text and numeric inputs
     * - Date and datetime pickers
     * - Single and multi-select dropdowns
     * - Checkbox and radio button groups
     * - File upload fields
     * - Complex JSON data structures
     * 
     * @return void
     */
    public function up(): void
    {
        // Create the meta_fields table if it doesn't exist
        if (!Schema::hasTable('meta_fields')) {
            Schema::create('meta_fields', function (Blueprint $table) {
                // Primary key - auto-incrementing identifier
                $table->id();
                
                // Field identification and uniqueness
                $table->string('key')
                      ->unique()
                      ->comment('Unique identifier for the field (e.g., custom_priority, client_category)');
                
                // Display and user interface properties
                $table->string('label')
                      ->comment('Human-readable label displayed in forms and UI (e.g., Priority Level, Client Category)');
                
                $table->text('description')
                      ->nullable()
                      ->comment('Optional detailed description or help text for the field');
                
                // Field type and behavior configuration
                $table->string('type')
                      ->comment('Field type identifier (text, number, date, select, checkbox, file, json, etc.)');
                
                // Validation and data integrity
                $table->string('rules')
                      ->nullable()
                      ->comment('Laravel validation rules as string (e.g., required|string|max:255, numeric|min:0)');
                
                // Default values and configuration
                $table->json('default_value')
                      ->nullable()
                      ->comment('Default value for new records (stored as JSON for flexibility)');
                
                $table->json('options')
                      ->nullable()
                      ->comment('Field-specific options: select choices, file types, display settings, etc.');
                
                // Module organization and categorization
                $table->string('module')
                      ->comment('Module identifier that owns this field (e.g., td-tasks, td-clients, td-tickets)');
                
                // Standard Laravel timestamps
                $table->timestamps();
                
                // Create indexes for optimized queries
                $table->index(['module'], 'meta_fields_module_index');
                $table->index(['type'], 'meta_fields_type_index');
            });
        }
    }

    /**
     * Reverse the migration by dropping the meta fields table.
     * 
     * This method removes the meta_fields table and all associated indexes and constraints.
     * 
     * WARNING: This will permanently delete all custom field definitions across the entire
     * system. This means all custom fields will become undefined, although their data
     * may still exist in the meta_data table. The system will not be able to properly
     * validate, display, or manage any custom fields until they are redefined.
     * 
     * Consider the impact on:
     * - Dynamic form generation
     * - Field validation rules
     * - UI display properties
     * - Module-specific custom fields
     * 
     * Ensure proper backup procedures are in place before executing this rollback.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop the meta_fields table (automatically removes all indexes and constraints)
        Schema::dropIfExists('meta_fields');
    }
};
