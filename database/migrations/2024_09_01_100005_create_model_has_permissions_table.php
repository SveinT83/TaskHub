<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sjekk om tabellen allerede eksisterer
        if (!Schema::hasTable('model_has_permissions')) {
            // Opprett tabellen "model_has_permissions"
            Schema::create('model_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id'); // ID for tillatelsen
                $table->string('model_type'); // Modellens type, f.eks. "App\User"
                $table->unsignedBigInteger('model_id'); // Modellens ID

                // Primærnøkkel sammensatt av permission_id, model_type, og model_id
                $table->primary(['permission_id', 'model_id', 'model_type']);

                // Fremmednøkkel som peker til "permissions" tabellen
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

                // Indekser for å forbedre søkeprosesser
                $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Slett tabellen hvis den eksisterer
        Schema::dropIfExists('model_has_permissions');
    }
};