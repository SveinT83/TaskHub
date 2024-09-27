<div class="card">
    <div class="card-header">
        <h2>Groups</h2>
    </div>
    <div class="card-header">
        @foreach($tasks as $task)
            <a class="row border p-1" href="{{ route('tasks.show', $task->id) }}">
                <b class="col-md-3">{{ $task->title }}</b>
                <p class="col-md-3 bi bi-hourglass-split"> {{ $task->due_date }}</p>

                <!-- ------------------------------------------------- -->
                <!-- Group? -->
                <!-- ------------------------------------------------- -->
                @if(optional($task->group)->name)
                    <p class="col-md-2 bi bi-collection-fill"> {{ $task->group->name }}</p>
                @else
                    <p class="col-md-2 bi bi-collection"> Ungrouped</p>
                @endif
                
                <!-- ------------------------------------------------- -->
                <!-- Assignet? -->
                <!-- ------------------------------------------------- -->
                @if(optional($task->assignee)->name)
                    <p class="col-md-2 bi bi-person-check-fill"> {{ $task->assignee->name }}</p>
                @else
                    <p class="col-md-2 bi bi-person-slash"> Unassignet</p>
                @endif
            </a>
        @endforeach
    </div>
    <div class="card-footer">
        <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary btn-sm bi bi-plus"> add</a>
    </div>
</div>