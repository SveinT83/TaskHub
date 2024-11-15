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
        // Check if the table already exists
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->bigInteger('parent_id')->unsigned()->nullable();
                $table->integer('order')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->timestamps();
                $table->string('icon')->nullable();
                $table->string('color', 7)->nullable();
                $table->boolean('disabled')->default(false);
                $table->string('module')->nullable();
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
        Schema::dropIfExists('categories');
    }
};
