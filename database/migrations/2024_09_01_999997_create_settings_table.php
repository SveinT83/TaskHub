<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Unik nøkkel for hver innstilling
            $table->text('description')->nullable(); // Valgfri beskrivelse av innstillingen
            $table->string('value')->nullable(); // Verdi for innstillingen (f.eks. 1 eller 0)
            $table->string('type')->default('string'); // Type (f.eks. boolean, string, etc.)
            $table->unsignedBigInteger('updated_by')->nullable(); // Hvem som oppdaterte innstillingen
            $table->timestamps();

            // Fremmednøkkel for updated_by (koblet til users-tabellen)
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
