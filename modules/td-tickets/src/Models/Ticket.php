<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;
use tronderdata\TdClients\Models\Client;
use App\Models\User;

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

    // Relasjoner
    protected $casts = [
        'due_date' => 'datetime',
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function queue()
    {
        return $this->belongsTo(Queue::class, 'queue_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
