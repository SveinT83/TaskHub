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
    <x-card-secondary title="Add equipment">

        <!-- ------------------------------------------------- -->
        <!-- Form -->
        <!-- ------------------------------------------------- -->
        <form action="{{ route('equipment.store') }}" method="POST">
            @csrf

            <!-- ------------------------------------------------- -->
            <!-- Row - Name, Serial number and Status -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">

                <!-- Name -->
                <div class="col-md-5 md-3">
                    <label class="form-label">Navn</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <!-- Intern number -->
                <div class="col-md-2 mb-3">
                    <label class="form-label">Internnummer</label>
                    <input type="text" name="internal_number" value="{{ $equipment->internal_number ?? '' }}" class="form-control">
                </div>

                <!-- SN -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Serienummer</label>
                    <input type="text" name="serial_number" class="form-control" required>
                </div>

                <!-- Status -->
                <div class="col-md-2 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Aktiv</option>
                        <option value="inactive">Ikke i bruk</option>
                        <option value="needs_certification">Må sertifiseres</option>
                    </select>
                </div>

            </div>


            <!-- ------------------------------------------------- -->
            <!-- Row - vendor, Category and sertificate month -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">

                <!-- Supplier -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Vendor</label>
                    <select name="vendor_id" class="form-control">
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control">
                        @if ($categories->isEmpty())
                            <option value="unspecified">Uspesifisert kategori</option>
                        @else
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Certification month -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Sertifiseringsmåned</label>
                    <select name="certification_month" class="form-control">

                        <option value="0">Velg måned</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

            </div>

            <!-- ------------------------------------------------- -->
            <!-- Row - Description -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">
                <div class="mb-3">
                    <label class="form-label">Beskrivelse</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Buttons -->
            <!-- ------------------------------------------------- -->
            <button type="submit" class="btn btn-success">Lagre</button>
            <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Avbryt</a>
        </form>

    <!-- ------------------------------------------------- -->
    <!-- End Card -->
    <!-- ------------------------------------------------- -->
    </x-card-secondary>
</div>
@endsection
