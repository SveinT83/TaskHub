<?php

namespace App\Livewire\Layouts\Navigation;

use Livewire\Component;
use App\Models\Menu;

/**
 * SidebarMenu Component
 *
 * This Livewire component dynamically generates a collapsible/expandable sidebar menu
 * based on data fetched from the Menu and MenuItem tables. It handles the opening/
 * closing of menu items, remembers the expanded state across page reloads, and
 * highlights the currently active menu based on the current URL.
 */
class SidebarMenu extends Component
{
    // Stores all menus fetched from the database
    public $menus = [];

    // Keeps track of which menus/items are currently active based on the URL
    public $activeMenuIds = [];

    // Stores the state of which menus are expanded (open) to persist state across page loads
    public $expandedMenus = [];

    /**
     * This method is called when the component is mounted (when it is initialized).
     * It handles data fetching and sets up any necessary initial states.
     */
    public function mount()
    {
        // Fetch all menus along with their related menu items and their children
        $this->menus = Menu::with(['items.children'])->get();

        // Retrieve the expanded menus state from the session, if it exists
        // This way, when the user refreshes the page or navigates away, the previous expanded state is remembered
        $this->expandedMenus = session('expandedMenus', []);

        // Call the method to set the active and expanded menus based on the current URL
        $this->setActiveMenus();
    }

    /**
     * Sets active menus based on the current URL. It iterates through all top-level menu items and their children
     * to check whether they match the current URL.
     *
     * What the method does:
     *  - If a menu item (or one of its children) matches the current URL, it is marked as active
     *    and all parents are expanded to ensure visibility.
     */
    private function setActiveMenus()
    {
        // Traverse all menus to determine which ones are currently active
        foreach ($this->menus as $menu) {
            foreach ($menu->items as $item) {

                // If the main/parent menu item is active based on the URL, mark it as active and expand it
                if ($this->isActive($item->url)) {
                    $this->activeMenuIds[] = $item->id;
                    $this->expandedMenus[$item->id] = true; // Parent is always expanded if it is active
                }

                // If a child item is active, expand its parent as well
                foreach ($item->children as $child) {
                    if ($this->isActive($child->url)) {
                        $this->activeMenuIds[] = $child->id;
                        $this->expandedMenus[$child->id] = true;  // Expand the current child menu
                        $this->expandedMenus[$item->id] = true;   // Expand parent item for its children
                    }
                }
            }
        }
    }

    /**
     * (Private Method) Expands all children for a given parent if the item (or one of its children) is active.
     * This method is recursive and it will continue to expand submenus until all nested items are expanded.
     *
     * @param  Collection $children  The child menu items to be expanded
     */
    private function expandChildren($children)
    {
        // Loop through all child items and mark them as expanded
        foreach ($children as $child) {
            $this->expandedMenus[$child->id] = true;

            // If this child has further children, call this method recursively to expand them as well
            if ($child->children->isNotEmpty()) {
                $this->expandChildren($child->children);
            }
        }
    }

    /**
     * Checks if the current URL matches the passed URL of a menu item.
     * Used to determine which menu item should be marked as active.
     *
     * @param  string  $url  The menu item URL to check
     * @return bool    Returns true if the current request URL matches, otherwise false
     */
    public function isActive($url)
    {
        // Using Laravel's `request()->is()` function to match the URL with the current request's URL
        return request()->is(trim($url, '/')) || request()->is(trim($url, '/') . '/*');
    }

    /**
     * Toggles the expanded/collapsed state of a menu item.
     * When a user clicks on a menu item, this method handles showing or hiding its child elements.
     *
     * @param  int  $menuId  The ID of the menu or menu item to toggle
     * @param  string|null  $url  An optional URL for redirecting the user when a menu item is clicked
     */
    public function toggleMenu($menuId, $url = null)
    {
        // Toggle the expansion state: if the menu is expanded, collapse it, or vice versa
        $this->expandedMenus[$menuId] = !($this->expandedMenus[$menuId] ?? false);

        // Store the updated expanded state into the session to remember it across page loads
        session(['expandedMenus' => $this->expandedMenus]);

        // If this menu item has a URL, navigate to it
        if ($url) {
            return redirect()->to($url);
        }
    }

    /**
     * Render method that returns the Blade view responsible for displaying the sidebar menu.
     * The view will have access to the menus, active menu IDs, and expanded menu states.
     *
     * @return \Illuminate\View\View  The Blade view that renders the sidebar
     */
    public function render()
    {
        return view('layouts.navigation.sidebar-menu', [
            'menus' => $this->menus,                 // Passes the menus fetched from the database
            'activeMenuIds' => $this->activeMenuIds, // Passes the list of active menu IDs
            'expandedMenus' => $this->expandedMenus, // Passes the expanded/collapsed state of each menu item
        ]);
    }
}
