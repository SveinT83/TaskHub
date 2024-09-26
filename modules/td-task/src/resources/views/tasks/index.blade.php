@extends('layouts.app')

@section('content')
    <h1>Tasks</h1>
    <ul>
        @foreach($tasks as $task)
            <li>{{ $task->title }} - {{ $task->due_date }}</li>
        @endforeach
    </ul>
@endsection
