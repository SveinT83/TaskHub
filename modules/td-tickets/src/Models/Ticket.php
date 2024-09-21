<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use tronderdata\TdClients\Models\Client;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'client_id',
        'status_id',
        'queue_id',
        'assigned_to',
        'priority',
        'due_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'due_date' => 'datetime', // Legg til denne linjen
        // Andre casts...
    ];

    // Relasjoner
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function timeSpends()
    {
        return $this->hasMany(TicketTimeSpend::class);
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_date ? $this->due_date->format('Y-m-d') : 'Ingen';
    }

    public function queue() {
        return $this->belongsTo(Queue::class, 'queue_id');
    }
}
