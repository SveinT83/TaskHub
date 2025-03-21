<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="sidebar">
        <h2>System Navigation</h2>
        <ul>
            <li><a href="{{ route('core.dashboard') }}">Home</a></li>

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('technician'))
                <li><a href="{{ route('projects.index') }}">Projects</a></li>
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('technician'))
                <li><a href="{{ route('inventory.index') }}">Inventory</a></li>
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('economy'))
                <li><a href="{{ route('invoicing.index') }}">Invoices</a></li>
            @endif

            @if(auth()->user()->hasRole('admin'))
                <li><a href="{{ route('auditlogs.index') }}">Audit Logs</a></li>
            @endif
        </ul>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>