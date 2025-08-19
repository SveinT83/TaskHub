<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MIGRATION - CREATE MENUS TABLE
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This migration creates the menus table in the database structure.
// It only handles the table creation - no data insertion.
// Data insertion is handled by a separate migration file.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This method creates the menus table if it doesn't already exist.
     * The table will store menu definitions that can be used throughout the application.
     * 
     * @return void
     */
    public function up(): void
    {
        // Check if the menus table already exists before attempting to create it
        // This prevents errors when running migrations multiple times
        if (!Schema::hasTable('menus')) {
            
            // Create the menus table with all required columns
            Schema::create('menus', function (Blueprint $table) {
                // Primary key - auto-incrementing ID
                $table->id();
                
                // Menu name - human readable name for the menu
                $table->string('name');
                
                // Menu slug - URL-friendly identifier, indexed for fast lookups
                $table->string('slug')->index(); 
                
                // Optional URL - can be null for menus that don't have direct URLs
                $table->string('url', 30)->nullable(); 
                
                // Optional description - provides context about what this menu contains
                $table->string('description')->nullable(); 
                
                // Laravel timestamps - created_at and updated_at
                $table->timestamps(); 
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * This method drops the menus table if it exists.
     * Warning: This will delete all menu data permanently.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop the menus table and all its data
        Schema::dropIfExists('menus');
    }
};