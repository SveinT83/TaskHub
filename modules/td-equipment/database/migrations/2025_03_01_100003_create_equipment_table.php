<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('equipment')) {
            Schema::create('equipment', function (Blueprint $table) {
                $table->id(); // Primærnøkkel
                $table->string('name'); // Navn på utstyret
                $table->unsignedBigInteger('category_id'); // Kategori (FK til categories)
                $table->string('serial_number')->unique(); // Serienummer
                $table->enum('status', ['active', 'inactive', 'needs_certification'])->default('active'); // Status
                $table->tinyInteger('certification_month')->nullable(); // Måned for sertifisering (1-12)
                $table->text('description')->nullable(); // Beskrivelse
                $table->timestamps(); // created_at & updated_at

                // Definer utenlandsnøkkel
                $table->foreign('category_id')->references('id')->on('equipment_categories')->onDelete('cascade');
            });

            // Indekser for raskere søk
            Schema::table('equipment', function (Blueprint $table) {
                $table->index('serial_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
