<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// COMPONENT - SIDEBAR MENU
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This component is responsible for handling the sidebar menu. It fetches the menus and their items from the database.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Livewire\Layouts\Navigation;

use Livewire\Component;
use App\Models\Menu;

class SidebarMenu extends Component
{
    public $menus = []; // Holder menydata fra databasen
    public $standaloneItems = []; // Holder standalone elementer
    public $expandedMenus = []; // Hvilke menyelementer er åpne

    public function mount()
    {
        // Hent alle menyer og deres tilhørende elementer
        $this->menus = Menu::with(['items.children'])->get();

        // Hent standalone elementer (uten parent_id og ikke is_parent)
        $this->standaloneItems = Menu::whereHas('items', function ($query) {
            $query->whereNull('parent_id')->where('is_parent', false);
        })->get();

        // Sett aktive menyer og ekspansjonsstatus basert på URL
        $this->setActiveMenus();
    }

    private function setActiveMenus()
    {
        foreach ($this->menus as $menu) {
            foreach ($menu->items as $item) {
                if ($this->isActive($item->url)) {
                    $this->expandedMenus[$menu->id] = true; // Åpne hovedmeny
                    $this->expandedMenus[$item->id] = true; // Åpne undermeny
                }

                foreach ($item->children as $child) {
                    if ($this->isActive($child->url)) {
                        $this->expandedMenus[$item->id] = true; // Åpne foreldreelement
                        $this->expandedMenus[$child->id] = true; // Åpne aktivt barneelement
                    }
                }
            }
        }
    }

    private function isActive($url)
    {
        return request()->is(trim($url, '/')) || request()->is(trim($url, '/') . '/*');
    }

    public function render()
    {
        return view('layouts.navigation.sidebar-menu', [
            'menus' => $this->menus,
            'standaloneItems' => $this->standaloneItems,
            'expandedMenus' => $this->expandedMenus,
        ]);
    }
}
