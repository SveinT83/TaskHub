@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Integrations</h1>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($integrations as $integration)
                        <tr>
                            <td>
                                @if($integration->icon)
                                    <i class="{{ $integration->icon }}" style="font-size: 1.2em;"></i>
                                @else
                                    <i class="bi bi-gear" style="font-size: 1.2em;"></i>
                                @endif
                            </td>
                            <td>
                                <strong>{{ ucfirst($integration->name) }}</strong>
                            </td>
                            <td>
                                @if($integration->active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $integration->created_at ? $integration->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>{{ $integration->updated_at ? $integration->updated_at->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if ($integration->active)
                                        <form action="{{ route('admin.integrations.deactivate', $integration->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" 
                                                    onclick="return confirm('Are you sure you want to deactivate this integration?')">
                                                <i class="bi bi-pause-circle"></i> Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.integrations.activate', $integration->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-play-circle"></i> Activate
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.integration.' . strtolower($integration->name)) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-gear"></i> Configure
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection