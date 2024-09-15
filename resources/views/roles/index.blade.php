@extends('layouts.app')

@section('title', 'Home')

@section('pageHeader')
    <h1>Roles and premissions</h1>
@endsection

@section('content')
<div class="container mt-3">

    <!-- Vis roller -->
    <h3>Roller</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Navn</th>
                <th>Handlinger</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary">Rediger</a>
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Slett</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('roles.create') }}" class="btn btn-success">Legg til ny rolle</a>

    <!-- Vis tillatelser -->
    <h3 class="mt-5">Tillatelser</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Navn</th>
                <th>Handlinger</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
            <tr>
                <td>{{ $permission->name }}</td>
                <td>
                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-primary">Rediger</a>
                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Slett</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('permissions.create') }}" class="btn btn-success">Legg til ny tillatelse</a>
</div>
@endsection
