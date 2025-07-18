@extends('layouts.app')

@section('title', 'Widget Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Manage Widgets</h5>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="widgetTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="positions-tab" data-bs-toggle="tab" data-bs-target="#positions" type="button" role="tab" aria-controls="positions" aria-selected="true">Widget Positions</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available" type="button" role="tab" aria-controls="available" aria-selected="false">Available Widgets</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="widgetTabsContent">
                        <!-- Current Widget Positions -->
                        <div class="tab-pane fade show active" id="positions" role="tabpanel" aria-labelledby="positions-tab">
                            <div class="mt-4">
                                <h5>Current Widget Positions</h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Widget</th>
                                                <th>Position</th>
                                                <th>Route</th>
                                                <th>Size</th>
                                                <th>Order</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="widget-positions-table">
                                            @forelse($currentWidgets as $position)
                                                <tr data-position-id="{{ $position->id }}">
                                                    <td>
                                                        @if($position->widget)
                                                            <strong>{{ $position->widget->name }}</strong>
                                                            @if(!empty($position->widget->description))
                                                                <small class="d-block text-muted">{{ $position->widget->description }}</small>
                                                            @endif
                                                        @else
                                                            <span class="text-danger">Widget not found</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $positions[$position->position_key] ?? $position->position_key }}</td>
                                                    <td>
                                                        @if($position->route == '*')
                                                            <span class="badge bg-info">All Routes</span>
                                                        @else
                                                            <code>{{ $position->route }}</code>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $sizeClasses = [
                                                                'small' => 'bg-info',
                                                                'medium' => 'bg-success',
                                                                'large' => 'bg-warning',
                                                                'full' => 'bg-danger'
                                                            ];
                                                        @endphp
                                                        <span class="badge {{ $sizeClasses[$position->size] ?? 'bg-secondary' }}">
                                                            {{ ucfirst($position->size) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="order-handle badge bg-secondary">
                                                            {{ $position->sort_order }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($position->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <form action="{{ route('admin.configurations.widgets.position.toggle', $position->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm {{ $position->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $position->is_active ? 'Deactivate' : 'Activate' }}">
                                                                    <i class="fas fa-{{ $position->is_active ? 'pause' : 'play' }} me-1"></i>
                                                                    {{ $position->is_active ? 'Disable' : 'Enable' }}
                                                                </button>
                                                            </form>
                                                            
                                                            @if($position->widget && $position->widget->is_configurable)
                                                                <button type="button" class="btn btn-sm btn-primary widget-settings-btn ms-1" data-bs-toggle="modal" data-bs-target="#widgetSettingsModal" data-widget-position="{{ $position->id }}" data-widget-name="{{ $position->widget->name }}" title="Configure widget">
                                                                    <i class="fas fa-cog me-1"></i>
                                                                    Settings
                                                                </button>
                                                            @endif
                                                            
                                                            <form action="{{ route('admin.configurations.widgets.position.remove', $position->id) }}" method="POST" class="d-inline delete-confirm">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger ms-1" title="Remove widget">
                                                                    <i class="fas fa-trash me-1"></i>
                                                                    Remove
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No widgets have been positioned yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Available Widgets -->
                        <div class="tab-pane fade" id="available" role="tabpanel" aria-labelledby="available-tab">
                            <div class="mt-4">
                                <h5>Available Widgets</h5>
                                
                                <div class="row">
                                    @forelse($widgets as $widget)
                                        <div class="col-md-6 col-xl-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        @if($widget->icon)
                                                            <i class="{{ $widget->icon }} me-2"></i>
                                                        @endif
                                                        {{ $widget->name }}
                                                    </h5>
                                                    
                                                    @if($widget->description)
                                                        <p class="card-text">{{ $widget->description }}</p>
                                                    @endif
                                                    
                                                    <dl class="row mb-0">
                                                        <dt class="col-sm-4">Module:</dt>
                                                        <dd class="col-sm-8">{{ $widget->module ?: 'Core' }}</dd>
                                                        
                                                        @if($widget->category)
                                                            <dt class="col-sm-4">Category:</dt>
                                                            <dd class="col-sm-8">{{ $widget->category }}</dd>
                                                        @endif
                                                        
                                                        <dt class="col-sm-4">Auth Required:</dt>
                                                        <dd class="col-sm-8">
                                                            @if($widget->requires_auth)
                                                                <span class="badge bg-warning">Yes</span>
                                                            @else
                                                                <span class="badge bg-success">No</span>
                                                            @endif
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="button" class="btn btn-sm btn-primary add-widget-btn" data-bs-toggle="modal" data-bs-target="#addWidgetModal" data-widget-id="{{ $widget->id }}" data-widget-name="{{ $widget->name }}">
                                                        <i class="fas fa-plus me-1"></i> Add to Position
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                No available widgets found.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Widget Modal -->
<div class="modal fade" id="addWidgetModal" tabindex="-1" aria-labelledby="addWidgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.configurations.widgets.position.add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addWidgetModalLabel">Add Widget to Position</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="widget_id" id="widget_id">
                    
                    <div class="mb-3">
                        <label for="widget_name" class="form-label">Widget</label>
                        <input type="text" class="form-control" id="widget_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="position_key" class="form-label">Position</label>
                        <select name="position_key" id="position_key" class="form-select" required>
                            <option value="">Select a position</option>
                            @foreach($positions as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="route" class="form-label">Route (leave blank for all routes)</label>
                        <input type="text" class="form-control" id="route" name="route" placeholder="e.g., dashboard or * for all routes">
                        <small class="form-text text-muted">Use route names like 'dashboard' or '*' for all routes</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="size" class="form-label">Size</label>
                        <select name="size" id="size" class="form-select" required>
                            <option value="small">Small (25%)</option>
                            <option value="medium" selected>Medium (50%)</option>
                            <option value="large">Large (75%)</option>
                            <option value="full">Full Width (100%)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Widget</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Widget Settings Modal -->
<div class="modal fade" id="widgetSettingsModal" tabindex="-1" aria-labelledby="widgetSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="widgetSettingsForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="widgetSettingsModalLabel">Widget Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="widgetSettingsContent">
                        <p class="text-center">
                            <i class="fas fa-spinner fa-pulse fa-2x"></i><br>
                            Loading settings...
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle "Add Widget" button
        document.querySelectorAll('.add-widget-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const widgetId = this.getAttribute('data-widget-id');
                const widgetName = this.getAttribute('data-widget-name');
                
                document.getElementById('widget_id').value = widgetId;
                document.getElementById('widget_name').value = widgetName;
            });
        });
        
        // Handle "Widget Settings" button
        document.querySelectorAll('.widget-settings-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const positionId = this.getAttribute('data-widget-position');
                const widgetName = this.getAttribute('data-widget-name');
                
                document.getElementById('widgetSettingsModalLabel').textContent = `${widgetName} Settings`;
                
                // Set up the form action URL
                const form = document.getElementById('widgetSettingsForm');
                form.action = `/admin/configurations/widgets/position/${positionId}/settings`;
                
                // You would load settings via AJAX here
                // For now, we'll just show a placeholder
                document.getElementById('widgetSettingsContent').innerHTML = `
                    <div class="alert alert-info">
                        Widget settings would be loaded here via AJAX.
                        Settings are specific to each widget implementation.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Example Setting</label>
                        <input type="text" class="form-control" name="settings[example]" value="">
                    </div>
                `;
            });
        });
        
        // Confirm before deleting
        document.querySelectorAll('.delete-confirm').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to remove this widget from this position?')) {
                    e.preventDefault();
                }
            });
        });
        
        // Drag and drop reordering (would require additional JS library)
        // This is just a placeholder - implement with Sortable.js or similar
        console.log('Widget reordering would be implemented with drag-and-drop library');
    });
</script>
@endsection
