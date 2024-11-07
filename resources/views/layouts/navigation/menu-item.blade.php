<!-- livewire/partials/menu-item.blade.php -->

<!--
    A partial Blade file that renders a single menu item (from the menu_items table).
    This template is called recursively to handle nested menus (menu items with parents and children).

    The structure is dynamic and supports multiple levels of nesting.
    When a menu item is clicked, it either expands or collapses its children.

    Variables used:
      - $item: The current menu item being rendered.
      - $level: The level of nesting for the current item (used for visually indenting nested items).
-->

<!--
<li class="list-group-item {{ in_array($item->id, $activeMenuIds) ? 'active' : '' }}" style="padding-left: {{ $level * 20 }}px;">
-->

    <!--
        The clickable link for the menu item is here.
        When clicked, it prevents the default link behavior and uses Livewire's
        `toggleMenu()` method to expand/collapse this menu item (and optionally navigate to a URL).
    -->
    <a class="list-group-item {{ in_array($item->id, $activeMenuIds) ? 'active' : '' }}" style="padding-left: {{ $level * 20 }}px;" href="{{ $item->url }}" wire:click.prevent="toggleMenu({{ $item->id }}, '{{ $item->url }}')">
        <!-- Display the title of the current menu item -->
        - {{ $item->title }}
    </a>

    <!--
        If the current item has children, display them below the current menu item, but only if:
          - The children collection is not empty (meaning it actually has children).
          - This menu item is explicitly expanded (controlled by Livewire's expandedMenus array).
    -->
    @if ($item->children->isNotEmpty() && isset($expandedMenus[$item->id]) && $expandedMenus[$item->id])
        <ul class="list-group">
            <!--
                Iterate over each child menu item and call this same template (menu-item.blade.php)
                recursively to manage sub-items.

                The level of indentation (`$level + 1`) increases with each nested level to
                ensure proper visual hierarchy.
            -->
            @foreach ($item->children as $child)
                @include('livewire.partials.menu-item', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif

<!--
</li>
-->
