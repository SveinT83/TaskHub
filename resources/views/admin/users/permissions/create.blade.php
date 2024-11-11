<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/UserAndRoles/UserController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Permission"></x-page-header>
@endsection

@section('content')
<div class="container">

    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf

        <!-- ------------------------------------------------- -->
        <!-- Put the new user form in a card -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="New permission form">

            <!-- ------------------------------------------------- -->
            <!-- Name - Required -->
            <!-- ------------------------------------------------- -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Create user button -->
            <!-- ------------------------------------------------- -->
            <div class="row m-1">
                <!-- view/compoments/save-update-button.blade.php -->
                <x-save-update-button type="submit" ico="bi bi-floppy">Create Permission</x-save-update-button>
            </div>

        </x-card-secondary>

    </form>

</div>
@endsection
