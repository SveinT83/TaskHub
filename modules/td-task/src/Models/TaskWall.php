<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTask\Models;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TaskWall extends Model
{
    // -------------------------------------------------
    // Fillable
    // -------------------------------------------------
    protected $fillable = ['name', 'description', 'created_by', 'template'];

    

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CREATOR
    // A function that returns the relationship between task walls and users
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function creator()
    {
        // -------------------------------------------------
        // Return the relationship between task walls and users
        // -------------------------------------------------
        return $this->belongsTo(User::class, 'created_by');
    }

    

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION TASKS
    // A function that returns the relationship between task walls and tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function tasks()
    {
        // -------------------------------------------------
        // Return the relationship between task walls and tasks
        // -------------------------------------------------
        return $this->hasMany(Task::class, 'wall_id');
    }
}