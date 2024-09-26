<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTask\Http\Controllers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use tronderdata\TdTask\Models\Task;
use tronderdata\TdTask\Models\TaskGroup;
use tronderdata\TdTask\Models\TaskStatus;
use tronderdata\TdTask\Models\TaskWall;

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CLASS TaskController
// 
// The TaskController is responsible for managing individual tasks within the TaskHub system.
// 
// A "Task" represents a unit of work or an action item that a user or team needs to complete. Each task contains 
// key information such as the task's title, description, due date, status, and potentially sub-tasks. Tasks can 
// be assigned to specific users and grouped into larger structures such as task walls or categories.
//
// Functionality provided by this controller includes:
//
// - Listing all tasks available to the user or team.
// - Displaying a form for creating a new task.
// - Storing newly created tasks in the system.
// - (Future functionality) Editing, updating, and deleting tasks.
// 
// Tasks serve as the core of the TaskHub system, allowing users to manage their workload effectively. Each task 
// can be assigned a due date and status (e.g., "Not started", "In progress", "Completed"), and tasks can be 
// grouped into task walls for better organization. This controller will handle all CRUD (Create, Read, Update, 
// Delete) operations related to tasks.
//
// The goal of tasks within TaskHub is to help users break down their work into manageable units, track progress, 
// and ensure tasks are completed on time. TaskController is the central point for interacting with and managing 
// individual tasks in the system.
//
// ---------------------------------------------------------------------------------------------------------------------------------------------------
class TaskController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION INDEX
    // Creating a view with all tasks
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Get all tasks
        // -------------------------------------------------
        $tasks = Task::all();

        // -------------------------------------------------
        // Return view whit tasks
        // -------------------------------------------------
        return view('tdtask::tasks.index', compact('tasks'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CEATE
    // Creating a view for creating a new task
    //
    // --------------------------------------------------------------------------------------------------------------------------------------------------
    public function create()
    {

        // -------------------------------------------------
        // Return view for creating a new task
        // -------------------------------------------------
        return view('tdtask::tasks.create');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION STORE
    // Store a new task
    //
    // --------------------------------------------------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {

        // -------------------------------------------------
        // Create a new task
        // -------------------------------------------------
        $task = Task::create($request->all());

        // -------------------------------------------------
        // Return view for creating a new task
        // -------------------------------------------------
        return redirect()->route('tasks.index');
    }
}
