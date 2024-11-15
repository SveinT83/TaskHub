<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// COMPONENT - SIDEBAR MENU
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This component is responsible for handling the sidebar menu. It fetches the menus and their items from the database.
// ---------------------------------------------------------------------------------------------------------------------------------------------------
//Fungerer

namespace App\Livewire\Layouts\Navigation;

use Livewire\Component;

// --------------------------------------------------------------------------------------------------
// MODEL - MENU
// --------------------------------------------------------------------------------------------------
// This model is used to interact with the menu data in the database.
//
// Table - Menus:
// id, name, slug, url, description, created_at, updated_at
//
// Table - menu_items
// id, menu_id, parent_id (If child, then the id of the parent menu item), title, url, icon, permission, order, created_at, updated_at
// --------------------------------------------------------------------------------------------------
use App\Models\Menu;

//------------------------------------------------------------
// Meny forklaring.
// Tabellen "Menus" inneholder menyene som skal vises i sidebaren.
// Menyen har inger url, men har en id som brukes til å koble menyen til menyelementene. (menu_items)
// Meny items: De deles inn i to kategorier, parent og child.
// Hvis meny item har en parent_id, så er det en child av en parent med samme id.
// ------------------------------------------------------------
// Hver meny vises i en accordion Og hver parent meny item har en egen accordion item.
// Når en hvilken som helst meny_item er aktiv, så skal parent accordion være aktiv og ha klassen show, hvis ikke skal den ha klassen collapsed.
//-------------------------------------------------------------


class SidebarMenu extends Component
{
   
    public $menus = []; // Holder menydata fra databasen
    public $expandedMenus = []; // Hvilke menyelementer er åpne

    // --------------------------------------------------------------------------------------------------
    // METHODE - MOUNT
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    public function mount()
    {
        // Hent alle menyer og deres tilhørende elementer
        $this->menus = Menu::with(['items.children'])->get();

        // Sett aktive menyer og ekspansjonsstatus basert på URL
        $this->setActiveMenus();
    }



    // --------------------------------------------------------------------------------------------------
    // METHODE - SET ACTIVE MENUS
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    private function setActiveMenus()
    {
        foreach ($this->menus as $menu) {
            foreach ($menu->items as $item) {
                // Hvis en URL matcher, marker elementet som aktivt og åpne relevante accordion
                if ($this->isActive($item->url)) {
                    $this->expandedMenus[$menu->id] = true; // Åpne hovedmeny
                    $this->expandedMenus[$item->id] = true; // Åpne undermeny
                }

                foreach ($item->children as $child) {
                    if ($this->isActive($child->url)) {
                        $this->expandedMenus[$item->id] = true; // Åpne foreldreelement
                        $this->expandedMenus[$child->id] = true; // Åpne aktivt barnelement
                    }
                }
            }
        }
    }

    private function isActive($url)
    {
        return request()->is(trim($url, '/')) || request()->is(trim($url, '/') . '/*');
    }


    // --------------------------------------------------------------------------------------------------
    // METHODE - RENDER
    // --------------------------------------------------------------------------------------------------
    // 
    // --------------------------------------------------------------------------------------------------
    public function render()
    {

        return view('layouts.navigation.sidebar-menu', [
            'menu' => $this->menus,
            'expandedMenus' => $this->expandedMenus,
        ]);
    }
}
