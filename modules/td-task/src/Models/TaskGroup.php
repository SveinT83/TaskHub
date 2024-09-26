<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTask\Models;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use Illuminate\Database\Eloquent\Model;

class TaskGroup extends Model
{
    // -------------------------------------------------
    // Fillable
    // -------------------------------------------------
    protected $fillable = ['name', 'description', 'created_by'];



    // Relasjon til bruker som opprettet gruppen
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CREATOR
    // A function that returns the relationship between task groups and users
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function creator()
    {
        // -------------------------------------------------
        // Return the relationship between task groups and users
        // -------------------------------------------------
        return $this->belongsTo(User::class, 'created_by');
    }



    // Relasjon til oppgaver som er i denne gruppen
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION TASKS
    // A function that returns the relationship between task groups and tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function tasks()
    {
        // -------------------------------------------------
        // Return the relationship between task groups and tasks
        // -------------------------------------------------
        return $this->hasMany(Task::class, 'group_id');
    }



    // Relasjon til vegg (en gruppe kan være knyttet til en vegg)
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION WALL
    // A function that returns the relationship between task groups and task walls
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function wall()
    {
        // -------------------------------------------------
        // Return the relationship between task groups and task walls
        // -------------------------------------------------
        return $this->belongsTo(TaskWall::class, 'wall_id');
    }
}
