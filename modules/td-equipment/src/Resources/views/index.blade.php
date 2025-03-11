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
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? 'Ingen kategori' }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td><a href="{{ route('equipment.show', $item->id) }}" class="btn btn-primary btn-sm">Vis</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Dette virker ikke, 12345... seksjonene havner langt nede på listen samt pilene som tar deg til neste og forigie side er mange pixler store.
        {{ $equipment->appends(['sort_field' => $sortField, 'sort_order' => $sortOrder])->links('vendor.pagination.default') }}
    @endif
</div>
@endsection
