<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;

class Alacarte extends Component
{
    public $alacarteItems = [];
    public $quantities = [];

    public function mount()
    {
        // Hent alle Alacarte-elementer fra databasen
        $this->alacarteItems = AlacarteItems::all();
        $this->quantities = Session::get('quantities', []); // Hent tidligere lagrede mengder fra session
    }

    public function updateQuantity($itemId, $value)
    {
        // Oppdater antall i session
        $this->quantities[$itemId] = max(0, (int) $value); // Sørg for at verdien er minst 0
        Session::put('quantities', $this->quantities); // Lagre oppdatert session
    }

    public function render()
    {
        return view('TdSalgsSkjema::livewire.alacarte', [
            'alacarteItems' => $this->alacarteItems,
        ]);
    }
}
