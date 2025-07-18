<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('view_path', 255);
            $table->string('module');
            $table->string('category', 100)->default('general');
            $table->boolean('is_configurable')->default(false);
            $table->json('default_settings')->nullable();
            $table->string('icon', 100)->nullable();
            $table->text('preview_image')->nullable();
            $table->boolean('requires_auth')->default(false);
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('widgets');
    }
}