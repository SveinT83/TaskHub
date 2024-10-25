<<<<<<< Updated upstream
<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>
=======
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
>>>>>>> Stashed changes
