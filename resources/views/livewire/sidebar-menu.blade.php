<!-- sidebar-menu.blade.php -->

<!-- 
    This Blade file renders a dynamic sidebar menu that contains top-level menus.
    If the user clicks on a menu, it expands to show child menu items. Child elements 
    can also have their own children, which are shown recursively when clicked.
    
    The Livewire component manages the state (expanded/collapsed) of the menus without 
    needing a full page refresh. When a user toggles a menu, the state of which menus are collapsed
    or expanded is stored temporarily in the Livewire component.
-->

<ul class="list-group list-group-flush">
    <!-- Loop through each top-level menu from the menus table -->
    @foreach ($menus as $menu)
        <!-- 
            Display the main menu item (from the menu table).
            This is always visible, regardless of whether it is expanded or not.
            Clicking this link triggers the toggling of expanded/collapsed state 
            via the Livewire component.
        -->
        <li class="list-group-item list-group-item-action">
            <a href="javascript:void(0);" wire:click.prevent="toggleMenu({{ $menu->id }}, '{{ $menu->url }}')">
                <!-- Display the menu name fetched from the menu table -->
                {{ $menu->name }}
            </a>
        </li>

        <!-- 
            The child items (from menu_items) should only be displayed if the 
            parent menu is expanded.
            The expandedMenus array in the Livewire component tracks which menus are in 
            the expanded state.
        -->
        @if (isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id])

            <!-- Loop through each "menu item" (children of this top-level menu) -->
            @foreach ($menu->items as $item)
                <!-- 
                    Only show top-level items (those without a parent ID).

                    Every menu item that does not have a parent (null parent_id)
                    is considered a top-level item. If it has a parent_id, it 
                    means it's a sub-item of another parent item. 
                -->
                @if (is_null($item->parent_id)) 
                    <li class="list-group-item list-group-item-action {{ in_array($item->id, $activeMenuIds) ? 'active' : '' }}">
                        <a href="javascript:void(0);" wire:click.prevent="toggleMenu({{ $item->id }}, '{{ $item->url }}')">
                            <!-- Display the title of the menu item (can link to a page) -->
                            {{ $item->title }}
                        </a>
                    </li>

                    <!-- 
                        If the menu item has children and is expanded, 
                        display further sub-items by calling a recursive Blade component.
                        
                        The expandedMenus array checks if any of the children need to
                        be expanded based on user interaction or current active page.
                    -->
                    @if (isset($expandedMenus[$item->id]) && $expandedMenus[$item->id])
                        <ul class="list-group list-group-flush">
                            @foreach ($item->children as $child)
                                <!-- Call the partial Blade view to render children -->
                                @include('livewire.partials.menu-item', ['item' => $child, 'level' => 1])
                            @endforeach
                        </ul>
                    @endif
                @endif
            @endforeach
        @endif
    @endforeach
</ul>

<!-- 
    JavaScript-based event listener for Livewire.

    This listens for the custom Livewire event 'menuToggled', which triggers 
    when a menu is expanded or collapsed. When this event is fired, it logs 
    the menuId in the browser's console.
-->
<script>
    document.addEventListener('livewire:load', () => {
        Livewire.on('menuToggled', menuId => {
            console.log('Menu toggled:', menuId);
        });
    });
</script>
