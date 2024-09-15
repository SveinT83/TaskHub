<ul class="nav flex-column">
    <!-- Hardkodet Dashboard-link -->
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
            Dashboard
        </a>
    </li>

    @foreach($menus as $menu)
        <!-- Sjekk om noen av child-elementene er aktive -->
        @php
            $isActiveMenu = false;
            foreach($menu->items as $item) {
                if (request()->is(trim($item->url, '/')) || $item->children->contains(function($child) {
                    return request()->is(trim($child->url, '/'));
                })) {
                    $isActiveMenu = true;
                    break;
                }
            }
        @endphp

        <!-- Hovedmenyelement med onclick for å toggles child-elementer -->
        <li class="nav-item">
            <a class="nav-link {{ $isActiveMenu ? 'active' : '' }}" href="javascript:void(0);" onclick="toggleMenu('menu-{{ $menu->id }}')" aria-expanded="{{ $isActiveMenu ? 'true' : 'false' }}">
                {{ $menu->name }}
            </a>

            <!-- Child-elementer med sjekk for om de skal vises (display: block for aktive) -->
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
                                <a class="nav-link {{ $isActiveItem ? 'active' : '' }}" href="javascript:void(0);" onclick="toggleMenu('item-{{ $item->id }}')" aria-expanded="{{ $isActiveItem ? 'true' : 'false' }}">
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


<script>
    function toggleMenu(id) {
        var menu = document.getElementById(id);
        if (menu.style.display === "none") {
            menu.style.display = "block";
        } else {
            menu.style.display = "none";
        }
    }
</script>
