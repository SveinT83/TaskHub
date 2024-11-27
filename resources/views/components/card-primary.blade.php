<div class="card text-bg-primary">
    <div class="card-header">
        {{ $title }}
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
    @isset($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endisset
</div>