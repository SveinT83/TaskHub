<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Opprett tabellen model_has_roles hvis den ikke eksisterer
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id'); // Foreign key til roles-tabellen
            $table->string('model_type'); // Typen modell (f.eks. App\Models\User)
            $table->unsignedBigInteger('model_id'); // ID-en til modellen (f.eks. User ID)

            $table->primary(['role_id', 'model_id', 'model_type']); // Kombinert primærnøkkel
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index'); // Indeks for ytelse

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade'); // Slett tilknytningen hvis rollen slettes
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_has_roles');
    }
};
