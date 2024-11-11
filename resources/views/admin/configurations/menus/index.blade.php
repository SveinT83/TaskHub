<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/Configurations/ConfigurationsController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Menu"></x-page-header>
@endsection

@section('content')
<div class="container mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Create new menu form and row -->
    <!-- If user has permission -->
    <!-- ------------------------------------------------- -->
    @can('superadmin.create')
        <div class="row">

                <!-- ------------------------------------------------- -->
                <!-- Create new menu form -->
                <!-- ------------------------------------------------- -->
                <form action="{{ route('menu.create') }}" method="POST">
                    @csrf

                    <!-- ------------------------------------------------- -->
                    <!-- Card - Create new menu -->
                    <!-- ------------------------------------------------- -->
                    <x-card-secondary title="Create New Menu">

                        <div class="form-group">
                            <label for="name">Menu Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description (optional)</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <div class="row mt-3 m-1">
                            <x-save-update-button type="submit" ico="bi bi-floppy">Create Menu</x-save-update-button>
                        </div>

                    </x-card-secondary>
                </form>
        </div>
    @endcan

    <!-- ------------------------------------------------- -->
    <!-- A list of existing menus -->
    <!-- ------------------------------------------------- -->
    <div class="row mt-2">
        <div class="col-12">

            <!-- ------------------------------------------------- -->
            <!-- Card - Menu list -->
            <!-- ------------------------------------------------- -->
            <x-card-secondary title="Existing Menus">

                <!-- If user has permission -->
                @if(auth()->user()->can('superadmin.view'))
                    <ul class="list-group">
                        @foreach($menus as $menu)
                            <li class="list-group-item">
                                <a href="{{ route('menu.items', $menu->id) }}">{{ $menu->name }}</a>
                            </li>
                        @endforeach
                    </ul>

                <!-- If user does not have permission -->
                @else
                    <p>You do not have permission to view menus.</p>
                @endif
            </x-card-secondary>

        </div>
    </div>
</div>
@endsection
