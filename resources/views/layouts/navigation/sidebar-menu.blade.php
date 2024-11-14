<!-- sidebar-menu.blade.php -->

<!-- ------------------------------------------------- -->
<!-- This is the sidebar menu that will be displayed on the left side of the screen. -->
<!-- ------------------------------------------------- -->
<div class="accordion accordion-main-menu" id="accordionSideMenu" style="margin: 0px; padding: 0px; width: 100%;">

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Render each menu with its items -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    @foreach ($menus as $menu)

        <!-- ------------------------------------------------- -->
        <!-- Accordion item for the menu -->
        <!-- ------------------------------------------------- -->
        <div class="accordion-item">

            <!-- Accordion Header -->
            <h2 class="accordion-header">
                <button class="accordion-button {{ isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id] ? '' : 'collapsed' }}"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseMenu{{ $menu->id }}"
                        aria-expanded="{{ isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id] ? 'true' : 'false' }}"
                        aria-controls="collapseMenu{{ $menu->id }}">
                    {{ $menu->name }}
                </button>
            </h2>

            <!-- Accordion Collapse Body -->
            <div id="collapseMenu{{ $menu->id }}" 
                 class="accordion-collapse collapse {{ isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id] ? 'show' : '' }}" 
                 data-bs-parent="#accordionSideMenu">

                <!-- Accordion Body -->
                <div class="accordion-body" style="padding: 0px;">

                    <!-- -------------------------------------------------------------------------------------------------- -->
                    <!-- Iterate through all menu items -->
                    <!-- -------------------------------------------------------------------------------------------------- -->
                    @foreach ($menu->items as $item)

                        <!-- Standalone menu item -->
                        @if (!$item->is_parent && is_null($item->parent_id))
                            <a href="{{ $item->url }}" 
                               class="d-block px-3 py-2 {{ request()->is(trim($item->url, '/')) ? 'text-primary fw-bold' : '' }}">
                                <i class="{{ $item->icon }}"> </i> {{ $item->title }}
                            </a>
                        @endif

                        <!-- Parent menu item -->
                        @if ($item->is_parent)
                            <div class="accordion accordion-cild-menu" id="accordionChildMenu" style="margin: 0px; padding: 0px;">
                                
                                <!-- ------------------------------------------------- -->
                                <!-- Accordion item -->
                                <!-- ------------------------------------------------- -->
                                <div class="accordion-item">
                                    <h3 class="accordion-header">
                                        <button class="accordion-button {{ isset($expandedMenus[$item->id]) && $expandedMenus[$item->id] ? '' : 'collapsed' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseChild{{ $item->id }}"
                                                aria-expanded="{{ isset($expandedMenus[$item->id]) && $expandedMenus[$item->id] ? 'true' : 'false' }}"
                                                aria-controls="collapseChild{{ $item->id }}">
                                            <i class="{{ $item->icon }}"> </i> {{ $item->title }}
                                        </button>
                                    </h3>

                                    <div id="collapseChild{{ $item->id }}" class="accordion-collapse collapse text-bg-dark {{ isset($expandedMenus[$item->id]) && $expandedMenus[$item->id] ? 'show' : '' }}"data-bs-parent="#accordionChildMenu{{ $item->id }}">

                                        <div class="accordion-body">
                                            <!-- Parent menu item -->
                                            <a href="{{ $item->url }}" 
                                                class="d-block px-3 py-2 {{ request()->is(trim($item->url, '/')) ? 'text-primary fw-bold' : '' }}">
                                                 <i class="{{ $item->icon }}"> </i> {{ $item->title }}
                                             </a>
                                            <!-- Iterate through all children of the parent menu item -->
                                            @foreach ($item->children as $child)
                                                <a href="{{ $child->url }}" 
                                                   class="d-block px-3 py-2 {{ request()->is(trim($child->url, '/')) ? 'text-primary fw-bold' : '' }}">
                                                    <i class="{{ $child->icon }}"> </i> {{ $child->title }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @endforeach

                </div>
            </div>
        </div>

    @endforeach
</div>
