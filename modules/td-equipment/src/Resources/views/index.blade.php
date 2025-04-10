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

<div class="container">

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card - Equipment -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <x-card-secondary title="List">

        <a href="{{ route('equipment.create') }}" class="btn btn-success mb-3">+ Nytt utstyr</a>

        @if ($equipment->isEmpty())
            <p class="text-center text-muted">Ingen utstyr registrert.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ route('equipment.index', ['sort_field' => 'id', 'sort_order' => $sortField == 'id' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                ID
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('equipment.index', ['sort_field' => 'internal_number', 'sort_order' => $sortField == 'internal_number' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                Intern nummer
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('equipment.index', ['sort_field' => 'name', 'sort_order' => $sortField == 'name' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                Navn
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('equipment.index', ['sort_field' => 'category_id', 'sort_order' => $sortField == 'category_id' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                Kategori
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('equipment.index', ['sort_field' => 'serial_number', 'sort_order' => $sortField == 'serial_number' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                Serienummer
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('equipment.index', ['sort_field' => 'status', 'sort_order' => $sortField == 'status' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                                Status
                            </a>
                        </th>
                        <th>Handlinger</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipment as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->internal_number }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? 'Ingen kategori' }}</td>
                        <td>{{ $item->serial_number }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td><a href="{{ route('equipment.show', $item->id) }}" class="btn btn-primary btn-sm">Vis</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $equipment->appends(['sort_field' => $sortField, 'sort_order' => $sortOrder])->links('pagination::bootstrap-4') }}
        @endif
        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- End Equipment Card -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        </x-card-secondary>

</div>
@endsection
