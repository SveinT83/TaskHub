<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_positions', function (Blueprint $table) {
            $table->id();
            $table->string('route', 255);
            $table->string('name')->nullable(); // For bakoverkompatibilitet
            $table->string('position_key')->default('main-content');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->string('size', 20)->default('medium');
            $table->foreignId('widget_id')->constrained('widgets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_positions');
    }
}