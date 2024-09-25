<?php
// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTasks\Http\Controllers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskWall;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION index
    //
    // Shows all tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {

        // -------------------------------------------------
        // Get all tasks
        // -------------------------------------------------
        $tasks = Task::with('status')->get();

        // -------------------------------------------------
        // Returner the view
        // -------------------------------------------------
        return view('tasks.index', compact('tasks'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION create
    //
    // Shows the form for creating a task
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function create()
    {

        // -------------------------------------------------
        // Get all walls
        // -------------------------------------------------
        $walls = TaskWall::all();

        // -------------------------------------------------
        // Get all statuses
        // -------------------------------------------------
        $statuses = TaskStatus::all();

        // -------------------------------------------------
        // Returner the view
        // -------------------------------------------------
        return view('tasks.create', compact('walls', 'statuses'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION store
    //
    // Stores a new task
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {
        // -------------------------------------------------
        // Validate input
        // -------------------------------------------------
        $task = Task::create([
            'task_wall_id' => $request->task_wall_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'status_id' => $request->status_id,
            'user_id' => Auth::id(),
        ]);

        // -------------------------------------------------
        // Return a redirect and a success message
        // -------------------------------------------------
        return redirect()->route('tasks.index')->with('success', 'Task created!');
    }
}
