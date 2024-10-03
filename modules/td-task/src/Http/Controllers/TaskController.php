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
use tronderdata\TdTask\Models\TaskComment;
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
        // Get walls
        // -------------------------------------------------
        $walls = TaskWall::all();

        // -------------------------------------------------
        // Get comments related to the task
        // -------------------------------------------------
        $comments = TaskComment::where('task_id', $id)->get();

        // -------------------------------------------------
        // Return view with task
        // -------------------------------------------------
        return view('tdtask::tasks.profile', compact('task', 'statuses', 'users', 'walls', 'comments'));
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
    public function updateStatus(Request $request, $id)
    {
        // Finn oppgaven
        $task = Task::findOrFail($id); // Finn eksisterende task med ID
    
        // Valider forespørselen hvis nødvendig (for eksempel status_id)
        $request->validate([
            'status_id' => 'required|exists:task_statuses,id',
        ]);
    
        // Oppdater statusen til tasken
        $task->status_id = $request->input('status_id');
        $task->save(); // Oppdaterer i databasen i stedet for å opprette ny
    
        // Omadresser til task-siden med en suksessmelding
        return redirect()->route('tasks.show', $task->id)->with('success', 'Task status updated successfully!');
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


    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION UPDATE WALL
    // Update the assignee of a task
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function updateWall(Request $request, Task $task)
    {
        $task = Task::findOrFail($id);
        $task->status_id = $request->input('status_id');
        $task->save();
    
        return redirect()->route('tasks.show', $task->id)->with('success', 'Task status updated successfully!');
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION DESTROY
    // Sletter en oppgave basert på ID
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function destroy($id)
    {
        // -------------------------------------------------
        // Finn oppgaven ved ID
        // -------------------------------------------------
        $task = Task::findOrFail($id);

        // -------------------------------------------------
        // Hent nåværende bruker
        // -------------------------------------------------
        $user = Auth::user();

        // -------------------------------------------------
        // Sjekk om brukeren har tillatelse til å slette oppgaven
        // -------------------------------------------------
        if ($user->id !== $task->created_by && $user->id !== $task->assigned_to && !$user->hasPermissionTo('task.delete') && !$user->hasPermissionTo('task.admin')) {
            // -------------------------------------------------
            // Returner en feilmelding hvis brukeren ikke har rettigheter
            // -------------------------------------------------
            return redirect()->back()->withErrors('You do not have permission to delete this task.');
        }

        // -------------------------------------------------
        // Sjekk om oppgaven har child oppgaver
        // -------------------------------------------------
        if ($task->childTasks()->count() > 0) {
            // -------------------------------------------------
            // Returner en feilmelding hvis det finnes child oppgaver
            // -------------------------------------------------
            return redirect()->back()->withErrors('You must delete the child tasks before deleting this task.');
        }

        // -------------------------------------------------
        // Slett oppgaven
        // -------------------------------------------------
        $task->delete();

        // -------------------------------------------------
        // Redirect til oppgavelisten med en suksessmelding
        // -------------------------------------------------
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION STORE COMMENT
    // Save a new comment for a task
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function storeComment(Request $request, $taskId)
    {
        // -------------------------------------------------
        // Validate the request
        // -------------------------------------------------
        $validatedData = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // -------------------------------------------------
        // Create a new comment for the task
        // -------------------------------------------------
        TaskComment::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'comment' => $validatedData['comment'],
        ]);

        // -------------------------------------------------
        // Redirect back to the task with a success message
        // -------------------------------------------------
        return redirect()->route('tasks.show', $taskId)->with('success', 'Comment added successfully.');
    }


    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION DELETE COMMENT
    // Delete a comment for a task
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function deleteComment($taskId, $commentId)
    {
        // Finn kommentaren basert på ID
        $comment = \tronderdata\TdTask\Models\TaskComment::findOrFail($commentId);

        // Sjekk om brukeren har tillatelse til å slette kommentaren
        $user = Auth::user();

        // Sjekk om brukeren er forfatteren av kommentaren eller har tillatelse til å slette den
        if ($user->id === $comment->user_id || $user->hasPermissionTo('task.edit') || $user->hasPermissionTo('task.admin')) {
            // Slett kommentaren
            $comment->delete();

            // Omadresser til oppgavens profil med en suksessmelding
            return redirect()->route('tasks.show', $taskId)->with('success', 'Comment deleted successfully.');
        } else {
            // Om brukeren ikke har tilgang, vis en feilmelding
            return redirect()->route('tasks.show', $taskId)->with('error', 'You do not have permission to delete this comment.');
        }
    }

}
