@extends('layouts.app')

@section('pageHeader')
    <h1>Edit Menu Item: {{ $item->title }}</h1>
@endsection

@section('content')
<div class="container mt-3">
    <form action="{{ route('menu.items.update', [$menu->id, $item->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" name="url" class="form-control" value="{{ $item->url }}" required>
        </div>
        <div class="form-group">
            <label for="parent_id">Parent Menu Item (optional)</label>
            <select name="parent_id" class="form-control">
                <option value="">None</option>
                @foreach($menu->items as $parent)
                    <option value="{{ $parent->id }}" {{ $item->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="order">Order (optional)</label>
            <input type="number" name="order" class="form-control" value="{{ $item->order }}">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Menu Item</button>
    </form>
</div>
@endsection
