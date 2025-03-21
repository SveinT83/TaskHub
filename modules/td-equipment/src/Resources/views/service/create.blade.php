
@extends('layouts.app')
@section('content')

<!-- ------------------------------------------------- -->
<!-- Page header -->
<!-- ------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="Legg til servicehistorikk"></x-page-header>
@endsection

<!-- ------------------------------------------------- -->
<!-- Container -->
<!-- ------------------------------------------------- -->
<div class="container mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Card - Add Service History -->
    <!-- ------------------------------------------------- -->
    <x-card-secondary title="Legg til servicehistorikk for {{ $equipment->name }}">

        <form action="{{ route('equipment.service.store', $equipment->id) }}" method="POST">
            @csrf

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="description">Beskrivelse</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="service_date">Service Dato</label>
                        <input type="date" name="service_date" id="service_date" class="form-control" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Lagre</button>
            <a href="{{ route('equipment.show', $equipment->id) }}" class="btn btn-secondary mt-3">Tilbake</a>
        </form>

    <!-- ------------------------------------------------- -->
    <!-- End Add Service History Card -->
    <!-- ------------------------------------------------- -->
    </x-card-secondary>

</div>
@endsection
