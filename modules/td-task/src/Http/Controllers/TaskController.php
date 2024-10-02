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
use App\Models\User;
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
        // Get all walls
        // -------------------------------------------------
        $walls = TaskWall::all();

        // -------------------------------------------------
        // Get all tasks
        // -------------------------------------------------
        $tasks = Task::with(['group', 'assignee'])->get();

        // -------------------------------------------------
        // Get all groups
        // -------------------------------------------------
        $groups = TaskGroup::all();

        // -------------------------------------------------
        // Get users
        // -------------------------------------------------
        $users = \App\Models\User::all();

        // -------------------------------------------------
        // Return view whit tasks
        // -------------------------------------------------
        return view('tdtask::tasks.index', compact('tasks', 'groups', 'users', 'walls'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION SHOW
    // Viser detaljer for en spesifikk oppgave
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function show($id)
    {
        // -------------------------------------------------
        // Get task by id and get data related from colums order by group
        // -------------------------------------------------
        $task = Task::with(['group', 'assignee', 'status', 'parentTask', 'childTasks'])
        ->findOrFail($id);

        // -------------------------------------------------
        // Get all statuses
        // -------------------------------------------------
        $statuses = TaskStatus::all();

        // -------------------------------------------------
        // Get users
        // -------------------------------------------------
        $users = \App\Models\User::all();

        // -------------------------------------------------
        // Return view with task
        // -------------------------------------------------
        return view('tdtask::tasks.profile', compact('task', 'statuses', 'users'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CEATE
    // Creating a view for creating a new task
    //
    // --------------------------------------------------------------------------------------------------------------------------------------------------
    public function create()
    {

        // -------------------------------------------------
        // Get all tasks
        // -------------------------------------------------
        $tasks = Task::all();

        // -------------------------------------------------
        // Get all groups
        // -------------------------------------------------
        $groups = TaskGroup::all();

        // -------------------------------------------------
        // Get all groups
        // -------------------------------------------------
        $users = \App\Models\User::all();

        // -------------------------------------------------
        // Get wall_id from request
        // -------------------------------------------------
        $wall_id = request()->input('wall_id');

        // -------------------------------------------------
        // Return view for creating a new task
        // -------------------------------------------------
        return view('tdtask::tasks.create', compact('tasks', 'groups', 'users', 'wall_id'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION STORE
    // Store a new task
    //
    // --------------------------------------------------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {

        // -------------------------------------------------
        // Validate the request
        // -------------------------------------------------
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'group_id' => 'nullable|exists:task_groups,id',
            'assigned_to' => 'nullable|exists:users,id',
            'child_task_id' => 'nullable|exists:tasks,id',
            'wall_id' => 'nullable|exists:task_walls,id',
        ]);

        // -------------------------------------------------
        // Add the user id to the validated data
        // -------------------------------------------------
        $validatedData['created_by'] = Auth::id();

        // -------------------------------------------------
        // Create a new task with validated data
        // -------------------------------------------------
        $task = Task::create($validatedData);

        // -------------------------------------------------
        // Redirect to the wall if wall_id is set
        // -------------------------------------------------
        if ($request->has('wall_id')) {
            return redirect()->route('walls.show', $request->input('wall_id'))->with('success', 'Task created successfully and added to the wall.');
        }

        // -------------------------------------------------
        // Redirect to the task index page
        // -------------------------------------------------
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION EDIT
    // Viser redigeringsskjemaet for en oppgave
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function edit(Task $task)
    {
        // Henter alle grupper og brukere for valglister
        $groups = TaskGroup::all();
        $users = \App\Models\User::all();
        $tasks = Task::where('id', '!=', $task->id)->get();
        $statuses = TaskStatus::all();

        // Returnerer redigeringsvisning
        return view('tdtask::tasks.edit', compact('task', 'groups', 'users', 'tasks', 'statuses'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION UPDATE
    // Oppdaterer en eksisterende oppgave
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function update(Request $request, Task $task)
    {
        // Validerer forespørselen
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'group_id' => 'nullable|exists:task_groups,id',
            'assigned_to' => 'nullable|exists:users,id',
            'child_task_id' => 'nullable|exists:tasks,id',
        ]);

        // Oppdaterer oppgaven med validerte data
        $task->update($validatedData);

        // Omadresserer til oppgavelisten
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION UPDATE STATUS
    // Update the status of a task
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function updateStatus(Request $request, Task $task)
    {
        // Valider at status_id er gyldig
        $request->validate([
            'status_id' => 'required|exists:task_statuses,id',
        ]);

        // Oppdater statusen på oppgaven
        $task->status_id = $request->status_id;
        $task->save();

        // Returner suksessrespons
        return response()->json(['success' => true]);
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION UPDATE ASSIGNEE
    // Update the assignee of a task
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function updateAssignee(Request $request, Task $task)
    {
        // -------------------------------------------------
        // Valider at den nye assignee er en gyldig bruker-ID, eller tom for å unassign
        // -------------------------------------------------
        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // -------------------------------------------------
        // Oppdater den tilordnede brukeren (eller fjern tilordningen hvis verdien er tom)
        // -------------------------------------------------
        $task->assigned_to = $request->assigned_to;
        $task->save();

        // -------------------------------------------------
        // Returner tilbake med en suksessmelding
        // -------------------------------------------------
        return redirect()->back()->with('success', 'Assignee updated successfully!');
    }

}
