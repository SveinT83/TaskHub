<!-- partials/category-option.blade.php -->
<option value="{{ $category->id }}">{{ str_repeat('--', $level) }} {{ $category->name }}</option>

@if ($category->children && $category->children->count() > 0)
    @foreach ($category->children as $child)
        @include('kbartickles::partials.category-option', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif
