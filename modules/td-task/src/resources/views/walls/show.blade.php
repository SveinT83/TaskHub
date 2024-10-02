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
                    <div class="row mt-2">
                        <div class="col-lg-2">
                            <a href="{{ route('tasks.create', ['wall_id' => $wall->id]) }}" class="btn btn-primary bi bi-plus"> Add task</a>
                        </div>
                        <p class="col-lg-2" style="font-size: 10px;"><strong> Created:</strong> {{ $wall->created_at }}</p>
                        <p class="col-lg-2" style="font-size: 10px;"><strong>Updated:</strong> {{ $wall->updated_at }}</p>
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
            <div class="card mt-3">
                <div class="card-header text-bg-primary">
                    <h2>{{ $status->name }}</h2> <!-- Status name as header -->
                </div>
                <div class="card-body">
                    @if($tasksGroupedByStatus->has($status->id))
                        @foreach($tasksGroupedByStatus[$status->id] as $task)
                            @include('tdtask::partials.task_card')
                        @endforeach
                    @else
                        <p>No tasks with this status</p>
                    @endif
                </div>
            </div>
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
