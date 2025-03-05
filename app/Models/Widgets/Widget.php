<?php

namespace App\Models\Widgets;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = ['name', 'description', 'view_path', 'module', 'controller'];

    public function positions()
    {
        return $this->hasMany(WidgetPosition::class);
    }
}
