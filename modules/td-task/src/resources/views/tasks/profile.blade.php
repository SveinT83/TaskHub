@extends('layouts.app')

@section('pageHeader')
    <div class="row align-items-center">
        <h5 class="col-md-10 mt-1 card-title">Task: {{ $task->title }}</h5>

        <!-- ------------------------------------------------- -->
        <!-- Status Drop-Down -->
        <!-- ------------------------------------------------- -->
        <div class="col-md-2 mt-1">
            <!-- Status Drop-Down in Form -->
            <form id="statusForm" action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="status_id" id="status" class="form-select" onchange="submitStatusForm()">
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ $task->status_id == $status->id ? 'selected' : '' }}>
                            {{ $status->status_name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

    </div>
@endsection

@section('content')

    <div class="row">

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Left Side -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <div class="col-md-6">

            <!-- ------------------------------------------------- -->
            <!-- Task Description Card -->
            <!-- ------------------------------------------------- -->
            <div class="row">
                <div class="col-12">
                    @include('tdtask::partials.task_desc_card')
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Task Comments Card -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">
                <div class="col-12">
                    @include('tdtask::partials.task_comments_card')
                </div>
            </div>

        </div>

        <!-- -------------------------------------------------------------------------------------------------- -->
        <!-- Right Side -->
        <!-- -------------------------------------------------------------------------------------------------- -->
        <div class="col-md-6">

            <!-- ------------------------------------------------- -->
            <!-- Assignet -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">
                <form class="col-12" id="assigneeForm" action="{{ route('tasks.updateAssignee', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <label for="assignee" class="card-header">Assign to User</label>

                        <div class="card-body">
                            <select name="assigned_to" id="assignee" class="form-control" onchange="document.getElementById('assigneeForm').submit();">
                                <option value="">Unassign</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Wall -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Wall</div>
                        <div class="card-body">
                            <!-- Wall select -->
                            <form id="wallForm" action="{{ route('tasks.updateWall', $task->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <!-- Select dropdown for walls -->
                                <select name="wall_id" id="wall_id" class="form-select" onchange="document.getElementById('wallForm').submit();">
                                    @foreach($walls as $wall)
                                        <option value="{{ $wall->id }}" 
                                            {{ $task->wall_id == $wall->id ? 'selected' : '' }}>
                                            {{ $wall->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Stats -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Stats</div>
                        <div class="card-body">
                            <div class="row">
                                <p class="col-lg-4">Created: {{ $task->created_at }}</p>
                                <p class="col-lg-4">Updated: {{ $task->updated_at }}</p>
                                <p class="col-lg-4">Due: {{ $task->due_date }}</p>
                            </div>
                            <!-- Child tasks -->
                            <div class="row mt-1">
                                <ul class="list-group">
                                    @if($task->childTasks->count() > 0)
                                        @foreach($task->childTasks as $childTask)
                                            <li class="list-group-item">
                                                <a href="{{ route('tasks.show', $childTask->id) }}" class="col-lg-12">
                                                    Child Task: {{ $childTask->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item">No Child Tasks</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Delete Task -->
            <!-- ------------------------------------------------- -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row row-cols-auto p-1 text-center">

                                <!-- Button to go back to the Wall (if it exists) -->
                                @if($task->wall_id)
                                    <div class="col-12 col-lg-auto m-1">
                                        <div class="row">
                                            <a href="{{ route('walls.show', $task->wall_id) }}" class="bi bi-backspace btn btn-primary">
                                                Back to: {{ $wall->name }} Wall
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <!-- Button to go back to Task List -->
                                <div class="col-12 col-lg-auto m-1">
                                    <div class="row">
                                        <a href="{{ route('tasks.index') }}" class="bi bi-skip-backward btn btn-outline-secondary">
                                            Back to Task List
                                        </a>
                                    </div>
                                </div>

                                <!-- Mark as done -->
                                @if($task->status_id != 4)
                                <form class="col-12 col-lg-auto m-1" action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
            
                                        <input type="hidden" name="status_id" value="4">
                                        <div class="row">
                                            <button type="submit" class="btn btn-success">
                                                Mark as Done
                                            </button>
                                        </div>
                                </form>
                                @endif

                                <!-- Delete button with confirmation alert -->
                                <form class="col-12 col-lg-auto m-1" id="deleteTaskForm" action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirmDeletion();">
                                    @csrf
                                    @method('DELETE')

                                    <div class="row">
                                        <button type="submit" class="bi bi-x btn btn-outline-danger"> Delete Task</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--
        <div class="card-footer">
            <a href="{{ route('walls.show', $task->wall_id) }}" class="btn btn-secondary bi bi-backspace mt-1" type="submit"> Wall</a>
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary mt-1">Edit</a>
        </div>
    -->
@endsection

@section('scripts')
    <script>
        function submitStatusForm() {
            document.getElementById('statusForm').submit();
        }
    </script>
@endsection
