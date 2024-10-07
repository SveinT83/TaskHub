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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use tronderdata\TdTask\Models\TaskWall;
use tronderdata\TdTask\Models\TaskStatus;
use HTMLPurifier;
use HTMLPurifier_Config;

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CLASS TaskWallController
//
// The TaskWallController is responsible for managing task "walls", which are used to group and organize tasks.
//
// A "Wall" represents a collection of tasks that are grouped together under a specific category or project.
// Walls serve as a way to visually and logically organize tasks within the system, making it easier to manage
// related tasks for a specific project, department, or category.
//
// Functionality provided by this controller includes:
//
// - Displaying a list of all task walls.
// - Showing the tasks that are grouped under a specific wall.
// - Creating, editing, and deleting task walls (future functionality).
//
// The idea behind walls is to give users a flexible structure for managing their tasks in a project-based or
// categorized manner, allowing for better task management and overview within the TaskHub system. Each wall can
// contain multiple tasks, and walls themselves can be customized based on the needs of the user or organization.
//
// ---------------------------------------------------------------------------------------------------------------------------------------------------
class TaskWallController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION INDEX
    // Creating a view with all walls
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {

        // -------------------------------------------------
        // Get all walls
        // -------------------------------------------------
        $walls = TaskWall::all();

        // -------------------------------------------------
        // Return view with walls
        // -------------------------------------------------
        return view('tdtask::walls.index', compact('walls'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION SHOW
    // Creating a view with a specific wall
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function show($id)
    {

        // -------------------------------------------------
        // Get wall by id
        // -------------------------------------------------
        $wall = TaskWall::findOrFail($id);

        // -------------------------------------------------
        // Get All Tasks grouped by status_id
        // -------------------------------------------------
        $tasksGroupedByStatus = $wall->tasks()
            ->with(['group', 'assignee', 'status', 'parentTask', 'childTasks']) // Include parent and child tasks
            ->get()
            ->groupBy(function ($task) {
                return $task->status_id ?? 'no_status'; // Group by status_id or 'no_status' for NULL
            });

        // -------------------------------------------------
        // Clean HTML in the description for each task
        // -------------------------------------------------
        $purifier = new HTMLPurifier(HTMLPurifier_Config::createDefault());
        foreach ($tasksGroupedByStatus as $status => $tasks) {
            foreach ($tasks as $task) {
                // Rens HTML før du sender til visningen
                $task->description = $purifier->purify($task->description);
            }
        }

        // -------------------------------------------------
        // Get all statuses for display purposes
        // -------------------------------------------------
        $statuses = TaskStatus::all();

        // -------------------------------------------------
        // Logikk for slett-knappen
        // -------------------------------------------------
        // Brukeren kan slette veggen hvis:
        // 1. De har superadmin- eller task-admin-rettigheter, ELLER
        // 2. De er eieren av veggen (created_by), ELLER
        // 3. Alle oppgaver er fullført (ingen oppgaver er igjen)
        
        $canDeleteWall = Auth::user()->can('task.admin') || Auth::user()->can('superadmin.delete') || Auth::id() === $wall->created_by;

        // Sjekk om det er oppgaver som ikke er "done"
        $allTasksDone = $wall->tasks->every(function ($task) {
            return $task->status && $task->status->status_name === 'Completed';
        });

        // Hvis veggen har oppgaver som ikke er "done", kan den ikke slettes
        if ($wall->tasks->isEmpty()) {
            $allTasksDone = true; // Hvis det ikke er oppgaver, sett $allTasksDone til true
        }

        $canDeleteWall = $canDeleteWall && $allTasksDone;

        // -------------------------------------------------
        // Return view with wall
        // -------------------------------------------------
        return view('tdtask::walls.show', compact('wall', 'tasksGroupedByStatus', 'statuses', 'canDeleteWall'));
    }



    // -------------------------------------------------
    // FUNCTION CREATE
    // Viser skjemaet for å opprette en ny "wall"
    // -------------------------------------------------
    public function create()
    {
        // -------------------------------------------------
        // check if user can create a template
        // -------------------------------------------------
        $canCreateTemplate = Gate::allows('superadmin.create') || Gate::allows('task.admin');

        // -------------------------------------------------
        // Hent alle templates som kan brukes som mal
        // -------------------------------------------------
        $templates = TaskWall::where('template', true)->get();

        // -------------------------------------------------
        // Return view with wall creation form
        // -------------------------------------------------
        return view('tdtask::walls.create', compact('canCreateTemplate', 'templates'));
    }



    // -------------------------------------------------
    // FUNCTION STORE
    // Lagre en ny "wall" i databasen og legg til en menyoppføring
    // -------------------------------------------------
    public function store(Request $request)
    {
        // -------------------------------------------------
        // Validering av inputdata
        // -------------------------------------------------
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template' => 'nullable|boolean',
            'template_id' => 'nullable|exists:task_walls,id'
        ]);

        // -------------------------------------------------
        // Legg til bruker ID som opprettet veggen
        // -------------------------------------------------
        $validatedData['created_by'] = Auth::id();

        // -------------------------------------------------
        // Finn "Tasks"-menyen ved hjelp av slug
        // -------------------------------------------------
        $taskMenu = DB::table('menus')->where('slug', 'tasks')->first();

        if ($taskMenu) {

            // -------------------------------------------------
            // Create the wall and store the entire object
            // -------------------------------------------------
            $taskWall = TaskWall::create($validatedData);

            // -------------------------------------------------
            // Håndter tilfeller der veggen er en template
            // -------------------------------------------------
            $title = $taskWall->name;
            $isTemplate = $request->input('template', false); // Sjekker om det er en template

            if ($isTemplate) {
                // Hvis veggen er en template, legg til prefiks og sett nødvendig permission
                $title = 'tp: ' . $taskWall->name;
                $permission = 'task.admin'; // Kun de med task.admin tillatelse skal se denne
            } else {
                $permission = null; // Vanlige walls har ingen spesielle tillatelser
            }

            // -------------------------------------------------
            // Kopier oppgaver fra en template hvis template_id er valgt
            // -------------------------------------------------
            if ($request->filled('template_id')) {
                $template = TaskWall::findOrFail($request->input('template_id')); // Finn templaten

                // Sjekk om templaten har noen oppgaver
                if ($template->tasks()->exists()) {
                    foreach ($template->tasks as $task) {
                        // Kopier hver oppgave fra templaten til den nye veggen
                        $newTask = $task->replicate(); // Lager en kopi av tasken
                        $newTask->wall_id = $taskWall->id; // Setter vegg-ID til den nye veggen

                        // Hvis det er påkrevde felter som mangler, kan du fylle dem ut her
                        $newTask->created_by = Auth::id(); // Eks: Sørg for at den nye oppgaven har en oppretter

                        $newTask->save(); // Lagre kopien
                    }
                }
            }

            // -------------------------------------------------
            // Legg til veggen som et meny-element under "Tasks"-menyen
            // -------------------------------------------------
            DB::table('menu_items')->insert([
                'menu_id' => $taskMenu->id,
                'parent_id' => null, // Hvis du vil ha den som et hovedmenypunkt
                'title' => $title, // Bruk navnet på veggen, evt. prefikset tittel for templates
                'url' => "/walls/{$taskWall->id}", // Lagre relativ URL
                'icon' => 'bi bi-columns', // Valgfritt ikon for menyen
                'permission' => $permission, // Sett tillatelser for templates
                'order' => 0, // Endre rekkefølgen hvis nødvendig
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // -------------------------------------------------
        // Lag dynamisk suksessmelding basert på om det er en template eller en vanlig wall
        // -------------------------------------------------
        $message = $isTemplate ? 'Template created successfully and added to menu.' : 'Wall created successfully and added to menu.';

        // -------------------------------------------------
        // Omadresser tilbake til veggoversikten med dynamisk suksessmelding
        // -------------------------------------------------
        return redirect()->route('walls.show', $taskWall->id)->with('success', $message);
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION DESTROY
    // Slett en vegg hvis alle tilknyttede tasks er fullført, og brukeren har nødvendige rettigheter
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function destroy($id)
    {
        // Hent veggen basert på ID
        $wall = TaskWall::findOrFail($id);

        // Sjekk om brukeren har nødvendige rettigheter eller er eieren av veggen
        $user = Auth::user();

        $canDeleteWall = $user->can('task.admin') || $user->can('superadmin.delete') || $user->id === $wall->created_by;

        // Hvis brukeren ikke har nødvendige rettigheter, redirect med feilmelding
        if (!$canDeleteWall) {
            return redirect()->route('walls.show', $wall->id)
                            ->with('error', 'You do not have permission to delete this wall.');
        }

        // Hent alle tasks knyttet til veggen
        $tasks = $wall->tasks;

        // Sjekk om alle tasks er merket som "done"
        $allTasksDone = $tasks->every(function ($task) {
            return $task->status && $task->status->status_name === 'Completed'; // Sjekker om statusen er "Completed"
        });

        // Hvis ikke alle oppgaver er fullført, redirect med feilmelding
        if (!$allTasksDone) {
            return redirect()->route('walls.show', $wall->id)
                            ->with('error', 'You cannot delete this wall because not all tasks are marked as "done".');
        }

        // Slett tilhørende meny-element
        DB::table('menu_items')->where('url', "/walls/{$id}")->delete();

        // Hvis alle tasks er fullført, slett veggen
        $wall->delete();

        return redirect()->route('tasks.index')->with('success', 'Wall and associated menu item deleted successfully.');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION GET TEMPLATE
    // Henter template data basert på ID
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getTemplate($id)
    {
        // Finn templaten basert på ID
        $template = TaskWall::where('id', $id)->where('template', true)->firstOrFail();
        
        // Returner navn og beskrivelse som JSON
        return response()->json([
            'name' => $template->name,
            'description' => $template->description
        ]);
    }

}
