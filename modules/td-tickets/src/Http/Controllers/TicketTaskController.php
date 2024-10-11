<?php

namespace tronderdata\TdTickets\Http\Controllers;

use App\Http\Controllers\Controller;

// Importer Ticket-modellen med riktig namespace
use tronderdata\TdTickets\Models\Ticket;

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

            'api' => [
                // -------------------------------------------------
                // TaskWall API routes
                // -------------------------------------------------
                'getWalls' => '/api.tasks/walls',           // API for å hente alle vegger
                'getWall' => '/api.tasks/walls/{id}',       // API for å hente en spesifikk vegg
                'storeWall' => '/api.tasks/walls',          // API for å opprette ny vegg
                'updateWall' => '/api.tasks/walls/{id}',    // API for å oppdatere en vegg
                'deleteWall' => '/api.tasks/walls/{id}',    // API for å slette en vegg

                // -------------------------------------------------
                // Task API routes
                // -------------------------------------------------
                'getTasks' => '/api.tasks',                 // API for å hente alle oppgaver
                'getTask' => '/api.tasks/{id}',             // API for å hente en spesifikk oppgave
                'storeTask' => '/api.tasks',                // API for å opprette ny oppgave
                'updateTask' => '/api.tasks/{id}',          // API for å oppdatere en oppgave
                'deleteTask' => '/api.tasks/{id}',          // API for å slette en oppgave
            ],
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
            // Case TD-TASK
            // -------------------------------------------------
            case 'td-task':

                // -------------------------------------------------
                // Get the API URL for fetching tasks
                // -------------------------------------------------
                $apiUrl = $activeModule['api']['getTasks']; // Use API to fetch tasks
                $wallId = $ticket->task_wall_id;

                // Fetch tasks using the API
                $tasks = $this->fetchTasksFromApi($apiUrl, $wallId); // Fetch tasks from the API using the wall ID

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
    // FUNCTION fetchTasksFromApi
    // Henter tasks fra API basert på wall_id
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    protected function fetchTasksFromApi($apiUrl, $wallId)
    {
        // -------------------------------------------------
        // Validate the API URL
        // -------------------------------------------------
        $url = str_replace('{id}', $wallId, $apiUrl);

        // -------------------------------------------------
        // Execute the API request
        // -------------------------------------------------
        try {
            $client = new \GuzzleHttp\Client(); // Bruk Guzzle eller annet HTTP-klientbibliotek
            $response = $client->get($url);
            $tasks = json_decode($response->getBody(), true); // Anta at API-et returnerer JSON

            return collect($tasks); // Returner oppgavene som en collection

        // -------------------------------------------------
        // Handle any exceptions that occur during the API request
        // -------------------------------------------------
        } catch (\Exception $e) {
            // Logg eventuelle feil og returner en tom collection
            //\Log::error("Failed to fetch tasks: " . $e->getMessage());
            return collect();
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
        $ticket = Ticket::findOrFail($ticketId);

        // -------------------------------------------------
        // Check if the ticket has a task wall ID from the ticket table
        // -------------------------------------------------
        if (!$ticket->task_wall_id) {

            // -------------------------------------------------
            // Create a new wall for the ticket if no wall is present
            // -------------------------------------------------
            $wall = $this->createWallForTicket($ticket);

            // -------------------------------------------------
            // Update the ticket with the new wall ID
            // -------------------------------------------------
            $ticket->task_wall_id = $wall->id;
            $ticket->save();
        }

        // -------------------------------------------------
        // Get the active task module
        // -------------------------------------------------
        $activeModule = $this->getActiveModule();

        // -------------------------------------------------
        // If no active task module is found, return an error
        // -------------------------------------------------
        if (!$activeModule) {
            return redirect()->back()->with('error', 'No active task module found.');
        }

        // -------------------------------------------------
        // Construct the API URL for storing a task
        // -------------------------------------------------
        $apiUrl = $activeModule['api']['storeTask']; // Fetch the API route from the active module
        $taskData = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'due_date' => $validatedData['due_date'],
            'wall_id' => $ticket->task_wall_id,  // Link the task to the ticket's wall ID
            'child_task_id' => $request->input('child_task_id')
        ];

        // -------------------------------------------------
        // Send a POST request to the API to create the task
        // -------------------------------------------------
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post($apiUrl, [
                'json' => $taskData,  // Send task data as JSON
                'headers' => [
                    'Authorization' => 'Bearer ' . $request->user()->api_token,  // Use user API token for authentication
                    'Accept' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            // If the task creation was successful, redirect back to the ticket
            if (isset($result['success']) && $result['success']) {
                return redirect()->route('tickets.show', $ticketId)->with('success', 'Task successfully created and linked to the ticket.');
            } else {
                // If the API response indicates failure, return an error
                return redirect()->back()->with('error', 'Failed to create the task via the API.');
            }

        } catch (\Exception $e) {
            // Log any errors and return an error message to the user
            Log::error("Failed to create task: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the task.');
        }
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CREATE WALL FOR TICKET
    // Create a new wall for a ticket
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
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
        // Use API to create the wall based on the active module
        // -------------------------------------------------
        switch ($activeModule['name']) {

            // -------------------------------------------------
            // Case TD-TASK (using API)
            // -------------------------------------------------
            case 'td-task':
                try {
                    // API URL for creating a wall
                    $apiUrl = $activeModule['api']['storeWall'];

                    // Data to be sent to the API
                    $data = [
                        'name' => 'Wall for Ticket ' . $ticket->title,
                        'description' => 'This wall was automatically created for Ticket #' . $ticket->id,
                        'created_by' => Auth::id(),
                    ];

                    // Call the API to create the wall
                    $client = new \GuzzleHttp\Client(); // Using Guzzle HTTP client for API calls
                    $response = $client->post($apiUrl, [
                        'json' => $data,
                        'headers' => [
                            'Authorization' => 'Bearer ' . auth()->user()->currentAccessToken()->token,
                        ],
                    ]);

                    // Parse the response and return the created wall object
                    $createdWall = json_decode($response->getBody()->getContents(), true);
                    return (object)$createdWall; // Return wall as an object

                } catch (\Exception $e) {
                    Log::error("Failed to create wall via API: " . $e->getMessage());
                    throw new \Exception("Failed to create wall via API.");
                }

            // -------------------------------------------------
            // Other task module cases can be added here as needed
            // -------------------------------------------------

            default:
                Log::error("Unknown task module: " . $activeModule['name']);
                throw new \Exception("Unknown task module: " . $activeModule['name']);
        }
    }
}
