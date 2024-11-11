<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/Configurations/ConfigurationsController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Menu Items for: {{ $menu->name }}"></x-page-header>
@endsection

@section('content')
<div class="container mt-3">

    <div class="row">
        <div class="col-12">

            <!-- ------------------------------------------------- -->
            <!-- Card -Show a list of existing menu items -->
            <!-- ------------------------------------------------- -->
            <x-card-secondary title="Existing Menu Items">
                <ul class="list-group">

                    <!-- ------------------------------------------------- -->
                    <!-- Loop through all menu items -->
                    <!-- ------------------------------------------------- -->
                    @foreach($menuItems as $item)
                        <li class="list-group-item">
                            {{ $item->title }} (URL: {{ $item->url }})

                            <!-- ------------------------------------------------- -->
                            <!-- Edit button if user has permission -->
                            <!-- ------------------------------------------------- -->
                            @can('superadmin.edit')
                                <!-- view/compoments/new-url.blade.php -->
                                <x-edit-url href="{{ route('menu.items.edit', [$menu->id, $item->id]) }}"></x-edit-url>
                            @endcan
                            
                        </li>
                    @endforeach
                </ul>
            </x-card-secondary>

        </div>
    </div>

    <div class="row mt-3">

        <!-- ------------------------------------------------- -->
        <!-- Form to create a new menu item If user has permission -->
        <!-- ------------------------------------------------- -->
        @can('superadmin.create')
            <form action="{{ route('menu.items.create', $menu->id) }}" method="POST">
                @csrf

                <!-- ------------------------------------------------- -->
                <!-- Card - Create new menu item -->
                <!-- ------------------------------------------------- -->
                <x-card-secondary title="Add New Menu Item">

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input type="text" name="url" class="form-control" required>
                    </div>

                    <!-- Parent menu item -->
                    <div class="form-group">
                        <label for="parent_id">Parent Menu Item (optional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">None</option>

                            <!-- ------------------------------------------------- -->
                            <!-- Loop through all menu items -->
                            <!-- ------------------------------------------------- -->
                            @foreach($menuItems as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order -->
                    <div class="form-group">
                        <label for="order">Order (optional)</label>
                        <input type="number" name="order" class="form-control">
                    </div>

                    <div class="row m-1">
                        <!-- ------------------------------------------------- -->
                        <!-- Button to submit the form -->
                        <!-- ------------------------------------------------- -->
                        <!-- view/compoments/save-update-button.blade.php -->
                        <x-save-update-button type="submit" ico="bi bi-floppy"> Create</x-save-update-button>
                    </div>
                    
                </x-card-secondary>
            </form>
        @endcan
    </div>
</div>
@endsection
