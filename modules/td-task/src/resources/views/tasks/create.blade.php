@extends('layouts.app')

@section('pageHeader')
    <h1>Create Task</h1>
@endsection

@section('content')
    <form class="card mt-3" action="{{ route('tasks.store') }}" method="POST">
        @csrf

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
                <div class="col-md-3 mt-2">
                    <label class="form-label fw-bold" for="due_date">Due Date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

                <!-- Child Task (Optional) -->
                <div class="col-md-3 mt-2">
                    <label class="form-label fw-bold" for="child_task_id">Child Task</label>
                    <select name="child_task_id" class="form-select">
                        <option value="">-- Select Child Task (optional) --</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Assignee (Optional) -->
                <div class="col-md-3 mt-2">
                    <label class="form-label fw-bold" for="assigned_to">Assign to User</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">-- Select User (optional) --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Select for Wall (Optional) -->
                <div class="col-md-3 mt-2">
                    <label class="form-label fw-bold" for="wall_id">Wall</label>
                    <select name="wall_id" class="form-select">
                        <option value="">-- No Wall --</option>
                        @foreach($walls as $wall)
                            <option value="{{ $wall->id }}" 
                                {{ request('wall_id') == $wall->id ? 'selected' : '' }}>
                                {{ $wall->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Submit -->
            <button class="btn btn-primary bi bi-plus mt-3" type="submit"> Create Task</button>
        </div>
    </form>
@endsection
