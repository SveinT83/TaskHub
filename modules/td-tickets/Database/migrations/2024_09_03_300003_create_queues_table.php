<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets_queues', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('description')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_queues');
    }
};
