@extends('layouts.app')

@section('pageHeader')
    <h1>Create Wall</h1>
@endsection

@section('content')
    <form action="{{ route('walls.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Wall Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter wall name" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" placeholder="Enter description (optional)"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Create Wall</button>
    </form>
@endsection
