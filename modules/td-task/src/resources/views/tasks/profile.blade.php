@extends('layouts.app')

@section('pageHeader')
    <div class="row align-items-center">
        <h5 class="col-md-10 card-title">Task: {{ $task->title }}</h5>

        <!-- ------------------------------------------------- -->
        <!-- Status Drop-Down -->
        <!-- ------------------------------------------------- -->
        <div class="col-md-2">
            <select name="status_id" id="status" class="form-control" data-task-id="{{ $task->id }}">
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ $task->status_id == $status->id ? 'selected' : '' }}>
                        {{ $status->status_name }}
                    </option>
                @endforeach
            </select>
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
            <!-- Mark As done -->
            <!-- ------------------------------------------------- -->
            @if($task->status_id != 4)
                <div class="row mt-3">
                    <form class="col-12" action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                        @csrf
                        <div class="card">
                            <input type="hidden" name="status_id" value="4">
                            <button type="submit" class="btn btn-success">Mark as Done</button>
                        </div>
                    </form>
                </div>
            @endif

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
                            <form id="deleteTaskForm" action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirmDeletion();">
                                @csrf
                                @method('DELETE')

                                <!-- Delete button with confirmation alert -->
                                <button type="submit" class="btn btn-outline-danger">Delete Task</button>
                            </form>
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
        function confirmDeletion() {
            return confirm('Are you sure you want to delete this task? This action cannot be undone.');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Finn status select-boksen
            const statusSelect = document.getElementById('status');

            if (statusSelect) {
                // Legg til en event listener for endring av status
                statusSelect.addEventListener('change', function () {
                    // Hent oppgave-ID og ny status
                    const taskId = this.getAttribute('data-task-id');
                    const newStatusId = this.value;

                    // Debugging: Sjekk om taskId og newStatusId er riktig
                    console.log('Task ID:', taskId);
                    console.log('Selected Status ID:', newStatusId);

                    // Send AJAX-forespørsel for å oppdatere status
                    fetch(`/tasks/${taskId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status_id: newStatusId
                        })
                    })
                    .then(response => {
                        // Debugging: Sjekk om vi får en respons
                        console.log('Response received:', response);
                        return response.json();
                    })
                    .then(data => {
                        // Debugging: Sjekk dataen fra serveren
                        console.log('Data from server:', data);

                        if (data.success) {
                            // alert('Status updated successfully!');
                        } else {
                            // alert('Failed to update status.');
                        }
                    })
                    .catch(error => {
                        // Debugging: Sjekk om det er noen feil i fetch-funksjonen
                        console.error('Error occurred:', error);
                    });
                });
            }
        });
    </script>
@endsection
