@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Legg til nytt utstyr</h1>

    <form action="{{ route('equipment.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Navn</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Serienummer</label>
            <input type="text" name="serial_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active">Aktiv</option>
                <option value="inactive">Ikke i bruk</option>
                <option value="needs_certification">Må sertifiseres</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Sertifiseringsmåned</label>
            <input type="number" name="certification_month" min="1" max="12" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Beskrivelse</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Lagre</button>
        <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Avbryt</a>
    </form>
</div>
@endsection
