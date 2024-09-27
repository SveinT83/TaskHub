@extends('layouts.app')

@section('pageHeader')
    <h1>Task Profile</h1>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h2>{{ $task->title }}</h2>
        </div>
        <div class="card-body">
            <!-- Description -->
            <p><strong>Description:</strong> {{ $task->description }}</p>

            <!-- Due Date -->
            <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

            <!-- Group -->
            @if($task->group)
                <p><strong>Group:</strong> {{ $task->group->name }}</p>
            @else
                <p><strong>Group:</strong> None</p>
            @endif

            <!-- Assignee -->
            @if($task->assignee)
                <p><strong>Assigned To:</strong> {{ $task->assignee->name }}</p>
            @else
                <p><strong>Assigned To:</strong> Unassigned</p>
            @endif

            <!-- Status -->
            @if($task->status)
                <p><strong>Status:</strong> {{ $task->status->name }}</p>
            @else
                <p><strong>Status:</strong> Not Set</p>
            @endif
        </div>

        <div class="card-footer">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary bi bi-backspace mt-1" type="submit"> Back</a>
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary mt-1">Edit</a>
        </div>
    </div>
@endsection
