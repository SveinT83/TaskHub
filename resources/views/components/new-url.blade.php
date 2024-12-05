<a
    class="btn btn-sm {{ $class ?? 'btn-success' }} bi bi-plus"
    href="{{ $href }}"
    {{ $attributes }}
>
    {{ $slot->isEmpty() ? ' New' : $slot }}
</a>
