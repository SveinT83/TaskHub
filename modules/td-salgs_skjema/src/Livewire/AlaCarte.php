<?php

//Funker

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;

class aLaCarte extends Component
{
    public $quantities = []; // Holder antall per item

    public function mount()
    {
        // Hent session-data ved initiering
        $this->quantities = session('quantities', []);
    }

    public function updateQuantity($alacarteId, $quantity)
    {
        // Oppdater antallet for et gitt alacarte-item
        if ($quantity <= 0) {
            unset($this->quantities[$alacarteId]); // Fjern hvis mengden er null eller negativ
        } else {
            $this->quantities[$alacarteId] = $quantity; // Oppdater med ny mengde
        }

        // Oppdater sessionen
        session(['quantities' => $this->quantities]);
    }

    public function render()
    {
        return view('TdSalgsSkjema::livewire.aLaCarte', [
            'alacarteItems' => AlacarteItems::all(),
        ]);
    }
}