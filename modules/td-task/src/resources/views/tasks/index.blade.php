@extends('layouts.app')

@section('pageHeader')
    <h1>Tasks</h1>
@endsection

@section('content')

    <div class="card mt-3">
        <div class="card-header">
            @foreach($tasks as $task)
                <a class="row mt-1" href="{{ route('tasks.show', $task->id) }}">
                        <b class="col-3">{{ $task->title }}</b>
                        <p class="col-1 bi bi-hourglass-split"> {{ $task->due_date }}</p>

                        <!-- ------------------------------------------------- -->
                        <!-- Group? -->
                        <!-- ------------------------------------------------- -->
                        @if(optional($task->group)->name)
                            <p class="col-1 bi bi-collection-fill"> {{ $task->group->name }}</p>
                        @else
                            <p class="col-1 bi bi-collection"></p>
                        @endif
                        
                        <!-- ------------------------------------------------- -->
                        <!-- Assignet? -->
                        <!-- ------------------------------------------------- -->
                        @if(optional($task->assignee)->name)
                            <p class="col-1 bi bi-person-check-fill"> {{ $task->assignee->name }}</p>
                        @else
                            <p class="col-1 bi bi-person-slash"> Unassignet</p>
                        @endif
                    </a>
            @endforeach
        </div>
    </div>
@endsection
