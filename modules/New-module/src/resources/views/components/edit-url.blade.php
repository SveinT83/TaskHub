<a
    class="btn btn-sm {{ $class ?? 'btn-warning' }} bi bi-pencil"
    href="{{ $href }}"
    {{ $attributes }}
>
    {{ $slot->isEmpty() ? ' Edit' : $slot }}
</a>
