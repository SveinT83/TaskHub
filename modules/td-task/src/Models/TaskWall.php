<?php

namespace tronderdata\TdTask\Models;

use Illuminate\Database\Eloquent\Model;

class TaskWall extends Model
{
    protected $fillable = ['name', 'description', 'created_by'];

    // Relasjon til bruker som opprettet veggen
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasjon til oppgaver som er knyttet til denne veggen
    public function tasks()
    {
        return $this->hasMany(Task::class, 'wall_id');
    }
}
