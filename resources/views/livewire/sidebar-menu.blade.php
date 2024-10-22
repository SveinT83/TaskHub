<ul class="nav flex-column">
    <!-- Hardkodet Dashboard-link -->
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
            Dashboard
        </a>
    </li>

    <!-- Menyer med elementer -->
    @foreach($menus as $menu)
        <li class="nav-item">
            <a class="nav-link {{ in_array($menu->id, $activeMenuIds ?? []) ? 'active' : '' }}"
               href="{{ $menu->url ?? 'javascript:void(0);' }}"
               aria-expanded="{{ in_array($menu->id, $activeMenuIds ?? []) ? 'true' : 'false' }}">
                {{ $menu->name }}
            </a>

            <!-- Menyelementer (første nivå) -->
            @if($menu->items->isNotEmpty())
                <ul class="nav flex-column ms-3" id="menu-{{ $menu->id }}" style="{{ in_array($menu->id, $activeMenuIds ?? []) ? 'display: block;' : 'display: none;' }}">
                    <li>x</li>
                    @foreach($menu->items as $item)
                        <li>
                            <a class="nav-link {{ in_array($item->id, $activeMenuIds ?? []) ? 'active' : '' }}"
                               href="{{ $item->url }}">
                                {{ $item->title }}
                            </a>

                            <!-- Barn av dette elementet -->
                            @if($item->children->isNotEmpty())
                                <ul class="nav flex-column ms-4" style="{{ in_array($item->id, $activeMenuIds ?? []) ? 'display: block;' : 'display: none;' }}">
                                    @foreach($item->children as $child)
                                        <li>
                                            <a class="nav-link {{ in_array($child->id, $activeMenuIds ?? []) ? 'active' : '' }}" href="{{ $child->url }}">
                                                {{ $child->title }}
                                            </a>
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
