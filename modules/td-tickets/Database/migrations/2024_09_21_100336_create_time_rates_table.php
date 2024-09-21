<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRatesTable extends Migration
{
    public function up()
    {
        Schema::create('time_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // F.eks. 'On Site', 'Remote Support', 'Internal Work'
            $table->decimal('price', 10, 2); // Pris per time
            $table->boolean('taxable')->default(true); // Om prisen er skattepliktig
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_rates');
    }
}
