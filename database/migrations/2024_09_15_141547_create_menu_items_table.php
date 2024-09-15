<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade'); // Tilknytning til meny
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade'); // Referanse til foreldreelement
            $table->string('title'); // Navn på menyelementet
            $table->string('url'); // Lenke menyelementet peker til
            $table->string('permission')->nullable(); // Valgfri tillatelse som kreves for å vise elementet
            $table->integer('order')->default(0); // Sorteringsrekkefølge
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
