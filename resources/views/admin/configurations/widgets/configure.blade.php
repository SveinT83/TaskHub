@extends('layouts.app')

@section('title', 'Configure Widgets')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">Configure Widgets</h1>
                    <div>
                        <select class="form-select d-inline-block w-auto me-2" id="routeSelector" onchange="changeRoute()">
                            @foreach($availableRoutes as $routeKey => $routeName)
                                <option value="{{ $routeKey }}" {{ $routeKey === $route ? 'selected' : '' }}>
                                    {{ $routeName }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.configurations.widgets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Available Widgets -->
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5><i class="fas fa-puzzle-piece me-2"></i>Available Widgets</h5>
                    </div>
                    <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                        @foreach($widgets as $category => $categoryWidgets)
                            <h6 class="text-muted border-bottom pb-2 mb-3">
                                <i class="fas fa-folder me-1"></i>{{ ucfirst($category) }}
                            </h6>
                            <div class="widget-list mb-4">
                                @foreach($categoryWidgets as $widget)
                                    <div class="widget-item p-3 border rounded mb-2 cursor-grab" 
                                         draggable="true" 
                                         data-widget-id="{{ $widget->id }}"
                                         data-widget-name="{{ $widget->name }}"
                                         data-widget-icon="{{ $widget->icon }}">
                                        <div class="d-flex align-items-center">
                                            @if($widget->icon)
                                                <i class="{{ $widget->icon }} me-3 text-primary"></i>
                                            @else
                                                <i class="fas fa-puzzle-piece me-3 text-muted"></i>
                                            @endif
                                            <div class="flex-grow-1">
                                                <strong>{{ $widget->name }}</strong>
                                                <small class="d-block text-muted">{{ $widget->description }}</small>
                                                <div class="mt-1">
                                                    <span class="badge bg-light text-dark">{{ $widget->module }}</span>
                                                    @if($widget->is_configurable)
                                                        <span class="badge bg-info">Config</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Widget Positions -->
            <div class="col-md-8">
                <div class="row">
                    @foreach($positionKeys as $positionKey => $positionName)
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-th-large me-2"></i>{{ $positionName }}
                                    </h6>
                                    <small class="text-muted">{{ $positionKey }}</small>
                                </div>
                                <div class="card-body position-drop-zone" 
                                     data-position="{{ $positionKey }}"
                                     style="min-height: 200px; background: #f8f9fa;">
                                    
                                    @if(isset($activeWidgets[$positionKey]) && $activeWidgets[$positionKey]->count() > 0)
                                        @foreach($activeWidgets[$positionKey] as $widgetPosition)
                                            <div class="active-widget p-3 border rounded mb-2 bg-white shadow-sm" 
                                                 data-position-id="{{ $widgetPosition->id }}"
                                                 draggable="true">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-2">
                                                            @if($widgetPosition->widget->icon)
                                                                <i class="{{ $widgetPosition->widget->icon }} me-2 text-primary"></i>
                                                            @endif
                                                            <strong>{{ $widgetPosition->widget->name }}</strong>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <select class="form-select form-select-sm w-auto" 
                                                                    onchange="updateWidgetSize({{ $widgetPosition->id }}, this.value)">
                                                                <option value="small" {{ $widgetPosition->size === 'small' ? 'selected' : '' }}>Small</option>
                                                                <option value="medium" {{ $widgetPosition->size === 'medium' ? 'selected' : '' }}>Medium</option>
                                                                <option value="large" {{ $widgetPosition->size === 'large' ? 'selected' : '' }}>Large</option>
                                                                <option value="full" {{ $widgetPosition->size === 'full' ? 'selected' : '' }}>Full</option>
                                                            </select>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       {{ $widgetPosition->is_active ? 'checked' : '' }}
                                                                       onchange="toggleWidget({{ $widgetPosition->id }}, this.checked)">
                                                                <label class="form-check-label">Active</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-1">
                                                        @if($widgetPosition->widget->is_configurable)
                                                            <button class="btn btn-sm btn-outline-primary"
                                                                    onclick="configureWidget({{ $widgetPosition->id }})">
                                                                <i class="fas fa-cog"></i>
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-sm btn-outline-secondary"
                                                                title="Flytt">
                                                            <i class="fas fa-grip-vertical"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger"
                                                                onclick="removeWidget({{ $widgetPosition->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-muted py-4 drop-placeholder">
                                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                            <p class="mb-0">Drag widgets here</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for widget-konfigurasjon -->
    <div class="modal fade" id="configModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfigurer Widget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="configContent">
                    <!-- Config content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <button type="button" class="btn btn-primary" onclick="saveWidgetConfig()">Lagre</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .cursor-grab { cursor: grab; }
        .cursor-grab:active { cursor: grabbing; }
        .position-drop-zone { 
            border: 2px dashed #dee2e6; 
            transition: all 0.3s ease;
        }
        .position-drop-zone.drag-over { 
            border-color: #0d6efd; 
            background-color: #e7f3ff !important; 
        }
        .widget-item:hover { background-color: #f8f9fa; }
        .active-widget { 
            transition: all 0.3s ease;
            cursor: move;
        }
        .active-widget:hover { transform: translateY(-2px); }
        .drop-placeholder { 
            pointer-events: none; 
            transition: opacity 0.3s ease;
        }
        .position-drop-zone:not(:empty) .drop-placeholder { display: none; }
    </style>
    @endpush

    @push('scripts')
    <script>
        let currentRoute = '{{ $route }}';
        let draggedWidget = null;
        let draggedPosition = null;

        document.addEventListener('DOMContentLoaded', function() {
            setupDragAndDrop();
        });

        function changeRoute() {
            const route = document.getElementById('routeSelector').value;
            window.location.href = `{{ route('admin.configurations.widgets.configure') }}?route=${route}`;
        }

        function setupDragAndDrop() {
            // Drag fra tilgjengelige widgets
            document.querySelectorAll('.widget-item[draggable="true"]').forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    draggedWidget = {
                        id: this.dataset.widgetId,
                        name: this.dataset.widgetName,
                        icon: this.dataset.widgetIcon,
                        type: 'new'
                    };
                    e.dataTransfer.effectAllowed = 'copy';
                });
            });

            // Drag fra aktive widgets
            document.querySelectorAll('.active-widget[draggable="true"]').forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    draggedPosition = {
                        id: this.dataset.positionId,
                        element: this,
                        type: 'move'
                    };
                    e.dataTransfer.effectAllowed = 'move';
                });
            });

            // Drop zones
            document.querySelectorAll('.position-drop-zone').forEach(zone => {
                zone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = draggedWidget ? 'copy' : 'move';
                    this.classList.add('drag-over');
                });

                zone.addEventListener('dragleave', function(e) {
                    if (!this.contains(e.relatedTarget)) {
                        this.classList.remove('drag-over');
                    }
                });

                zone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');
                    
                    const position = this.dataset.position;
                    
                    if (draggedWidget && draggedWidget.type === 'new') {
                        addWidgetToPosition(draggedWidget.id, position);
                    } else if (draggedPosition && draggedPosition.type === 'move') {
                        moveWidgetToPosition(draggedPosition.id, position);
                    }
                    
                    draggedWidget = null;
                    draggedPosition = null;
                });
            });
        }

        function addWidgetToPosition(widgetId, position) {
            const size = prompt('Choose size (small/medium/large/full):', 'medium');
            if (!size || !['small', 'medium', 'large', 'full'].includes(size)) {
                return;
            }

            fetch('{{ route("admin.configurations.widgets.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    widget_id: widgetId,
                    route: currentRoute,
                    position_key: position,
                    size: size
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error adding widget');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding widget');
            });
        }

        function removeWidget(positionId) {
            if (confirm('Are you sure you want to remove this widget?')) {
                fetch(`/admin/configurations/widgets/positions/${positionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error removing widget');
                    }
                });
            }
        }

        function updateWidgetSize(positionId, size) {
            fetch(`/admin/configurations/widgets/positions/${positionId}/settings`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    size: size,
                    is_active: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Error updating widget size');
                    location.reload();
                }
            });
        }

        function toggleWidget(positionId, isActive) {
            fetch(`/admin/configurations/widgets/positions/${positionId}/settings`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    is_active: isActive,
                    size: 'medium' // Keep existing size
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Error toggling widget status');
                    location.reload();
                }
            });
        }

        function configureWidget(positionId) {
            // TODO: Implement widget configuration
            alert('Widget configuration coming soon!');
        }
    </script>
    @endpush
@endsection
