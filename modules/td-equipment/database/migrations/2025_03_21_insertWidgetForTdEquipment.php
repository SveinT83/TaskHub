<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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
        if (Schema::hasTable('widgets') && Schema::hasTable('widget_positions')) {
            // Sett inn widget
            $widgetId = DB::table('widgets')->insertGetId([
                'name' => 'Equipments List Widget',
                'description' => 'This widget displays a list of equipments.',
                'view_path' => 'widgets::equipmentsListWidget',
                'module' => 'td-equipment',
                'controller' => 'TronderData\Equipment\Http\Controllers\WidgetController@equipmentsListWidget',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Sett inn widget posisjon
            DB::table('widget_positions')->insert([
                'route' => 'dashboard',
                'name' => 'main',
                'widget_id' => $widgetId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('widgets') && Schema::hasTable('widget_positions')) {
            // Slett widget posisjon
            DB::table('widget_positions')->where('route', 'dashboard')->where('name', 'main')->delete();

            // Slett widget
            DB::table('widgets')->where('view_path', 'widgets::equipmentsListWidget')->delete();
        }
    }
};