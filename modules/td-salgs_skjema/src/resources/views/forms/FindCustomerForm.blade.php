<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- modules/td-salgs_skjema/src/Controllers/TdsalgsskjemaController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="TD Salgs Skjema"></x-page-header>
@endsection

@section('content')

        <!-- ------------------------------------------------- -->
        <!-- Card - Form -->
        <!-- ------------------------------------------------- -->
        <livewire:FindCustomerForm />


@endsection
