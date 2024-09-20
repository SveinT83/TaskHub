<?php

namespace tronderdata\TdTickets\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use tronderdata\TdTickets\Models\Ticket;
use tronderdata\TdTickets\Models\Status;
use tronderdata\TdTickets\Models\Queue;
use tronderdata\TdClients\Models\Client;
use App\Models\User;

class TicketController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Index
    // Standard function for listing tickets. This function is used for both the main ticket list and the technician's assigned tickets list.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index(Request $request)
    {
        $tickets = Ticket::whereHas('status', function ($query) {
            $query->where('name', '!=', 'Closed');
        });

        // Filtrering
        if ($request->has('status')) {
            $tickets->whereHas('status', function ($query) use ($request) {
                $query->where('name', $request->input('status'));
            });
        }

        if ($request->has('client_id')) {
            $tickets->where('client_id', $request->input('client_id'));
        }

        if ($request->has('assigned_to')) {
            $tickets->where('assigned_to', $request->input('assigned_to'));
        }

        // Søk
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $tickets->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Sortering
        if ($request->has('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order', 'asc');
            $tickets->orderBy($sortBy, $sortOrder);
        } else {
            $tickets->orderBy('created_at', 'desc');
        }

        $tickets = $tickets->paginate(10);

        $statuses = Status::all();

        return view('tdtickets::tickets.index', compact('tickets', 'statuses'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION myTickets
    // Function for listing tickets assigned to the logged in user.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function myTickets(Request $request)
    {
        $userId = auth()->id();

        $tickets = Ticket::where('assigned_to', $userId);

        // De samme filtrerings- og sorteringsalternativene som i index()

        $tickets = $tickets->paginate(10);

        return view('tdtickets::tickets.my_tickets', compact('tickets'));
    }


    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION show
    // Function for showing a single ticket.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        return view('tdtickets::tickets.show', compact('ticket'));
    }
}
