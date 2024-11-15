<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- modules/td-salgs_skjema/src/Controllers/Admin/UserAndRoles/UserController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="TD Salgs Skjema - Nytt salg"></x-page-header>
@endsection

@section('content')

        <!-- ------------------------------------------------- -->
        <!-- Card - Search Form -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="Kontaktinformasjon">
            <livewire:FindCustomerForm />
        </x-card-secondary>

@endsection
