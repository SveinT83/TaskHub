<a class="card h-100" href="{{ route('tasks.show', $task->id) }}">

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card Header -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <div class="card-header bg-secondary-subtle">
        <h5 class="card-title">{{ $task->title }}</h5>
    </div>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card Body -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <div class="card-body">
        <div class="row">
            <div class="col-12" style="max-height: 200px; overflow-y: auto;">
                <p>{!! $task->description !!}</p>
            </div>
        </div>
    </div>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card Footer -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <div class="card-footer">
        <div class="row">
            <p class="col-lg-4 bi bi-hourglass-split"> {{ $task->due_date }}</p>

            <!-- ------------------------------------------------- -->
            <!-- Status? -->
            <!-- ------------------------------------------------- -->
            @if(optional($task->status)->name)
                <p class="col-lg-3 bi bi-check-circle-fill"> {{ $task->status->name }}</p>
            @endif

            <!-- ------------------------------------------------- -->
            <!-- Assignet? -->
            <!-- ------------------------------------------------- -->
            @if(optional($task->assignee)->name)
                <p class="col-lg-4 bi bi-person-check-fill"> {{ $task->assignee->name }}</p>
            @else
                <p class="col-lg-4 bi bi-person-slash"> Unassignet</p>
            @endif

            <!-- ------------------------------------------------- -->
            <!-- Parent Task (belongsTo or hasMany) -->
            <!-- ------------------------------------------------- -->
            @if($task->parentTask)
                <div class="col-lg-3">
                    <b>Child of:</b>
                    <!-- <a href="{{ route('tasks.show', $task->parentTask->id) }}">{{ $task->parentTask->title }}</a> -->
                    <i>{{ $task->parentTask->title }}</i>
                </div>
            @endif

        </div>
    </div>

</a>
