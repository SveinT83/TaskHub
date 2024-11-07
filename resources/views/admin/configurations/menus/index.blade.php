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

    <!-- Formular for å opprette en ny meny -->
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
                    <button type="submit" class="btn btn-primary mt-3">Create Menu</button>

                </x-card-secondary>
            </form>
    </div>

    <!-- ------------------------------------------------- -->
    <!-- A list of existing menus -->
    <!-- ------------------------------------------------- -->
    <div class="row mt-2">
        <div class="col-12">

            <!-- ------------------------------------------------- -->
            <!-- Card - Menu list -->
            <!-- ------------------------------------------------- -->
            <x-card-secondary title="Existing Menus">
                <ul class="list-group">
                    @foreach($menus as $menu)
                        <li class="list-group-item">
                            <a href="{{ route('menu.items', $menu->id) }}">{{ $menu->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </x-card-secondary>

        </div>
    </div>
</div>
@endsection
