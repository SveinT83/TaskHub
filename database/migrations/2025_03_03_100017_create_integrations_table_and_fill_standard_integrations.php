<?php
/**
 * Migration: Create Integrations System Tables and Default Data
 * 
 * This migration establishes the complete integration management system for TaskHub Core,
 * creating both the main integrations table and the related credentials storage table.
 * The integration system enables TaskHub to connect with external services like cloud
 * storage providers (Nextcloud) and accounting systems (Tripletex).
 * 
 * Created: 2025-03-03
 * Purpose: Third-party service integration management
 * Dependencies: None (standalone tables)
 * 
 * @package TaskHub\Database\Migrations
 * @version 1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Execute the migration to create integration tables and seed default data.
     * 
     * This method performs a comprehensive setup of the integration system:
     * 1. Creates the main integrations table for storing available integrations
     * 2. Seeds default integrations (Nextcloud, Tripletex) with initial configuration
     * 3. Creates the integration_credentials table for storing API credentials
     * 
     * The system supports various authentication methods including API keys,
     * OAuth2 (client credentials), and basic authentication.
     * 
     * @return void
     */
    public function up(): void
    {
        // Create the main integrations table if it doesn't exist
        if (!Schema::hasTable('integrations')) {
            Schema::create('integrations', function (Blueprint $table) {
                // Primary key - auto-incrementing identifier
                $table->id();
                
                // Integration identification
                $table->string('name')
                      ->comment('Unique name identifier for the integration (e.g., nextcloud, tripletex)');
                
                // Status and display configuration
                $table->boolean('active')
                      ->default(0)
                      ->comment('Whether this integration is currently active and available for use');
                
                $table->string('icon')
                      ->nullable()
                      ->comment('CSS class or icon identifier for UI display (e.g., bi-cloud, bi-calculator)');
                
                // Standard Laravel timestamps
                $table->timestamps();
            });

            // Insert default integrations with proper configuration
            DB::table('integrations')->insert([
                [
                    'name' => 'nextcloud',
                    'active' => 0,
                    'icon' => 'bi-cloud',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'tripletex',
                    'active' => 0,
                    'icon' => 'bi-calculator',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]);
        }

        // Create the integration credentials storage table if it doesn't exist
        if (!Schema::hasTable('integration_credentials')) {
            Schema::create('integration_credentials', function (Blueprint $table) {
                // Primary key - auto-incrementing identifier
                $table->id();
                
                // Foreign key relationship to integrations table
                $table->foreignId('integration_id')
                      ->constrained('integrations')
                      ->onDelete('cascade')
                      ->comment('Reference to the parent integration record');
                
                // API credential fields (various authentication methods supported)
                $table->string('api')
                      ->nullable()
                      ->comment('API key or token for direct API authentication');
                
                $table->string('clientid')
                      ->nullable()
                      ->comment('OAuth2 client ID for application identification');
                
                $table->string('clientsecret')
                      ->nullable()
                      ->comment('OAuth2 client secret (encrypted/hashed in production)');
                
                $table->string('redirecturi')
                      ->nullable()
                      ->comment('OAuth2 redirect URI for authorization callback');
                
                $table->string('baseurl')
                      ->nullable()
                      ->comment('Base URL for the integration API endpoint');
                
                $table->string('username')
                      ->nullable()
                      ->comment('Username for basic authentication or user-specific access');
                
                $table->string('password')
                      ->nullable()
                      ->comment('Password for basic authentication (encrypted/hashed in production)');
                
                $table->string('expires_in')
                      ->nullable()
                      ->comment('Token expiration time or validity period information');
                
                // Standard Laravel timestamps
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migration by dropping integration-related tables.
     * 
     * This method safely removes all integration system tables in the correct order
     * to prevent foreign key constraint violations. The integration_credentials table
     * is dropped first since it has foreign key dependencies on the integrations table.
     * 
     * Note: This will permanently remove all integration configurations and credentials.
     * Ensure proper backup procedures are in place before running this rollback.
     * 
     * @return void
     */
    public function down(): void
    {
        // Drop dependent table first to avoid foreign key constraint violations
        Schema::dropIfExists('integration_credentials');
        
        // Drop the main integrations table
        Schema::dropIfExists('integrations');
    }
};