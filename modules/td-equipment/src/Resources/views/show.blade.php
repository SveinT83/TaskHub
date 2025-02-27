@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $equipment->name }}</h1>
    <p><strong>Kategori:</strong> {{ $equipment->category->name ?? 'Ingen kategori' }}</p>
    <p><strong>Serienummer:</strong> {{ $equipment->serial_number }}</p>
    <p><strong>Status:</strong> {{ ucfirst($equipment->status) }}</p>
    <p><strong>Sertifiseringsmåned:</strong> {{ $equipment->certification_month ?? 'Ikke satt' }}</p>
    <p><strong>Beskrivelse:</strong> {{ $equipment->description ?? 'Ingen beskrivelse' }}</p>

    <a href="{{ route('equipment.edit', $equipment->id) }}" class="btn btn-warning">Rediger</a>
    <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Tilbake</a>
</div>
@endsection
