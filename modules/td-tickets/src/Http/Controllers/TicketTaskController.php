<?php

namespace tronderdata\TdTickets\Http\Controllers;

use App\Http\Controllers\Controller;

// Importer Ticket-modellen med riktig namespace
use tronderdata\TdTickets\Models\Ticket;

use Illuminate\Support\Facades\Schema;
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
            'model' => \tronderdata\TdTask\Models\Task::class,
        ],
    ];



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION getActiveModule
    // Henter aktiv task-modul basert på tilgjengelige tabeller i databasen
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getActiveModule()
    {
        foreach ($this->taskModules as $module) {
            // Sjekk om tabellene for denne modulen eksisterer
            if (Schema::hasTable($module['tables'][0]) && Schema::hasTable($module['tables'][1])) {
                return $module; // Returner den aktive modulen
            }
        }

        return false; // Ingen kompatible moduler funnet
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION showTicketTasks
    // Henter tasks knyttet til en spesifikk ticket basert på task_wall_id
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function showTicketTasks($ticketId)
    {
        // Hent aktiv task-modul
        $activeModule = $this->getActiveModule();

        if (!$activeModule) {
            // Hvis ingen aktiv modul er funnet, returner en tom collection
            return collect();
        }

        // Finn ticket basert på ID
        $ticket = \tronderdata\TdTickets\Models\Ticket::findOrFail($ticketId);

        // Sjekk om ticket har en task_wall_id
        if (!$ticket->task_wall_id) {
            return collect();
        }

        // Bruk riktig task-modell fra den aktive modulen for å hente tasks
        $taskModel = $activeModule['model'];

        // Hent tabell- og kolonnenavn for den aktive modulen
        $taskTable = $activeModule['tables']['tasks'];
        $wallIdColumn = $activeModule['columns']['wall_id'];

        // Hent tasks for denne ticketens task_wall_id
        return $taskModel::where($wallIdColumn, $ticket->task_wall_id)->get();
    }
}
