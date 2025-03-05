<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- Http/Controllers/EquipmentController.php -->
<!-- Http/Controllers/EquipmentServiceController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')
@section('content')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Equipment"></x-page-header>
@endsection


<!-- ------------------------------------------------- -->
<!-- Container -->
<!-- ------------------------------------------------- -->
<div class="container mt-3">

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card - Equipment -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <x-card-secondary title="{{ $equipment->name }}">

        <div class="row">
            <div class="col">
                <p><strong>Kategori:</strong> {{ $equipment->category->name ?? 'Ingen kategori' }}</p>
            </div>
            <div class="col">
                <p><strong>Serienummer:</strong> {{ $equipment->serial_number }}</p>
            </div>
            <div class="col">
                <p><strong>Status:</strong> {{ ucfirst($equipment->status) }}</p>
            </div>
            <div class="col">
                <p><strong>Sertifiseringsmåned:</strong> {{ $equipment->certification_month ?? 'Ikke satt' }}</p>
            </div>
        </div>
                
        <div class="row mt-3">
            <div class="col">
                <strong>Beskrivelse:</strong>
                <p>{{ $equipment->description ?? 'Ingen beskrivelse' }}</p>
            </div>
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Buttons Row -->
        <!-- ------------------------------------------------- -->
        <div class="row mt-3 justify-content-between">

            <div class="col-md-2">
                <a href="{{ route('equipment.index') }}" class="btn btn-secondary bi bi-backspace"> Back</a>
            </div>

             <div class="col-md-2">
                @canany(['equipment.edit', 'equipment.admin', 'superadmin.edit'])
                <a href="{{ route('equipment.edit', $equipment->id) }}" class="btn btn-warning bi bi-pencil-square"> Edit</a>
                @endcanany

                @canany(['equipment.delete', 'equipment.admin', 'superadmin.delete'])
                <form action="{{ route('equipment.destroy', $equipment->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger bi bi-x-circle"> Delete</button>
                </form>
                @endcanany
            </div>
        </div>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- End Equipment Card -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    </x-card-secondary>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card - Service -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <x-card-secondary title="Services count: {{ $historyCount }}">

        <!-- ------------------------------------------------- -->
        <!-- Service History List Row -->
        <!-- ------------------------------------------------- -->
        <div class="row mt-3">
                <!-- Display the service history -->
                @if($serviceHistory->isEmpty())
                    <p>No service history available.</p>
                @else
                    <ul class="list-group">

                        <!-- ------------------------------------------------- -->
                        <!-- Fore each service iten -->
                        <!-- ------------------------------------------------- -->
                        @foreach($serviceHistory as $history)
                            <li class="list-group-item">
                                <div class="row justify-content-between">
                                    
                                    <!-- ------------------------------------------------- -->
                                    <!-- Service Description-->
                                    <!-- ------------------------------------------------- -->
                                    <p class="col-md-8">{{ $history->description }}</p>
                                    
                                    <!-- ------------------------------------------------- -->
                                    <!-- Date and delete button -->
                                    <!-- ------------------------------------------------- -->
                                    <div class="col-md-3">
                                        <div class="row justify-content-end">
                                            <i class="bi col-md-6 bi-calendar-check"> {{ $history->service_date }}</i>
                                            
                                            @canany(['equipment.edit', 'equipment.admin', 'superadmin.edit'])
                                            <form action="{{ route('equipment.service.destroy', $history->id) }}" method="POST" class="col-md-5">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm bi bi-x-circle"> </button>
                                            </form>
                                            @endcanany
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Buttons Row -->
        <!-- ------------------------------------------------- -->
        <div class="row mt-3 justify-content-between">
            <div class="col-md-2">
                @canany(['equipment.create', 'equipment.admin', 'superadmin.create'])
                <a href="{{ route('equipment.service.create', $equipment->id) }}" class="btn btn-primary bi bi-plus"> Add</a>
                @endcanany
            </div>
        </div>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- End Service Card -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    </x-card-secondary>

</div>
@endsection
