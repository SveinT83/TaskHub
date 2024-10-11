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
                            <tr data-task-id="{{ $task->id }}" class="task-row">
                                <th scope="row">
                                    <input class="form-check-input task-complete-checkbox" type="checkbox" value="{{ $task->id }}" id="task-checkbox-{{ $task->id }}" {{ $task->status_id == 4 ? 'checked' : '' }}>
                                </th>
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
        <!-- Task Detail Section (Hidden by default) -->
        <!-- ------------------------------------------------- -->
        <div class="card" id="task-details" style="display:none;" class="mt-4">
            <div class="card-header">
                <div class="row align-items-center justify-content-between">
                    <h5 class="col-10" id="task-title">Task Details</h5>

                    <button class="col-2 btn btn-link" id="edit-task-btn" style="font-size: 10px;">Edit</button>
                </div>
            </div>

            <div class="card-body">
                <p id="task-description"></p>
            </div>

            <div class="card-footer">
                <div class="row">
                    <p class="col-4 bi bi-calendar-event" id="task-due-date" style="font-size: 10px;"></p> <!-- Viser forfallsdatoen -->
                    <p class="col-4 bi bi-person" id="task-assignee" style="font-size: 10px;"></p> <!-- Viser hvem som er tildelt oppgaven -->
                    <p class="col-4" id="task-status" style="font-size: 10px;"></p> <!-- Viser statusen til oppgaven -->
                </div>
            </div>
        </div>

        <!-- Create task button -->
        <form class="row mt-3">
            <button type="button" class="btn btn-outline-primary" id="showCreateTaskBtn">Create task</button>
        </form>

        <!-- Create task form -->
        <form class="row justify-content-center border-top mt-3" id="createTaskForm" style="display:none;" action="{{ route('tickets.tasks.store', ['ticketId' => $ticket->id]) }}" method="POST">
            @csrf
            <div class="col-12 mt-1 mb-3">
                <label for="title" class="form-label fw-bold">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-bold" for="description">Description:</label>
                <textarea name="description" class="form-control" placeholder="Description" required></textarea>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-bold" for="due_date">Due Date:</label>
                <input type="date" name="due_date" class="form-control">
            </div>
            <button class="btn btn-outline-primary">Save</button>
            <button type="button" class="btn mt-3 btn-outline-secondary btn-sm" id="abortCreateTaskBtn">Abort</button>
        </form>
    </div>
</div>

<!-- JavaScript for showing/hiding forms, task details, and marking tasks as complete -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const taskRows = document.querySelectorAll('.task-row');
        const taskDetailsDiv = document.getElementById('task-details');
        const taskTitleElement = document.getElementById('task-title');
        const taskDueDateElement = document.getElementById('task-due-date');
        const taskAssigneeElement = document.getElementById('task-assignee');
        const taskDescriptionElement = document.getElementById('task-description');
        const taskStatusElement = document.getElementById('task-status');

        const createTaskForm = document.getElementById('createTaskForm');
        const showCreateTaskBtn = document.getElementById('showCreateTaskBtn');
        const abortCreateTaskBtn = document.getElementById('abortCreateTaskBtn');

        // Når brukeren trykker på "Create Task"-knappen, vis skjemaet og skjul knappen
        showCreateTaskBtn.addEventListener('click', function() {
            createTaskForm.style.display = 'block';
            showCreateTaskBtn.style.display = 'none';
        });

        // Når brukeren trykker på "Abort"-knappen, skjul skjemaet og vis "Create Task"-knappen igjen
        abortCreateTaskBtn.addEventListener('click', function() {
            createTaskForm.style.display = 'none';
            showCreateTaskBtn.style.display = 'block';
        });

        // Legg til hendelse for når en task klikkes på for å vise detaljer
        taskRows.forEach(row => {
            row.addEventListener('click', function() {
                const taskId = row.getAttribute('data-task-id');
                const ticketId = "{{ $ticket->id }}"; // Henter ticket ID fra Blade-filen
                
                console.log("Fetching details for task:", taskId);

                // Send en AJAX-forespørsel for å hente detaljer om tasken
                fetch(`/tickets/${ticketId}/tasks/${taskId}/details`)
                    .then(response => response.json())
                    .then(task => {
                        console.log("Task details:", task);

                        // Fyll ut detaljene i task-detaljdiven
                        taskTitleElement.textContent = ` ${task.title}`;
                        taskDueDateElement.textContent = ` ${task.due_date || 'Not set'}`;
                        taskAssigneeElement.textContent = ` ${task.assigned_to || 'Not assigned'}`;
                        taskDescriptionElement.textContent = ` ${task.description || 'No description available'}`;
                        taskStatusElement.textContent = ` ${task.status_id === 1 ? 'Open' : (task.status_id === 2 ? 'In Progress' : task.status_id === 3 ? 'Assignet' : task.status_id === 4 ? 'Closed' : 'No status')}`;

                        // Vis task-detaljdiven
                        taskDetailsDiv.style.display = 'block';

                        // Oppdater rediger-knappen med riktig URL
                        const editButton = document.getElementById('edit-task-btn');
                        editButton.setAttribute('onclick', `window.location.href='/tasks/${task.id}/edit'`);
                    })
                    .catch(error => console.error('Error fetching task details:', error));
            });
        });

        // Legg til hendelse for checkbox-statusendring
        taskRows.forEach(row => {
            const checkbox = row.querySelector('.form-check-input');

            checkbox.addEventListener('change', function() {
                const taskId = row.getAttribute('data-task-id');
                const ticketId = "{{ $ticket->id }}"; // Henter ticket ID fra Blade-filen
                const completed = checkbox.checked; // True hvis checkboksen er avkrysset

                // Send forespørsel om å oppdatere task-status
                fetch(`/tickets/${ticketId}/tasks/${taskId}/complete`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ completed: completed })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Oppdater visningen basert på ny status
                        if (data.task.status_id === 4) {
                            row.classList.add('text-decoration-line-through'); // Strek gjennom teksten for completed
                        } else {
                            row.classList.remove('text-decoration-line-through'); // Fjern streken for open
                        }
                    } else {
                        console.error('Error updating task status:', data.error);
                    }
                })
                .catch(error => console.error('Error updating task status:', error));
            });
        });
    });
</script>
    
