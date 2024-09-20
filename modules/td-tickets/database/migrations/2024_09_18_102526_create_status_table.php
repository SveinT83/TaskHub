<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusTable extends Migration
{
    public function up()
    {
        Schema::create('tickets_status', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_default')->default(false);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->timestamps();

            // Indekser
            $table->index('is_default');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets_status');
    }
}
