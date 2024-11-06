@extends('layouts.app')

@section('pageHeader')
    <h1>Create a new role</h1>
@endsection

@section('content')
<div class="container mt-3">

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Rollenavn</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="permissions" class="form-label">Tillatelser</label>
            <select multiple class="form-control" id="permissions" name="permissions[]">
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Opprett</button>
    </form>
</div>
@endsection
