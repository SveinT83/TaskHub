@extends('layouts.app')

@section('content')
    <h1>Task Walls</h1>
    <ul>
        @foreach($walls as $wall)
            <li><a href="{{ route('walls.show', $wall->id) }}">{{ $wall->name }}</a></li>
        @endforeach
    </ul>
@endsection
