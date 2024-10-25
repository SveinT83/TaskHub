<?php
// Virker
// SidebarMenu.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Menu;

class SidebarMenu extends Component
{
    public $menus = [];
    public $activeMenuIds = [];
    public $expandedMenus = []; // Track which menus are expanded

    public function mount()
    {
        // Fetch all menus and their associated items
        $this->menus = Menu::with(['items.children'])->get();

        // Set active menus based on the current URL
        $this->setActiveMenus();
    }

    private function setActiveMenus()
    {
        foreach ($this->menus as $menu) {
            $menuIsActive = false;

            // Check if the menu itself is active based on the URL
            if ($this->isActive($menu->url)) {
                $this->activeMenuIds[] = $menu->id;
                $this->expandedMenus[$menu->id] = true;
                $menuIsActive = true;
            }

            // Check each item under the menu
            foreach ($menu->items as $item) {
                $itemIsActive = false;

                // If the item is active, mark it and the parent menu as active
                if ($this->isActive($item->url)) {
                    $this->activeMenuIds[] = $item->id;
                    $this->expandedMenus[$menu->id] = true;
                    $itemIsActive = true;
                    $menuIsActive = true;
                }

                // Check each child of the item
                foreach ($item->children as $child) {
                    if ($this->isActive($child->url)) {
                        $this->activeMenuIds[] = $child->id;
                        $this->expandedMenus[$menu->id] = true;
                        $this->expandedMenus[$item->id] = true;
                        $itemIsActive = true;
                        $menuIsActive = true;
                    }
                }

                // If any child of the item is active, mark the item itself as active
                if ($itemIsActive) {
                    $this->activeMenuIds[] = $item->id;
                }
            }

            // If any item under the menu is active, mark the menu itself as active
            if ($menuIsActive) {
                $this->activeMenuIds[] = $menu->id;
            }
        }
    }


    public function isActive($url)
    {
        // Check if the current request URL matches the given URL or its sub-paths
        return request()->is(trim($url, '/')) || request()->is(trim($url, '/') . '/*');
    }

    public function render()
    {
        return view('livewire.sidebar-menu', [
            'menus' => $this->menus,
            'activeMenuIds' => $this->activeMenuIds,
            'expandedMenus' => $this->expandedMenus,
        ]);
    }
}
