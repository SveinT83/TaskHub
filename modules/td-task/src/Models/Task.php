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

class Task extends Model
{
    // -------------------------------------------------
    // Fillable
    // -------------------------------------------------
    protected $fillable = ['title', 'description', 'due_date', 'created_by', 'child_task_id', 'status_id', 'group_id', 'assigned_to', 'wall_id'];


    
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CREATOR
    // Relationships between users, taskgroups, taskstatuses
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function creator()
    {
        // -------------------------------------------------
        // Return the relationship between tasks and users
        // -------------------------------------------------
        return $this->belongsTo(User::class, 'created_by');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION ChildTask
    // Relationships between child tasks and tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function childTask()
    {
        // -------------------------------------------------
        // Return the relationship between child tasks and tasks
        // -------------------------------------------------
        return $this->belongsTo(Task::class, 'child_task_id');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION ParentTask
    // Relationships between parent tasks and child tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function parentTask()
    {
        // -------------------------------------------------
        // Return the relationship between tasks and child tasks
        // -------------------------------------------------
        return $this->belongsTo(Task::class, 'parent_task_id');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION ParentTask
    // Relationships to task statuses for tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function status()
    {
        // -------------------------------------------------
        // Return the relationship between tasks and taskstatuses
        // -------------------------------------------------
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Group
    // Relationships to task groups for tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function group()
    {
        // -------------------------------------------------
        // Return the relationship between tasks and taskgroups
        // -------------------------------------------------
        return $this->belongsTo(TaskGroup::class, 'group_id');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION ASSIGNEE
    // Relationships between assignees and tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function assignee()
    {
        // -------------------------------------------------
        // Return the relationship between tasks and the assigned user
        // -------------------------------------------------
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
