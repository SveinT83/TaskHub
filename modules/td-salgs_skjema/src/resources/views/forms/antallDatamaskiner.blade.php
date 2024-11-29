<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- modules/td-salgs_skjema/src/Controllers/TdsalgsskjemaController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="TD Salgs Skjema - Datamaskiner">
        <livewire:Price_card />
    </x-page-header>
@endsection

@section('content')

        <!-- ------------------------------------------------- -->
        <!-- Card - Form -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="Antall Datamaskiner">
            <livewire:antallDatamaskiner />
        </x-card>


@endsection