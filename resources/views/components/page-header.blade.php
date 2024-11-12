<div class="row align-items-center justify-content-between">
    <div class="col-md-4 mt-1">
        @isset($pageHeaderTitle)
            <h1>{{ $pageHeaderTitle }}</h1>
        @endisset
    </div>
    
    {{ $slot }}
    
</div>