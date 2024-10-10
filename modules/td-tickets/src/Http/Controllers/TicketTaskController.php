<?php

namespace tronderdata\TdTickets\Http\Controllers;

use App\Http\Controllers\Controller;

// Importer Ticket-modellen med riktig namespace
use tronderdata\TdTickets\Models\Ticket;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class TicketTaskController extends Controller
{

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION checkTasksTablesExist
    // Sjekker om 'task_walls' og 'tasks' tabellene finnes i databasen
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function checkTasksTablesExist()
    {
        // Sjekker om begge tabeller eksisterer
        return Schema::hasTable('task_walls') && Schema::hasTable('tasks');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION showTicketTasks
    // Henter tasks knyttet til en spesifikk ticket basert på task_wall_id
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function showTicketTasks($ticketId)
    {
        // Først, sjekk om tasks-tabeller finnes
        if (!$this->checkTasksTablesExist()) {
            // Hvis tabellene ikke finnes, returner en tom collection
            return collect();
        }

        // Finn ticket basert på ID
        $ticket = Ticket::findOrFail($ticketId);

        // Hent tasks knyttet til ticketens task wall ID hvis den finnes
        if ($ticket->task_wall_id) {
            return \tronderdata\TdTask\Models\Task::where('wall_id', $ticket->task_wall_id)->get();
        }

        // Returner en tom collection hvis det ikke er noen oppgaver eller task_wall_id
        return collect();
    }
}
