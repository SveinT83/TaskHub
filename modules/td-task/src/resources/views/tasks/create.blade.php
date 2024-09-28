@extends('layouts.app')

@section('pageHeader')
    <h1>Create Task</h1>
@endsection

@section('content')
    <form class="card mt-3" action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <!-- Hvis oppgaven opprettes fra en vegg, inkluder wall_id som skjult felt -->
        @if(request()->has('wall_id'))
            <input type="hidden" name="wall_id" value="{{ request('wall_id') }}">
        @endif

        <div class="card-body">
            <!-- Title -->
            <div class="mb-3">
                <label class="form-label fw-bold" for="title">Title</label>
                <input type="text" class="form-control" name="title" placeholder="Title" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label fw-bold" for="description">Description</label>
                <textarea name="description" class="form-control" placeholder="Description" required></textarea>
            </div>

            <div class="row mt-3">

                <!-- Due Date -->
                <div class="col-md-3">
                    <label class="form-label fw-bold" for="due_date">Due Date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

                <!-- Child Task (Optional) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold" for="child_task_id">Child Task</label>
                    <select name="child_task_id" class="form-select">
                        <option value="">-- Select Child Task (optional) --</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Group (Optional) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold" for="group_id">Group</label>
                    <select name="group_id" class="form-select">
                        <option value="">-- Select Group (optional) --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Assignee (Optional) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold" for="assigned_to">Assign to User</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">-- Select User (optional) --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Submit -->
            <button class="btn btn-primary bi bi-plus mt-3" type="submit"> Create Task</button>
        </div>
    </form>
@endsection
