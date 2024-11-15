<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TicketTimeSpend extends Model
{
    protected $fillable = [
        'ticket_id',
        'ticket_reply_id', // Legg til dette hvis tidsforbruk er knyttet til en reply
        'time_rate_id',
        'time_spend',
        'billed',
    ];

    // Relasjoner
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function ticketReply()
    {
        return $this->belongsTo(TicketReply::class);
    }

    public function timeRate()
    {
        return $this->belongsTo(TimeRate::class);
    }
}
