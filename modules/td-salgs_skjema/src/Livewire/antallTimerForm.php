<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use function session;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;

class antallTimerForm extends Component
{

    // -------------------------------------------------
    // VAR
    // -------------------------------------------------
    public $estimatedHours;
    public $amountUsers;



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // When the component is mounted, set the amount of hours
    // --------------------------------------------------------------------------------------------------
    public function mount()
    {
        // -------------------------------------------------
        // Set number of hours based on the number of users
        // -------------------------------------------------
        $this->calculateEstimatedHours();
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // When the component is mounted, set the amount of hours
    // --------------------------------------------------------------------------------------------------
    public function calculateEstimatedHours()
    {
        // -------------------------------------------------
        // If session that holds the amount of users exists
        // -------------------------------------------------
        if (session('amountUsers')) {

            $amountUsers = session('amountUsers');
            $this->amountUsers = $amountUsers;

            // -------------------------------------------------
            // Calculate and set the estimated hours. Max 40 hours, min 5 hours
            // -------------------------------------------------
            $this->estimatedHours = min(40, max(5, ceil($amountUsers / 5) * 5));

        // -------------------------------------------------
        // If session does not exist
        // -------------------------------------------------
        } else {

            // -------------------------------------------------
            // If unknown, return 5 hours
            // -------------------------------------------------
            $this->estimatedHours = 5;
        }
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // Render the view whih the amount of users
    // --------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('TdSalgsSkjema::livewire.antallTimerForm');
    }
}