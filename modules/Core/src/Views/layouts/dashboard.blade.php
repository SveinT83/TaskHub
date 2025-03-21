@extends('core::layouts.app')

@section('content')
<h1>System Dashboard</h1>
<p>Select a module below:</p>

<div class="module-links">
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('technician'))
        <a href="{{ route('projects.index') }}" class="module-button">Manage Projects</a>
    @endif

    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('technician'))
        <a href="{{ route('inventory.index') }}" class="module-button">Manage Inventory</a>
    @endif

    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('economy'))
        <a href="{{ route('invoicing.index') }}" class="module-button">Manage Invoices</a>
    @endif

    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('auditlogs.index') }}" class="module-button">View Audit Logs</a>
    @endif
</div>
@endsection