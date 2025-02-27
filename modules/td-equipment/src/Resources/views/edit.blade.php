@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rediger utstyr</h1>

    <form action="{{ route('equipment.update', $equipment->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Navn</label>
            <input type="text" name="name" value="{{ $equipment->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" @if($equipment->category_id == $category->id) selected @endif>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Serienummer</label>
            <input type="text" name="serial_number" value="{{ $equipment->serial_number }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" @if($equipment->status == 'active') selected @endif>Aktiv</option>
                <option value="inactive" @if($equipment->status == 'inactive') selected @endif>Ikke i bruk</option>
                <option value="needs_certification" @if($equipment->status == 'needs_certification') selected @endif>
                    Må sertifiseres
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Sertifiseringsmåned</label>
            <input type="number" name="certification_month" min="1" max="12" value="{{ $equipment->certification_month }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Beskrivelse</label>
            <textarea name="description" class="form-control">{{ $equipment->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Lagre</button>
        <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Avbryt</a>
    </form>
</div>
@endsection
