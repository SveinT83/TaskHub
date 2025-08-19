<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MIGRATION - CREATE MODEL_HAS_ROLES TABLE
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This migration creates the model_has_roles table for the Spatie Laravel Permission package.
// This is a pivot table that establishes many-to-many relationships between roles and models (typically users).
// It allows any Eloquent model to be assigned multiple roles, enabling flexible role-based access control.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This method creates the model_has_roles table if it doesn't already exist.
     * This table is required by Spatie Permission package to track which roles are assigned to which models.
     * 
     * @return void
     */
    public function up(): void
    {
        // Check if the model_has_roles table already exists before attempting to create it
        // This prevents errors when running migrations multiple times or in different environments
        if (!Schema::hasTable('model_has_roles')) {
            
            // Create the model_has_roles pivot table for Spatie Permission package
            Schema::create('model_has_roles', function (Blueprint $table) {
                
                // Foreign key to the roles table
                // References the ID of the role being assigned to the model
                // When a role is deleted, all assignments to models are also deleted (cascade)
                $table->unsignedBigInteger('role_id');

                // Model type - stores the fully qualified class name of the model
                // Examples: 'App\Models\User', 'App\Models\Admin', 'App\Models\Customer'
                // This allows any Eloquent model to have roles assigned to it
                $table->string('model_type');

                // Model ID - stores the primary key of the specific model instance
                // Combined with model_type, this uniquely identifies which model instance has the role
                // Example: If model_type is 'App\Models\User' and model_id is 5, then User with ID 5 has this role
                $table->unsignedBigInteger('model_id');

                // Composite primary key - prevents duplicate role assignments to the same model
                // A model can only have each specific role assigned once
                // The combination of role_id + model_id + model_type must be unique
                $table->primary(['role_id', 'model_id', 'model_type']);

                // Performance index for efficient lookups by model
                // When checking what roles a specific model has, this index speeds up the query
                // Named index to avoid auto-generated name conflicts
                $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

                // Foreign key constraint to ensure data integrity
                // Links role_id to the roles table and cascades deletes
                // When a role is deleted, all assignments of that role are automatically removed
                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * This method drops the model_has_roles table if it exists.
     * Warning: This will delete all role assignments permanently and break Spatie Permission functionality.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop the model_has_roles table and all role assignments
        // This will remove all role assignments from all models in the system
        Schema::dropIfExists('model_has_roles');
    }
};