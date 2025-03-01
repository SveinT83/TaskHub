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
    <x-card-secondary title="Edit: {{ $equipment->name }}">

        <form action="{{ route('equipment.update', $equipment->id) }}" method="POST">
            @csrf @method('PUT')

            <!-- ------------------------------------------------- -->
            <!-- Row - Name, Serial number and Status -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">

                <!-- Name -->
                <div class="col-md-4 md-3">
                    <label class="form-label">Navn</label>
                    <input type="text" name="name" value="{{ $equipment->name }}" class="form-control" required>
                </div>

                <!-- SN -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Serienummer</label>
                    <input type="text" name="serial_number" value="{{ $equipment->serial_number }}" class="form-control" required>
                </div>

                <!-- Status -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" @if($equipment->status == 'active') selected @endif>Aktiv</option>
                        <option value="inactive" @if($equipment->status == 'inactive') selected @endif>Ikke i bruk</option>
                        <option value="needs_certification" @if($equipment->status == 'needs_certification') selected @endif>
                            Må sertifiseres
                        </option>
                    </select>
                </div>

            </div>

            <!-- ------------------------------------------------- -->
            <!-- Row - vendor, Category and sertificate month -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">

                <!-- vendor -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Vendor</label>
                    <select name="vendor_id" class="form-control">
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" 
                                {{ isset($selectedvendor) && $selectedvendor->id == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ isset($selectedCategory) && $selectedCategory->id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sertifisering -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Sertifiseringsmåned</label>
                    <input type="number" name="certification_month" min="1" max="12" value="{{ $equipment->certification_month }}" class="form-control">
                </div>

            </div>

            <!-- Description -->
            <div class="row mt-3">
                <div class="col mb-3">
                    <label class="form-label">Beskrivelse</label>
                    <textarea name="description" class="form-control">{{ $equipment->description }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Lagre</button>
            <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Avbryt</a>
        </form>

    <!-- ------------------------------------------------- -->
    <!-- End Card -->
    <!-- ------------------------------------------------- -->
    </x-card-secondary>

</div>
@endsection
