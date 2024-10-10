<?php

namespace tronderdata\TdTickets\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Services\MailService;
use tronderdata\TdTickets\Models\Ticket;
use tronderdata\TdTickets\Models\Status;
use tronderdata\TdTickets\Models\Queue;
use tronderdata\TdTickets\Models\TicketReply;
use tronderdata\TdTickets\Models\TimeRate;
use tronderdata\TdTickets\Models\TicketTimeSpend;
use tronderdata\TdTickets\Models\TicketPriority;
use tronderdata\TdTickets\Mail\TicketReplyMail;
use tronderdata\TdTickets\Models\TicketCategory;
use tronderdata\TdTickets\Http\Controllers\TicketTaskController;
use tronderdata\TdClients\Models\Client;
use tronderdata\TdClients\Models\ClientUser;
use App\Models\User;
use App\Models\EmailAccount;

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
            $sortOrder = $request->input('sort_order', 'desc');
            $tickets->orderBy($sortBy, $sortOrder);
        } else {
            $tickets->orderBy('created_at', 'desc');
        }

        $tickets = $tickets->paginate(10);

        $statuses = Status::all();

        return view('tdtickets::tickets.index', compact('tickets', 'statuses'));
    }


    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION show
    // Function for showing a single ticket.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function show(Request $request, $id)
    {
        $ticket = Ticket::with(['assignedUser', 'replies.user', 'replies.timeSpends.timeRate'])->findOrFail($id);

        // Hent alle svar for å vise dem på siden
        $replies = $ticket->replies()->orderBy('created_at', 'desc')->get();

        // Hent alle TimeRates
        $timeRates = TimeRate::all();

        // Bruk TicketTaskController for å hente tasks knyttet til denne ticket
        $tasks = (new TicketTaskController)->showTicketTasks($id);

        return view('tdtickets::tickets.show', compact('ticket', 'replies', 'timeRates', 'tasks'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION sendTicketReplyEmail
    // Function for sending an email to the ticket recipient when a reply is made.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    protected function sendTicketReplyEmail(Ticket $ticket, TicketReply $ticketReply, $recipientEmail)
    {
        // Sett e-postkonfigurasjonen
        MailService::setMailConfig(EmailAccount::where('is_default', true)->first());

        // Send e-posten
        Mail::to($recipientEmail)->send(new TicketReplyMail($ticket, $ticketReply));

        // Håndtere feil hvis nødvendig
    }


    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION reply
    // Function for replying to a ticket.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function reply(Request $request, $id)
    {
        $ticket = Ticket::with(['client'])->findOrFail($id);

        // Validering
        $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
            'timeSpend' => 'nullable|integer|min:1',
            'timeRate' => 'nullable|exists:time_rates,id',
            'action' => 'required|string|in:send_response,internal_note',
        ]);

        // Bestem om dette er en intern notis eller et publisert svar
        $isInternal = $request->input('action') === 'internal_note';

        // Lagre reply
        $ticketReply = new TicketReply();
        $ticketReply->ticket_id = $ticket->id;
        $ticketReply->user_id = Auth::id(); // Teknikeren som svarer
        $ticketReply->message = $request->input('message');
        $ticketReply->is_internal = $isInternal;
        $ticketReply->save();

        // Lagre time spend hvis present
        if ($request->filled('timeSpend') && $request->filled('timeRate')) {
            $ticketTimeSpend = new TicketTimeSpend();
            $ticketTimeSpend->ticket_id = $ticket->id;
            $ticketTimeSpend->ticket_reply_id = $ticketReply->id;
            $ticketTimeSpend->time_rate_id = $request->input('timeRate');
            $ticketTimeSpend->time_spend = $request->input('timeSpend'); // Tid i minutter
            $ticketTimeSpend->save();
        }

        // Send e-post hvis det ikke er en intern notis
        if (!$isInternal) {
            try {
                $this->sendTicketReplyEmail($ticket, $ticketReply, $request->input('email'));
            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'E-postsending feilet.');
            }
        }

        return redirect()->back()->with('success', $isInternal ? 'Intern notis lagt til.' : 'Svar sendt.');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION timeSpends
    // Function for listing time spends for a ticket.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    /*
    public function timeSpends()
    {
        return $this->hasMany(TicketTimeSpend::class);
    }
        */



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION addTimeSpend
    // Function for adding time spend to a ticket.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function addTimeSpend(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Validering
        $request->validate([
            'timeSpend' => 'required|integer|min:1',
            'timeRate' => 'required|exists:time_rates,id',
        ]);

        // Lagre time spend
        $ticketTimeSpend = new TicketTimeSpend();
        $ticketTimeSpend->ticket_id = $ticket->id;
        $ticketTimeSpend->time_rate_id = $request->input('timeRate');
        $ticketTimeSpend->time_spend = $request->input('timeSpend'); // Tid i minutter
        $ticketTimeSpend->save();

        return redirect()->back()->with('success', 'Tid registrert på ticketen.');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CreateNewTicket
    // Function for creating a new simple ticket form.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function createNewTicket(Request $request) {

        // -------------------------------------------------
        // Client: Get all clients from Client model - use tronderdata\TdClients\Models\Client; at the top
        // -------------------------------------------------
        $clients = Client::all();

        // -------------------------------------------------
        // Ques: Get all queues - use tronderdata\TdTickets\Models\Queue; at the top
        // -------------------------------------------------
        $queues = Queue::all();

        // -------------------------------------------------
        // TicketCategories: Get all ticket categories - use tronderdata\TdTickets\Models\TicketCategory; at the top
        // -------------------------------------------------
        $ticketCategories = TicketCategory::all();

        // -------------------------------------------------
        // TicketPriorities: Get all ticket priorities - use tronderdata\TdTickets\Models\TicketPriority; at the top
        // -------------------------------------------------
        $ticketPriorities = TicketPriority::all();


        // -------------------------------------------------
        // Return: View
        // -------------------------------------------------
        return view('tdtickets::new.index', compact('clients', 'queues', 'ticketCategories', 'ticketPriorities'));

    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Store
    // Function for storing a new ticket.
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {
        // Validering
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'user_id' => 'required|exists:client_users,id',
            'queue_id' => 'required|exists:tickets_queues,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority_id' => 'required|exists:ticket_priorities,id', // Bruk priority_id nå
            'due_date' => 'nullable|date',
        ]);

        // Opprett ny ticket
        $ticket = new Ticket();
        $ticket->client_id = $request->input('client_id');
        $ticket->user_id = $request->input('user_id'); // Bruker tilknyttet ticketen
        $ticket->queue_id = $request->input('queue_id');
        $ticket->title = $request->input('title');
        $ticket->description = $request->input('description');
        $ticket->priority_id = $request->input('priority_id'); // Nå bruker vi priority_id
        $ticket->due_date = $request->input('due_date');
        $ticket->assigned_to = Auth::id(); // Tildel til innlogget bruker
        $ticket->status_id = 1; // Sett status til "Åpen"
        $ticket->created_by = Auth::id();
        $ticket->updated_by = Auth::id();
        $ticket->save();

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket opprettet!');
    }


    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Get Users By Client
    // Function for getting users by client. Used in ticket form.
    // ---------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Hent alle brukere for en spesifikk klient.
     *
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */

    public function getUsersByClient($client) {

        // -------------------------------------------------
        // Users: Get users by client - used in ticket form
        // -------------------------------------------------
        $users = ClientUser::where('client_id', $client->id)->get(['id', 'first_name', 'last_name', 'email']);

        // -------------------------------------------------
        // Return: JSON response
        // -------------------------------------------------
        return response()->json($users);
    }
}
