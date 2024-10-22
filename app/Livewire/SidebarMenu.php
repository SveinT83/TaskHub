<?php

namespace App\Livewire;

use App\Models\Menu;

class SidebarMenu extends Component
{
    public $menus = [];
    public $activeMenuIds = [];

    public function mount()
    {
        // Hent alle menyer med relaterte elementer og barn
        $this->menus = Menu::with(['items' => function ($query) {
            $query->with('children');
        }])->get();

        dd($this->menus);

        // Sett aktive menyelementer basert på den nåværende URL
        $this->setActiveMenus();
    }

    private function setActiveMenus()
    {
        // Iterer over hver meny for å finne aktive elementer
        foreach ($this->menus as $menu) {
            foreach ($menu->items as $item) {
                if ($this->isActive($item->url)) {
                    $this->activeMenuIds[] = $item->id;
                    $this->activeMenuIds[] = $menu->id;
                }

                foreach ($item->children as $child) {
                    if ($this->isActive($child->url)) {
                        $this->activeMenuIds[] = $child->id;
                        $this->activeMenuIds[] = $item->id;
                        $this->activeMenuIds[] = $menu->id;
                    }
                }
            }
        }

        dd($this->activeMenuIds);
    }

    public function isActive($url)
    {
        // Skriv ut URL-en som sjekkes og om den er aktiv
        $isActive = request()->is(trim($url, '/')) || request()->is(trim($url, '/') . '/*');
        return $isActive;
    }

    public function render()
    {
        return view('livewire.sidebar-menu', [
            'menus' => $this->menus,
            'activeMenuIds' => $this->activeMenuIds,
        ]);
    }
}
