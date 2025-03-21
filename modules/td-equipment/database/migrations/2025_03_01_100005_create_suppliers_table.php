<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vendors')) {
            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // Navn må være unikt og påbudt
                $table->string('url')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();

                // Besøksadresse
                $table->string('visit_address_01')->nullable();
                $table->string('visit_address_02')->nullable();
                $table->string('visit_zip', 10)->nullable();
                $table->string('visit_city')->nullable();
                $table->string('visit_country', 2)->nullable(); // 2-bokstavs landkode (ISO)

                // Postadresse
                $table->string('post_address_01')->nullable();
                $table->string('post_address_02')->nullable();
                $table->string('post_zip', 10)->nullable();
                $table->string('post_city')->nullable();
                $table->string('post_country', 2)->nullable();

                // Kategori & momsnummer
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('vat_id')->nullable(); // F.eks. MVA-nummer

                // Timestamps
                $table->timestamps();

                // Fremmednøkkel (hvis `categories`-tabellen eksisterer)
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
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
        Schema::dropIfExists('vendors');
    }
};
