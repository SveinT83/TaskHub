@extends('layouts.app')

@section('pageHeader')
    <h1>Opprett ny tillatelse</h1>
@endsection

@section('content')
<div class="container">

    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tillatelsesnavn</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <button type="submit" class="btn btn-primary">Opprett</button>
    </form>
</div>
@endsection
