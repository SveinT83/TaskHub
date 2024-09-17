@extends('layouts.app')

@section('pageHeader')
    <h1>{{ $module->name }} Module</h1>
@endsection

@section('content')
<div class="container mt-3">
    <p>{{ $module->description }}</p>

    <h2>Module Menu</h2>
    @if(isset($menu['module_menu']))
        <p><strong>Menu Name:</strong> {{ $menu['module_menu']['name'] }}</p>
        <p><strong>Route:</strong> {{ $menu['module_menu']['route'] }}</p>
        <p><strong>Icon:</strong> <i class="{{ $menu['module_menu']['icon'] }}"></i></p>
    @endif

    <h2>Admin Menu Items</h2>
    @if(isset($menu['admin_menu_items']))
        <ul>
            @foreach ($menu['admin_menu_items'] as $item)
                <li><strong>{{ $item['name'] }}</strong> (Route: {{ $item['route'] }})</li>
            @endforeach
        </ul>
    @endif

    <h2>Database Migrations</h2>
    @if (count($migrationFiles) > 0)
        <ul>
            @foreach ($migrationFiles as $file)
                <li>{{ $file->getFilename() }}</li>
            @endforeach
        </ul>
        <form action="{{ route('modules.runMigrations', $module->slug) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Run Migrations</button>
        </form>
    @else
        <p>No migrations found for this module.</p>
    @endif

    <h2>Database Seed</h2>
    <form action="{{ route('modules.runSeed', $module->slug) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Run Seed</button>
    </form>
</div>
@endsection
