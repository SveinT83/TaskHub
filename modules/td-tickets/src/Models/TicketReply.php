<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TicketReply extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_internal', // Legg til dette feltet
    ];

    // Relasjoner
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeSpends()
    {
        return $this->hasMany(TicketTimeSpend::class);
    }
}
