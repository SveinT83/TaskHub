@extends('layouts.app')

@section('pageHeader')
    <h1>Edit permission</h1>
@endsection

@section('content')
<div class="container"><

    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Tillatelsesnavn</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Oppdater</button>
    </form>
</div>
@endsection
