<ul class="nav flex-column">
    <!-- Hardkodet Dashboard-link -->
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
            Dashboard
        </a>
    </li>

    @foreach($menus as $menu)
        <!-- Sjekk om menyen eller noen av child-elementene er aktive -->
        @php
            $isActiveMenu = request()->is(trim($menu->url, '/')) || $menu->items->contains(function($item) {
                return request()->is(trim($item->url, '/')) || $item->children->contains(function($child) {
                    return request()->is(trim($child->url, '/'));
                });
            });
        @endphp

        <!-- Hovedmenyelement med onclick for å toggles child-elementer -->
        <li class="nav-item">
            <a class="nav-link {{ $isActiveMenu ? 'active' : '' }}" 
               href="{{ $menu->url ?? 'javascript:void(0);' }}" 
               onclick="{{ $menu->items->isNotEmpty() ? "toggleMenu('menu-{$menu->id}', {$isActiveMenu})" : '' }}" 
               aria-expanded="{{ $isActiveMenu ? 'true' : 'false' }}">
                {{ $menu->name }}
            </a>

            <!-- Child-elementer vises bare hvis menyen er aktiv -->
            @if($menu->items->isNotEmpty())
                <ul class="nav flex-column ms-3" id="menu-{{ $menu->id }}" style="{{ $isActiveMenu ? 'display: block;' : 'display: none;' }}">
                    @foreach($menu->items as $item)
                        <li class="nav-item">
                            <!-- Sjekk om item eller noen av dets children er aktivt -->
                            @php
                                $isActiveItem = request()->is(trim($item->url, '/')) || $item->children->contains(function($child) {
                                    return request()->is(trim($child->url, '/'));
                                });
                            @endphp

                            @if($item->children->isNotEmpty())
                                <a class="nav-link {{ $isActiveItem ? 'active' : '' }}" 
                                   href="javascript:void(0);" 
                                   onclick="toggleMenu('item-{{ $item->id }}', {{ $isActiveItem }})" 
                                   aria-expanded="{{ $isActiveItem ? 'true' : 'false' }}">
                                    {{ $item->title }}
                                </a>
                                <ul class="nav flex-column ms-4" id="item-{{ $item->id }}" style="{{ $isActiveItem ? 'display: block;' : 'display: none;' }}">
                                    @foreach($item->children as $child)
                                        <li>
                                            <a class="nav-link {{ request()->is(trim($child->url, '/')) ? 'active' : '' }}" href="{{ $child->url }}">
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <!-- Hvis det er et vanlig linkelement -->
                                <a class="nav-link {{ request()->is(trim($item->url, '/')) ? 'active' : '' }}" href="{{ $item->url }}">
                                    {{ $item->title }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
