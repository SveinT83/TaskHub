<?php

namespace App\Livewire\Layouts\Navigation;

use Livewire\Component;
use App\Models\Menu;  // Menu-modellen som kobler mot menydata

class navigation extends Component
{
    public $menus = [];

    // Metode som kjører ved lasting av komponenten
    public function mount()
    {
        // Henter menyer og deres tilknyttede "items"
        $this->menus = Menu::with('items')->get();  // Henter menyene med elementer
    }

    public function render()
    {
        // Gjør menydataene tilgjengelige for 'layouts.navigation'
        return view('layouts.navigation.navigation', [
            'menus' => $this->menus  // Sender menyene til Blade-filen
        ]);
    }
}
