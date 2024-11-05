<?php

namespace tronderdata\TdTickets\Http\Livewire;

use Livewire\Component;

class TicketConfig extends Component
{
    // -------------------------------------------------
    // Define the public properties
    // -------------------------------------------------

    // -------------------------------------------------
    // Define the validation rules
    // -------------------------------------------------
    public function render()
    {
        //return view('livewire.ticket-config');

        return view('tdtickets::livewire.admin.ticket-config')
        ->layout('layouts.app');
    }
        
}
