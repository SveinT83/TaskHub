@extends('layouts.app')

@section('pageHeader')
    <h1>{{ $wall->name }}</h1>
@endsection

@section('content')

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-bg-primary">
                    <div class="row justify-content-between align-items-center">
                        <h2 class="col-10">Wall Details</h2>
                        <div class="col-2 text-end">
                            <a href="" class="btn btn-outline-primary btn-sm">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <p class="col-md-6">{{ $wall->description }}</p>
                        <p class="col-md-6">Stats about tasks comes here...</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row mt-2 align-items-center">

                        <div class="col-auto">
                            <a href="{{ route('tasks.create', ['wall_id' => $wall->id]) }}" class="btn btn-primary bi bi-plus"> Add task</a>
                        </div>
                        <div class="col-auto">
                            <p style="font-size: 10px;"><strong> Created:</strong> {{ $wall->created_at }}</p>
                            <p style="font-size: 10px;"><strong>Updated:</strong> {{ $wall->updated_at }}</p>
                        </div>

                        <form class="col-auto" action="{{ route('walls.destroy', $wall->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this wall?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger {{ $canDeleteWall ? '' : 'disabled' }}" {{ $canDeleteWall ? '' : 'disabled' }}>
                                Delete Wall
                            </button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- TASKS -->
    <!-- Only if there are tasks -->
    <!-- -------------------------------------------------------------------------------------------------- -->
       <!-- Iterate over all statuses -->
        @foreach($statuses as $status)
            @if($tasksGroupedByStatus->has($status->id))
                <div class="card mt-3">
                    <div class="card-header bg-info-subtle">
                        <h2>{{ $status->status_name }}</h2> <!-- Status name as header -->
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($tasksGroupedByStatus->has($status->id))
                                    
                                @foreach($tasksGroupedByStatus[$status->id] as $task)
                                    <div class="col-lg-4 mt-1">
                                        @include('tdtask::partials.task_card')
                                    </div>
                                @endforeach
                                    
                            @else
                                <p>No tasks with this status</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Section for tasks with no status (NULL) -->
        @if($tasksGroupedByStatus->has('no_status'))
            <div class="card mt-3">
                <div class="card-header text-bg-primary">
                    <h2>No Status</h2> <!-- Header for tasks without a status -->
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($tasksGroupedByStatus['no_status'] as $task)
                            <div class="col-md-4 mt-1">
                                @include('tdtask::partials.task_card')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

@endsection
