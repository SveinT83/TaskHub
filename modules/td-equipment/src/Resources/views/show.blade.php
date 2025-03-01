<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- Http/Controllers/EquipmentController.php -->
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

    <!-- ------------------------------------------------- -->
    <!-- Card -->
    <!-- ------------------------------------------------- -->
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

        <a href="{{ route('equipment.edit', $equipment->id) }}" class="btn btn-warning">Rediger</a>
        <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Tilbake</a>
        <form action="{{ route('equipment.destroy', $equipment->id) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">Slett</button>
        </form>

    <!-- ------------------------------------------------- -->
    <!-- End Card -->
    <!-- ------------------------------------------------- -->
    </x-card-secondary>

</div>
@endsection
