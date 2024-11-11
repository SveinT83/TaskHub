<!-- sidebar-menu.blade.php -->

<!-- ------------------------------------------------- -->
<!-- This is the sidebar menu that will be displayed on the left side of the screen. -->
<!-- ------------------------------------------------- -->
    <div class="accordion" id="accordionSideMenu" style="margin: 0px; padding: 0px; width: 100%;">

        <!-- ------------------------------------------------- -->
        <!-- For each menu item, create an accordion item. -->
        <!-- ------------------------------------------------- -->
        @foreach ($menus as $menu)

            <!-- ------------------------------------------------- -->
            <!-- Accordion item -->
            <!-- ------------------------------------------------- -->
            <div class="accordion-item">

                <!-- ------------------------------------------------- -->
                <!-- Accordion header -->
                <!-- ------------------------------------------------- -->
                <h2 class="accordion-header">
                    <button class="accordion-button {{ isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id] ? '' : 'collapsed' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $menu->id }}"
                            aria-expanded="{{ isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id] ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $menu->id }}">
                        {{ $menu->name }}
                    </button>
                </h2>

                <!-- ------------------------------------------------- -->
                <!-- Accordion Collapse Body -->
                <!-- ------------------------------------------------- -->
                <div id="collapse{{ $menu->id }}" 
                    class="accordion-collapse collapse {{ isset($expandedMenus[$menu->id]) && $expandedMenus[$menu->id] ? 'show' : '' }}" 
                    data-bs-parent="#accordionSideMenu">

                    <!-- ------------------------------------------------- -->
                    <!-- Accordion Body -->
                    <!-- ------------------------------------------------- -->
                    <div class="accordion-body" style="padding: 0px;">

                        <!-- ------------------------------------------------- -->
                        <!-- Accordion item for the menu item. -->
                        <!-- ------------------------------------------------- -->
                        <div class="accordion" id="accordionChildMenu" style="margin: 0px; padding: 0px;">

                            <!-- ------------------------------------------------- -->
                            <!-- For each child menu item, create an accordion item. -->
                            <!-- ------------------------------------------------- -->
                            @foreach ($menu->items as $item)

                                <!-- ------------------------------------------------- -->
                                <!-- If not a child, display the menu item. -->
                                <!-- ------------------------------------------------- -->
                                @if (!$item->children->isEmpty())

                                    <!-- ------------------------------------------------- -->
                                    <!-- Accordion item for the child menu item. -->
                                    <!-- ------------------------------------------------- -->
                                    <div class="accordion-item">

                                        <!-- ------------------------------------------------- -->
                                        <!-- Accordion header for the child menu item. -->
                                        <!-- ------------------------------------------------- -->
                                        <h3 class="accordion-header">
                                            <button class="accordion-button {{ isset($expandedMenus[$item->id]) && $expandedMenus[$item->id] ? '' : 'collapsed' }}"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseChild{{ $item->id }}"
                                                    aria-expanded="{{ isset($expandedMenus[$item->id]) && $expandedMenus[$item->id] ? 'true' : 'false' }}"
                                                    aria-controls="collapseChild{{ $item->id }}">
                                                {{ $item->title }}
                                            </button>
                                        </h3>

                                        <!-- ------------------------------------------------- -->
                                        <!-- Accordion Collapse Body for the child menu item. -->
                                        <!-- ------------------------------------------------- -->
                                        <div id="collapseChild{{ $item->id }}" 
                                            class="accordion-collapse collapse text-bg-dark {{ isset($expandedMenus[$item->id]) && $expandedMenus[$item->id] ? 'show' : '' }}"
                                            data-bs-parent="#accordionChildMenu">

                                            <!-- ------------------------------------------------- -->
                                            <!-- Accordion Body for the child menu item. -->
                                            <!-- ------------------------------------------------- -->
                                            <div class="accordion-body">


                                                <!-- ------------------------------------------------- -->
                                                <!-- ???? -->
                                                <!-- ------------------------------------------------- -->
                                                <div class="row {{ isset($expandedMenus[$item->id]) && $expandedMenus[$item->id] ? 'active' : '' }}">

                                                    <!-- ------------------------------------------------- -->
                                                    <!-- Item URL -->
                                                    <!-- ------------------------------------------------- -->
                                                    <a href="{{ $item->url }}" 
                                                        class="p-2 {{ request()->is(trim($item->url, '/')) ? 'text-primary fw-bold' : '' }}">
                                                        {{ $item->title }}
                                                    </a>

                                                    <!-- ------------------------------------------------- -->
                                                    <!-- Repeat the same logic for the children of the current menu item. -->
                                                    <!-- ------------------------------------------------- -->
                                                    @if ($item->children->isNotEmpty())
                                                        
                                                        @foreach ($item->children as $child)

                                                            <!-- ------------------------------------------------- -->
                                                            <!-- Child Item URL -->
                                                            <!-- ------------------------------------------------- -->
                                                            <a href="{{ $child->url }}" 
                                                                class="p-2 border-top {{ request()->is(trim($child->url, '/')) ? 'text-primary fw-bold' : '' }}">
                                                                {{ $child->title }}
                                                            </a>
                                                        @endforeach

                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
