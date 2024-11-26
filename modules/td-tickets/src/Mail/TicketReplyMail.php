<?php

namespace tronderdata\TdTickets\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use tronderdata\TdTickets\Models\Ticket;
use tronderdata\TdTickets\Models\TicketReply;

class TicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $ticketReply;

    public function __construct(Ticket $ticket, TicketReply $ticketReply)
    {
        $this->ticket = $ticket;
        $this->ticketReply = $ticketReply;
    }

    public function build()
    {
        $messageId = "<ticket-{$this->ticket->id}-reply-{$this->ticketReply->id}@dittdomene.no>";

        return $this->subject('Svar på din ticket #' . $this->ticket->id)
                    ->view('tdtickets::emails.ticket_reply')
                    ->withSwiftMessage(function ($message) use ($messageId) {
                        $message->getHeaders()
                                ->addTextHeader('Message-ID', $messageId);
                    });
    }
}
