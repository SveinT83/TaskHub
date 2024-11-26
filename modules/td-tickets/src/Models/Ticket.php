<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use tronderdata\TdClients\Models\ClientUser;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'queue_id',
        'ticket_category_id',
        'title',
        'description',
        'priority_id',
        'due_date',
        'assigned_to',
        'status_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasjon til klienten.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relasjon til brukeren.
     */
    public function user()
    {
        return $this->belongsTo(ClientUser::class, 'user_id');
    }

    /**
     * Relasjon til køen.
     */
    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    /**
     * Relasjon til kategorien.
     */
    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    /**
     * Relasjon til brukeren som er tildelt ticketen.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Relasjon til statusen.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Relasjon til brukeren som opprettet ticketen.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasjon til brukeren som oppdaterte ticketen.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasjon til prioritet.
     */
    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id');
    }

    /**
     * Relasjon til TicketTimeSpend
     * En ticket kan ha flere tidsregistreringer.
     */
    public function timeSpends()
    {
        return $this->hasMany(TicketTimeSpend::class, 'ticket_id');
    }

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function replies()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }
}
