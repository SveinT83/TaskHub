@extends('layouts.app')

@section('pageHeader')
    <h1>Menu Configurations</h1>
@endsection

@section('content')
<div class="container mt-3">

    <!-- Formular for å opprette en ny meny -->
    <div class="row">
        <h3>Create New Menu</h3>
        <form action="{{ route('menu.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Menu Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description (optional)</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Create Menu</button>
        </form>
    </div>

    <!-- Liste over eksisterende menyer -->
    <div class="row mt-2">
        <h3>Existing Menus</h3>
        <ul class="list-group">
            @foreach($menus as $menu)
                <li class="list-group-item">
                    <a href="{{ route('menu.items', $menu->id) }}">{{ $menu->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
