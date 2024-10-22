<?php

namespace tronderdata\TdTickets\Http\Controllers;

use App\Http\Controllers\Controller;

// Importer Ticket-modellen med riktig namespace
use tronderdata\TdTickets\Models\Ticket;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class TicketTaskController extends Controller
{

    // --------------------------------------------------------------------------------------------------
    // A list of task modules that can be used in the system
    // Each module should have a name, API routes, and a model
    // --------------------------------------------------------------------------------------------------
    protected $taskModules = [
        // TdTask Module
        [
            'name' => 'td-task',
            'path' => 'modules/td-task',  // Filstien til modulen
            'version_file' => 'module.json',  // Filen hvor versjonsnummer er definert
            'supported_version' => '1.0.0',  // Versjonen som tickets-modulen er kompatibel med
        ],
    ];



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION getActiveModule
    // Henter aktiv task-modul basert på tilgjengelig versjon fra composer.json og API-støtte
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getActiveModule()
    {
        foreach ($this->taskModules as $module) {

            // -------------------------------------------------
            // Sjekk om composer.json-filen finnes i modulens path
            // -------------------------------------------------
            $composerFilePath = base_path($module['path'] . '/composer.json');

            // -------------------------------------------------
            // Module Exists
            // -------------------------------------------------
            if (file_exists($composerFilePath)) {

                // -------------------------------------------------
                // Get the module version from composer.json
                // -------------------------------------------------
                $composerData = json_decode(file_get_contents($composerFilePath), true);

                if (isset($composerData['version'])) {
                    // -------------------------------------------------
                    // Sammenlign modulens versjon med den støttede versjonen
                    // -------------------------------------------------
                    $installedVersion = $composerData['version'];
                    $supportedVersion = $module['supported_version'];

                    // -------------------------------------------------
                    // Hvis versjonen er lik eller mindre enn støttet versjon, returner modulen
                    // -------------------------------------------------
                    if (version_compare($installedVersion, $supportedVersion, '<=')) {
                        return $module;  // Returner modulen hvis versjonen er kompatibel
                    }
                }
            }
        }

        // -------------------------------------------------
        // Ingen kompatible moduler funnet
        // -------------------------------------------------
        return false;
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION showTicketTasks
    // Henter tasks knyttet til en spesifikk ticket basert på task_wall_id
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function showTicketTasks($ticketId)
    {
        // -------------------------------------------------
        // Get the active task module
        // -------------------------------------------------
        $activeModule = $this->getActiveModule();

        // -------------------------------------------------
        // If no active module is found, return 'no-module' to indicate that no compatible module is available
        // -------------------------------------------------
        if (!$activeModule) {
            return 'no-module';
        }

        // -------------------------------------------------
        // Get the ticket by ID
        // -------------------------------------------------
        $ticket = \tronderdata\TdTickets\Models\Ticket::findOrFail($ticketId);

        // -------------------------------------------------
        // Check if the ticket has a task wall ID from the ticket table
        // -------------------------------------------------
        if (!$ticket->task_wall_id) {
            return collect(); // Return an empty collection if no task_wall_id is present
        }

        // -------------------------------------------------
        // Fetch tasks based on the active module
        // -------------------------------------------------
        switch ($activeModule['name']) {

            // -------------------------------------------------
            // Case TD-TASK: Fetch tasks directly from the TdTask module
            // -------------------------------------------------
            case 'td-task':

                // Importer TdTask-modellen og Task-modellen med riktig namespace
                $taskModel = '\tronderdata\TdTask\Models\Task'; // Path til Task-modellen i td-task modulen

                // Hent oppgaver som er knyttet til ticketens task_wall_id
                $tasks = $taskModel::where('wall_id', $ticket->task_wall_id)->get();

                // -------------------------------------------------
                // Return the tasks
                // -------------------------------------------------
                return $tasks;

            // -------------------------------------------------
            // If the module is not recognized, return an empty collection
            // -------------------------------------------------
            default:
                return collect(); // Return an empty collection if the module is unknown
        }
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION showTaskDetails
    // Henter detaljer for en spesifikk task
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getTaskDetails($ticketId, $taskId)
    {
        // Sjekk om task-modulen er aktiv
        $activeModule = $this->getActiveModule();
        if (!$activeModule) {
            return response()->json(['error' => 'No active module found'], 404);
        }

        // Hent task basert på ID fra td-task modulen
        switch ($activeModule['name']) {
            case 'td-task':
                $taskModel = '\tronderdata\TdTask\Models\Task';  // Path til Task-modellen i td-task modulen
                $task = $taskModel::findOrFail($taskId);
                return response()->json($task);  // Returner task som JSON
            default:
                return response()->json(['error' => 'Module not supported'], 404);
        }
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION STORETASK
    // Store a new task for a ticket
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function storeTask(Request $request, $ticketId)
    {
        // -------------------------------------------------
        // Validate the request data
        // -------------------------------------------------
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'child_task_id' => 'nullable|exists:tasks,id',
        ]);

        // -------------------------------------------------
        // Get the ticket by ID
        // -------------------------------------------------
        $ticket = \tronderdata\TdTickets\Models\Ticket::findOrFail($ticketId);

        // -------------------------------------------------
        // Check if the ticket has a task wall ID from the ticket table
        // -------------------------------------------------
        if (!$ticket->task_wall_id) {
            // Opprett ny Task Wall hvis ingen eksisterer
            $wall = $this->createWallForTicket($ticket);

            // Oppdater ticket med den nye wall ID
            $ticket->task_wall_id = $wall->id;
            $ticket->save();
        }

        // -------------------------------------------------
        // Fetch the correct task model from the td-task module
        // -------------------------------------------------
        $taskModel = '\tronderdata\TdTask\Models\Task';

        // -------------------------------------------------
        // Create a new task for the ticket's wall
        // -------------------------------------------------
        $task = new $taskModel;
        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->due_date = $validatedData['due_date'];
        $task->wall_id = $ticket->task_wall_id; // Assign task to the ticket's wall
        $task->created_by = Auth::id(); // Set the creator of the task

        // Hvis child_task_id er til stede, knytt den til oppgaven
        if ($request->filled('child_task_id')) {
            $task->child_task_id = $request->input('child_task_id');
        }

        // -------------------------------------------------
        // Save the task
        // -------------------------------------------------
        $task->save();

        // -------------------------------------------------
        // Return success message
        // -------------------------------------------------
        return redirect()->route('tickets.show', $ticketId)->with('success', 'Task successfully created and linked to the ticket.');
    }



    protected function createWallForTicket(Ticket $ticket)
    {
        // -------------------------------------------------
        // Get the active task module
        // -------------------------------------------------
        $activeModule = $this->getActiveModule();

        // -------------------------------------------------
        // If no active module is found, throw an exception or handle it
        // -------------------------------------------------
        if (!$activeModule) {
            Log::error("No active task module found.");
            throw new \Exception("No task module available to create a wall.");
        }

        // -------------------------------------------------
        // Use the model directly when we identify td-task
        // -------------------------------------------------
        switch ($activeModule['name']) {

        // -------------------------------------------------
        // Case TD-TASK (direct model use)
        // -------------------------------------------------
        case 'td-task':
            try {
                // -------------------------------------------------
                // Sjekk om TaskWall-modellen fra td-task-modulen eksisterer
                // -------------------------------------------------
                if (class_exists('\tronderdata\TdTask\Models\TaskWall')) {
                    // Bruk den dynamiske modellen
                    $taskWallModel = '\tronderdata\TdTask\Models\TaskWall';

                    // Opprett en ny vegg
                    $wall = new $taskWallModel();
                    $wall->name = 'Wall for Ticket #' . $ticket->id . ' - ' . $ticket->title;  // Inkluder ticket ID i veggens navn
                    $wall->description = 'This wall was automatically created for Ticket #' . $ticket->id;
                    $wall->created_by = Auth::id();  // Sett den innloggede brukeren som skaperen av veggen
                    $wall->save();

                    // -------------------------------------------------
                    // Finn "Tasks"-menyen ved hjelp av slug
                    // -------------------------------------------------
                    $taskMenu = DB::table('menus')->where('slug', 'tasks')->first();

                    if ($taskMenu) {
                        // Legg til veggen som et nytt meny-element under "Tasks"
                        DB::table('menu_items')->insert([
                            'menu_id' => $taskMenu->id,
                            'parent_id' => null,  // Set null for main menu item
                            'title' => 'Ticket: #' . $ticket->id,  // Navn på meny-elementet inkluderer Ticket-ID
                            'url' => "/walls/{$wall->id}",  // URL peker til veggen
                            'icon' => 'bi bi-columns',  // Valgfritt ikon
                            'permission' => null,  // Ingen spesifikk tillatelse for vanlige vegger
                            'order' => 0,  // Standard rekkefølge
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Returner den opprettede veggen
                    return $wall;

                } else {
                    // Hvis modulen ikke eksisterer, logg en melding og håndter situasjonen
                    Log::error("TaskWall-modellen er ikke tilgjengelig.");
                    throw new \Exception("TaskWall-modulen er ikke tilgjengelig, og veggen kan ikke opprettes.");
                }

            } catch (\Exception $e) {
                Log::error("Failed to create wall via direct model: " . $e->getMessage());
                throw new \Exception("Failed to create wall via direct model.");
            }
        }
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION COMPLETE TASK
    // Mark a task as completed or not completed
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function completeTask(Request $request, $ticketId, $taskId)
    {
        try {
            // Hent task basert på ID
            $task = \tronderdata\TdTask\Models\Task::findOrFail($taskId);

            // Veksle status mellom "Closed" (4) og "Open" (1)
            if ($task->status_id == 4) {
                $task->status_id = 1; // Sett til "Open"
            } else {
                $task->status_id = 4; // Sett til "Closed"
            }

            $task->save();

            // Returner en vellykket JSON-respons med den oppdaterte tasken
            return response()->json(['success' => true, 'task' => $task]);
        } catch (\Exception $e) {
            Log::error("Failed to update task status: " . $e->getMessage());

            // Returner en feilmelding i JSON-format
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

}
