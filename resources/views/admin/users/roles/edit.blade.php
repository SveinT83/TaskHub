@extends('layouts.app')

@section('pageHeader')
    <h1>Edit role</h1>
@endsection

@section('content')
<div class="container mt-3">

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Rollenavn</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
        </div>

        <div class="mb-3">
            <label for="permissions" class="form-label">Tillatelser</label>
            <select multiple class="form-control" id="permissions" name="permissions[]">
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->name }}" {{ $role->permissions->contains($permission) ? 'selected' : '' }}>
                        {{ $permission->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Oppdater</button>
    </form>
</div>
@endsection
