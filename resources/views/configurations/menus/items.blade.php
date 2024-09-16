@extends('layouts.app')

@section('pageHeader')
<h1>Menu Items for {{ $menu->name }}</h1>
@endsection

@section('content')
<div class="container mt-3">

    <!-- Liste over menyelementer -->
    <div class="row">
        <h3>Existing Menu Items</h3>
        <ul class="list-group">
            @foreach($menuItems as $item)
                <li class="list-group-item">
                    {{ $item->title }} (URL: {{ $item->url }})
                    <a href="{{ route('menu.items.edit', [$menu->id, $item->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Formular for å opprette et nytt menyelement -->
    <div class="row mt-3">
        <h3>Add New Menu Item</h3>
        <form action="{{ route('menu.items.create', $menu->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="url">URL</label>
                <input type="text" name="url" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="parent_id">Parent Menu Item (optional)</label>
                <select name="parent_id" class="form-control">
                    <option value="">None</option>
                    @foreach($menuItems as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="order">Order (optional)</label>
                <input type="number" name="order" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Menu Item</button>
        </form>
    </div>
</div>
@endsection
