<?php

namespace App\Models\Widgets;

use Illuminate\Database\Eloquent\Model;

class WidgetPosition extends Model
{
    protected $fillable = ['route', 'name', 'widget_id'];

    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}