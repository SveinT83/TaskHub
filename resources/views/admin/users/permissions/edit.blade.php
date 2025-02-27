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

    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- ------------------------------------------------- -->
        <!-- Put the new user form in a card -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="Edit permission">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name }}" required>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Create user button -->
            <!-- ------------------------------------------------- -->
            <div class="row m-1">
                <!-- view/compoments/save-update-button.blade.php -->
                <x-save-update-button type="submit">Update</x-save-update-button>
            </div>

        </x-card-secondary>
    </form>
</div>
@endsection
