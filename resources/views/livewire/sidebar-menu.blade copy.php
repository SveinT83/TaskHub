<!-- Virker -->
<!-- livewire/sidebar-menu.blade.php -->

<ul class="list-group">
    @foreach ($menus as $menu)
        <li class="list-group-item {{ in_array($menu->id, $activeMenuIds) ? 'active' : '' }}">
            <a href="{{ $menu->url }}">
                {{ $menu->name }}
            </a>

            @if (isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id])
                <ul class="list-group-flush">
                    @foreach ($menu->items as $item)
                        <li class="list-group-item list-group-item-dark {{ in_array($item->id, $activeMenuIds) ? 'active' : '' }}">
                            <a href="{{ $item->url }}">{{ $item->title }}</a>

                            @if ($item->children->isNotEmpty())
                                <ul>
                                    @foreach ($item->children as $child)
                                        <li class="list-group-item {{ in_array($child->id, $activeMenuIds) ? 'active' : '' }}">
                                            <a href="{{ $child->url }}">{{ $child->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
