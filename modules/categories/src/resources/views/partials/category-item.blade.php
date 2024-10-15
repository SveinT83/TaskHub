<li class="list-group-item">
    <strong>{{ $category->name }}</strong>
    
    <!-- Knapp for sletting -->
    <button wire:click="deleteCategory({{ $category->id }})">Delete</button>

    @if($category->children->count())
        <ul>
            @foreach($category->children as $child)
                @include('categories::livewire.partials.category-item', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
