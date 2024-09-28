
@foreach($tasks as $task)
    <div class="col-md-4">
        <a class="card" href="{{ route('tasks.show', $task->id) }}">
            <div class="card-body">
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
            </div>
        </a>
    </div>
@endforeach

<div class="card-footer">
    <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary btn-sm bi bi-plus"> add</a>
</div>