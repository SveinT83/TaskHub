<div class="alert alert-danger">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <div>
            <strong>Widget Error: {{ $widget->name ?? 'Unknown Widget' }}</strong>
            @if(app()->environment('local') || auth()->user()?->can('admin.configurations.widgets.debug'))
                <small class="d-block text-muted mt-1">{{ $error }}</small>
            @else
                <small class="d-block text-muted mt-1">Widget could not be loaded.</small>
            @endif
        </div>
    </div>
</div>
