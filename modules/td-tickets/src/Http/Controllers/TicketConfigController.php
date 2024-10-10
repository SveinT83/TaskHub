<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTickets\Http\Controllers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
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

class TicketConfigController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Index
    // Show the configuration page for the ticket module
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index(Request $request)
    {
        // Henter den innloggede brukeren
        $user = auth()->user();

        // Sjekker om brukeren har tillatelsen 'manageclients'
        if (!$user->can('tickets.admin')) {
            // Hvis brukeren ikke har tillatelsen, returnerer vi en 403-feil (forbudt tilgang)
            abort(403, 'You do not have permission to manage clients.');
        }

        return view('tdtickets::admin.config');
    }
}
