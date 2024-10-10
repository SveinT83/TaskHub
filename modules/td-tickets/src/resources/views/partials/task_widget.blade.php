<!-- ------------------------------------------------- -->
<!-- Task Card -->
<!-- ------------------------------------------------- -->

<div class="card mt-3">

    <!-- ------------------------------------------------- -->
    <!-- Card Header -->
    <!-- ------------------------------------------------- -->
    <div class="card-header">
        <p>Tasks</p>
    </div>

    <!-- ------------------------------------------------- -->
    <!-- Card Body -->
    <!-- ------------------------------------------------- -->
    <div class="card-body">
        <div class="row">

            <!-- ------------------------------------------------- -->
            <!-- Task If task exists - Hide if create task is pressed -->
            <!-- ------------------------------------------------- -->
            @if($tasks->count() > 0)
                <div class="col">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Task</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr>
                                <th scope="row"><input class="form-check-input" type="checkbox" value="{{ $task->id }}" id="{{ $task->id }}"></th>
                                <td>{{ $task->title }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No tasks available!</p>
            @endif
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Create task button - Hide if create task is pressed -->
        <!-- ------------------------------------------------- -->
        <form class="row mt-3">
            <button type="button" class="btn btn-outline-primary" id="showCreateTaskBtn"> Create task </button>
        </form>

        <!-- ------------------------------------------------- -->
        <!-- Create task Form Show only if "Create Task is pressed -->
        <!-- ------------------------------------------------- -->
        <form class="row justify-content-center border-top mt-3" id="createTaskForm" style="display:none;"> <!-- Skjult som standard -->

            <!-- Task Name -->
            <div class="col-12 mt-1 mb-3">
                <label for="title" class="form-label fw-bold">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <!-- Description -->
            <div class="col-12 mb-3">
                <label class="form-label fw-bold" for="description">Description:</label>
                <textarea name="description" class="form-control" placeholder="Description" required></textarea>
            </div>

            <!-- Due Date -->
            <div class="col-12 mb-3">
                <label class="form-label fw-bold" for="due_date">Due Date:</label>
                <input type="date" name="due_date" class="form-control">
            </div>

            <!-- Child Task (Optional) -->
            <div class="col-12 mb-3">
                <label class="form-label fw-bold" for="child_task_id">Child Task:</label>
                <select name="child_task_id" class="form-select">
                    <option value="">-- Select Child Task (optional) --</option>
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-outline-primary"> Save </button>
            <button type="button" class="btn mt-3 btn-outline-secondary btn-sm" id="abortCreateTaskBtn"> Abort </button>
        </form>

    <!-- ------------------------------------------------- -->
    <!-- Card Body And Card - END -->
    <!-- ------------------------------------------------- -->
    </div>
</div>

<!-- ------------------------------------------------- -->
<!-- JavaScript for showing/hiding forms -->
<!-- ------------------------------------------------- -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const createTaskForm = document.getElementById('createTaskForm');
        const showCreateTaskBtn = document.getElementById('showCreateTaskBtn');
        const abortCreateTaskBtn = document.getElementById('abortCreateTaskBtn');

        // Når brukeren trykker på "Create task"-knappen, vis skjemaet og skjul knappen
        showCreateTaskBtn.addEventListener('click', function() {
            createTaskForm.style.display = 'block';
            showCreateTaskBtn.style.display = 'none';
        });

        // Når brukeren trykker på "Abort"-knappen, skjul skjemaet og vis "Create task"-knappen igjen
        abortCreateTaskBtn.addEventListener('click', function() {
            createTaskForm.style.display = 'none';
            showCreateTaskBtn.style.display = 'block';
        });
    });
</script>
