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

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info bi bi-building" role="alert">
                    Søk etter eksistenende kunde eller opprett en ny kunde.
                </div>                  
            </div>
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Card - Form -->
        <!-- ------------------------------------------------- -->
        <x-card-secondary title="Finn kunde:">
            <livewire:FindCustomerForm />
        </x-card>

        <!-- ------------------------------------------------- -->
        <!-- register new customer bUTTON -->
        <!-- ------------------------------------------------- -->
        <div class="row mt-3 justify-content-center">
            <div class="col-md-3">
                <div class="row m-1">
                    <a class="btn btn-outline-primary bi bi-building-add" href="{{ route('tdsalgsskjema.RegisterNewCustomer') }}"> Registrer ny kunde</a>
                </div>
            </div>
        </div>


@endsection
