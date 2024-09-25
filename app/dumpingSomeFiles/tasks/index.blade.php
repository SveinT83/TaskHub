@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Oppgaver</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <ul>
            @foreach($tasks as $task)
                <li>{{ $task->title }} - {{ $task->due_date }}</li>
            @endforeach
        </ul>
    </div>
@endsection
