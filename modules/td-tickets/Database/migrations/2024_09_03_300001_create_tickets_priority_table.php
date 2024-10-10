<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ticket_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Navn på prioriteten, f.eks. 'Low', 'Normal', 'High'
            $table->timestamps();
        });

        // Sett inn standardprioriteringer
        DB::table('ticket_priorities')->insert([
            ['name' => 'Low'],
            ['name' => 'Normal'],
            ['name' => 'High'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('ticket_priorities');
    }
};
