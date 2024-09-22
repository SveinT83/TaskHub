<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'queue_id',
        'ticket_category_id', // Legg til dette
        'title',
        'description',
        'priority',
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
    public function assignee()
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
}
