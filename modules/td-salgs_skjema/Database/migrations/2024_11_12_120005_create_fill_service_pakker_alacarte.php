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
        if (!Schema::hasTable('service_pakker_alacarte')) {
            Schema::create('service_pakker_alacarte', function (Blueprint $table) {
                $table->id(); // Primærnøkkel
                $table->unsignedBigInteger('service_pakke_id'); // Fremmednøkkel til service_pakker
                $table->unsignedBigInteger('alacarte_id'); // Fremmednøkkel til alacarte
                $table->timestamps(); // Opprettet/oppdatert tid

                // Fremmednøkkel-relasjoner
                $table->foreign('service_pakke_id')->references('id')->on('service_pakker')->onDelete('cascade');
                $table->foreign('alacarte_id')->references('id')->on('alacarte')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_pakker_alacarte');
    }
};
