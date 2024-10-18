<!-- partials/category-item.blade.php -->
<a href="#" wire:click.prevent="editCategory({{ $category->id }})">{{ $category->name }}</a>

<!-- Hvis denne kategorien har child-kategorier, vis dem rekursivt -->
@if ($category->children && $category->children->count() > 0)
    @foreach ($category->children as $child)
        <div class="row mt-2">
            <div class="col ms-3 bi bi-arrow-return-right">
                @include('categories::partials.category-item', ['category' => $child])
            </div>
        </div>
    @endforeach
@endif
