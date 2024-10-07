<?php

namespace tronderdata\TdTask\Models;

use Illuminate\Database\Eloquent\Model;
use tronderdata\TdTask\Models\Task;
use App\Models\User;

class TaskComment extends Model
{
    protected $fillable = ['task_id', 'user_id', 'comment'];
    
    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}