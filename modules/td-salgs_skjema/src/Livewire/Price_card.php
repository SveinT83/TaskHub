<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;

class Price_card extends Component
{
    public $totalItems = 0;
    public $totalPrice = 0;

    public function mount()
    {
        $this->calculateTotals();
        $this->pb();
    }

    public function pb() {
        if (session('private')) {
            return "Privat";
        } else if (session('business')) {
            return "Bedrift";
        } else {
            return "Ikke valgt";
        }
    }

    public function calculateTotals()
    {
        $quantities = Session::get('quantities', []);
        $items = AlacarteItems::whereIn('id', array_keys($quantities))->get();

        $this->totalItems = 0;
        $this->totalPrice = 0;

        foreach ($items as $item) {
            $quantity = $quantities[$item->id] ?? 0;

            if ($quantity > 0) {
                $this->totalItems++;
                $this->totalPrice += $quantity * $item->price;
            }
        }
    }

    public function refresh()
    {
        $this->calculateTotals();
    }

    public function render()
    {
        return view('TdSalgsSkjema::livewire.price_card', [
            'totalItems' => $this->totalItems,
            'totalPrice' => number_format($this->totalPrice, 2),
            'pb' => $this->pb()
        ]);
    }
}
