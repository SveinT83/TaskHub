<div class="container-fluid">
    <!-- Loading overlay -->
    @if($loading)
    <div class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex justify-content-center align-items-center" style="z-index: 9999;">
        <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="text-white">Scanning modules...</div>
        </div>
    </div>
    @endif

    <!-- Flash messages -->
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Total Modules</h5>
                            <h3>{{ $modules->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cubes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Enabled</h5>
                            <h3>{{ $modules->where('enabled', true)->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Disabled</h5>
                            <h3>{{ $modules->where('enabled', false)->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <button wire:click="rescan" class="btn btn-light btn-sm" wire:loading.attr="disabled">
                        <i class="fas fa-sync-alt" wire:loading.class="fa-spin"></i>
                        Rescan Modules
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Module Management</h5>
        </div>
        <div class="card-body">
            @if($modules->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-cube fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No modules found</h5>
                    <p class="text-muted">Click "Rescan Modules" to scan for modules in the filesystem.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Version</th>
                                <th>Description</th>
                                <th>Path</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                            <tr class="{{ !$module->enabled ? 'table-warning' : '' }}">
                                <td>
                                    <button wire:click="showModuleDetails('{{ $module->slug }}')" 
                                            class="btn btn-link text-start p-0 text-decoration-none">
                                        <strong>{{ $module->name }}</strong>
                                    </button>
                                </td>
                                <td>
                                    <code>{{ $module->slug }}</code>
                                </td>
                                <td>
                                    @if($module->version)
                                        <span class="badge bg-secondary">{{ $module->version }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $module->description ?? 'No description' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted font-monospace">{{ basename($module->path) }}</small>
                                </td>
                                <td>
                                    @if($module->enabled)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Enabled
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-times me-1"></i>Disabled
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button 
                                            wire:click="toggleModule('{{ $module->slug }}')" 
                                            class="btn btn-outline-{{ $module->enabled ? 'warning' : 'success' }}"
                                            title="{{ $module->enabled ? 'Disable' : 'Enable' }} module"
                                        >
                                            <i class="fas fa-{{ $module->enabled ? 'pause' : 'play' }}"></i>
                                        </button>
                                        
                                        <button 
                                            wire:click="deleteModule('{{ $module->slug }}')" 
                                            wire:confirm="Are you sure you want to delete this module from the database?"
                                            class="btn btn-outline-danger"
                                            title="Delete from database"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>


    <!-- Module Details Modal -->
    @if($selectedModule)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-cube me-2"></i>{{ $selectedModule->name }}
                    </h5>
                    <button wire:click="closeModal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Description</h6>
                            <p>{{ $selectedModule->description ?? 'No description available' }}</p>
                            
                            <h6>Module Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Slug:</strong></td>
                                    <td><code>{{ $selectedModule->slug }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Version:</strong></td>
                                    <td>
                                        @if($selectedModule->version)
                                            <span class="badge bg-secondary">{{ $selectedModule->version }}</span>
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Path:</strong></td>
                                    <td><code class="small">{{ $selectedModule->path }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $selectedModule->enabled ? 'success' : 'warning' }}">
                                            <i class="fas fa-{{ $selectedModule->enabled ? 'check' : 'times' }} me-1"></i>
                                            {{ $selectedModule->enabled ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $selectedModule->updated_at?->diffForHumans() ?? 'Unknown' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <h6>Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <button wire:click="toggleModule('{{ $selectedModule->slug }}')" 
                                        class="btn btn-{{ $selectedModule->enabled ? 'warning' : 'success' }}">
                                    <i class="fas fa-{{ $selectedModule->enabled ? 'pause' : 'play' }} me-2"></i>
                                    {{ $selectedModule->enabled ? 'Disable' : 'Enable' }} Module
                                </button>
                                
                                <button wire:click="deleteModule('{{ $selectedModule->slug }}')" 
                                        wire:confirm="Are you sure you want to delete this module from the database?"
                                        class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Delete from Database
                                </button>
                                
                                <hr class="my-2">
                                
                                <button wire:click="closeModal" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
