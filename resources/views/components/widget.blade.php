@props([
    'title' => null,
    'size' => 'medium',
    'variant' => '',
    'bodyClass' => '',
    'headerClass' => '',
    'footerClass' => '',
    'actions' => null,
    'widget' => null,
    'configurable' => false
])

@php
    $sizeClasses = [
        'small' => 'col-lg-3 col-md-6 col-sm-12',
        'medium' => 'col-lg-6 col-md-12',
        'large' => 'col-lg-9 col-md-12',
        'full' => 'col-12'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['medium'];
    
    $cardClasses = ['card', 'h-100', 'shadow-sm'];
    
    if ($variant) {
        $cardClasses[] = $variant;
    } else {
        $cardClasses[] = 'border-start border-primary border-4';
    }
@endphp

<div class="{{ $sizeClass }} mb-4">
    <div class="{{ implode(' ', $cardClasses) }}">
        @if($title || $actions || $configurable)
            <div class="card-header d-flex justify-content-between align-items-center {{ $headerClass }}">
                @if($title)
                    <h5 class="mb-0 card-title">{{ $title }}</h5>
                @endif
                
                <div class="widget-actions d-flex gap-1">
                    {{ $actions }}
                    
                    @if($configurable && $widget)
                        <button class="btn btn-sm btn-outline-secondary" 
                                onclick="configureWidget({{ $widget->id }})"
                                title="Konfigurer widget">
                            <i class="fas fa-cog"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endif
        
        <div class="card-body {{ $bodyClass }}">
            {{ $slot }}
        </div>
        
        @isset($footer)
            <div class="card-footer {{ $footerClass }}">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
