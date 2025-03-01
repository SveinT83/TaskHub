@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Utstyrsliste</h1>

    <a href="{{ route('equipment.create') }}" class="btn btn-success mb-3">+ Nytt utstyr</a>

    @if ($equipment->isEmpty())
        <p class="text-center text-muted">Ingen utstyr registrert.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Navn</th>
                    <th>Kategori</th>
                    <th>Serienummer</th>
                    <th>Status</th>
                    <th>Handlinger</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($equipment as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? 'Ingen kategori' }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td><a href="{{ route('equipment.show', $item->id) }}" class="btn btn-primary btn-sm">Vis</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $equipment->links() }}
    @endif
</div>
@endsection
