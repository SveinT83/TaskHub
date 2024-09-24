<?php
// app/Models/Task.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'due_date', 'nextcloud_task_id', 'nextcloud_synced_at'];

    protected $casts = [
        'due_date' => 'datetime', // Sikrer at due_date blir behandlet som en datetime
    ];
}
