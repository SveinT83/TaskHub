@extends('layouts.app')

@section('pageHeader')
    <h1>Edit Task</h1>
@endsection

@section('content')
    <form class="card mt-3" action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Dette er viktig for å bruke PUT-forespørsel -->

        <div class="card-body">
            <!-- Title -->
            <div class="mb-3">
                <label class="form-label fw-bold" for="title">Title</label>
                <input type="text" class="form-control" name="title" value="{{ $task->title }}" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label fw-bold" for="description">Description</label>
                <textarea name="description" class="form-control" required>{{ $task->description }}</textarea>
            </div>

            <div class="row mt-3">

                <!-- Due Date -->
                <div class="col-md-3">
                    <label class="form-label fw-bold" for="due_date">Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ $task->due_date }}">
                </div>

                <!-- Child Task (Optional) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold" for="child_task_id">Child Task</label>
                    <select name="child_task_id" class="form-select">
                        <option value="">-- Select Child Task (optional) --</option>
                        @foreach($tasks as $childTask)
                            <option value="{{ $childTask->id }}" {{ $task->child_task_id == $childTask->id ? 'selected' : '' }}>
                                {{ $childTask->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Group (Optional) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold" for="group_id">Group</label>
                    <select name="group_id" class="form-select">
                        <option value="">-- Select Group (optional) --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $task->group_id == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Assignee (Optional) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold" for="assigned_to">Assign to User</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">-- Select User (optional) --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Submit -->
            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-secondary bi bi-backspace mt-3" type="submit"> Cansel</a>
            <button class="btn btn-primary bi bi-pencil mt-3" type="submit"> Update Task</button>
        </div>
    </form>
@endsection
