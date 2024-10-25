<!-- livewire/sidebar-menu.blade.php -->
<ul class="list-group list-group-flush">
    @foreach ($menus as $menu)
        <li class="list-group-item list-group-item-action {{ in_array($menu->id, $activeMenuIds) ? 'active' : '' }}">
            <a href="javascript:void(0);" wire:click.prevent="toggleMenu({{ $menu->id }}, '{{ $menu->url }}')">
                {{ $menu->name }}
            </a>
        </li>

        @if (isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id])
            <ul class="list-group list-group-item-dark">
                @foreach ($menu->items as $item)
                    <li class="list-group-item list-group-item-action {{ in_array($item->id, $activeMenuIds) ? 'active' : '' }}">
                        <a href="{{ $item->url }}">{{ $item->title }}</a>

                        @if ($item->children->isNotEmpty() && isset($expandedMenus[$item->id]) && $expandedMenus[$item->id])
                            <ul>
                                @foreach ($item->children as $child)
                                    <li class="{{ in_array($child->id, $activeMenuIds) ? 'active' : '' }}">
                                        <a href="{{ $child->url }}">{{ $child->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach
</ul>

<script>
    document.addEventListener('livewire:load', () => {
        Livewire.on('menuToggled', menuId => {
            // Handle the menu toggle in the UI if needed
            console.log('Menu toggled:', menuId);
        });
    });
</script>
