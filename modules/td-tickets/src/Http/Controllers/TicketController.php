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
use tronderdata\TdClients\Models\Client;
use tronderdata\TdTickets\Models\TicketReply;
use tronderdata\TdTickets\Models\TimeRate;
use tronderdata\TdTickets\Models\TicketTimeSpend;
use tronderdata\TdTickets\Mail\TicketReplyMail;
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
    public function show(Request $request, $id)
    {
        $ticket = Ticket::with(['assignedUser', 'replies.user', 'replies.timeSpends.timeRate'])->findOrFail($id);

        // Hent alle svar for å vise dem på siden
        $replies = $ticket->replies()->orderBy('created_at', 'desc')->get();

        // Hent alle TimeRates
        $timeRates = TimeRate::all();

        return view('tdtickets::tickets.show', compact('ticket', 'replies', 'timeRates'));
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

        return redirect()->back()->with('success', $isInternal ? 'Intern notis lagt til.' : 'Svar sendt.');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION timeSpends
    // Function for listing time spends for a ticket.
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function timeSpends()
    {
        return $this->hasMany(TicketTimeSpend::class);
    }



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

}
