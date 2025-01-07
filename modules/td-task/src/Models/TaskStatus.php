<?php

namespace tronderdata\TdTask\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $fillable = ['status_name'];

    // Relasjon til oppgaver med denne statusen
    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
