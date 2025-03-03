<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceHistoryTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('service_history')) {
            Schema::create('service_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
                $table->text('description');
                $table->date('service_date');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('service_history');
    }
}
