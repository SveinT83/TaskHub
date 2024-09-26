@extends('layouts.app')

@section('content')
    <h1>Create Task</h1>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="date" name="due_date">
        <button type="submit">Create Task</button>
    </form>
@endsection
