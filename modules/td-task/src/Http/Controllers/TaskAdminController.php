<?php
namespace tronderdata\TdTask\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use tronderdata\TdTask\Models\Task;
use tronderdata\TdTask\Models\TaskStatus;

class TaskAdminController extends Controller
{
    // Admin dashboard for Task module
    public function index()
    {
        // Hent alle oppgaver og status
        $tasks = Task::with(['status', 'assignee'])->get();
        $statuses = TaskStatus::all();

        return view('tdtask::admin.index', compact('tasks', 'statuses'));
    }
}
