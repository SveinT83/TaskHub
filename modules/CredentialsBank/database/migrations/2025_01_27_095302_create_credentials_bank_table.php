<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('credentials_bank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Link to TaskHub users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('encrypted_username');
            $table->text('encrypted_password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('credentials_bank');
    }
};
