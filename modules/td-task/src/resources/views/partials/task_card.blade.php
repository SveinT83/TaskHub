<a class="card h-100" href="{{ route('tasks.show', $task->id) }}">
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
            <p class="col-lg-6 bi bi-hourglass-split"> {{ $task->due_date }}</p>

            <!-- ------------------------------------------------- -->
            <!-- Status? -->
            <!-- ------------------------------------------------- -->
            @if(optional($task->status)->name)
                <p class="col-lg-6 bi bi-check-circle-fill"> {{ $task->status->name }}</p>
            @else
                <p class="col-lg-6 bi bi-circle"> No Status</p>
            @endif

            <!-- ------------------------------------------------- -->
            <!-- Parent Task (belongsTo or hasMany) -->
            <!-- ------------------------------------------------- -->
            @if($task->parentTask)
                <p class="text-muted">Child of:
                    <a href="{{ route('tasks.show', $task->parentTask->id) }}">
                        {{ $task->parentTask->title }}
                    </a>
                </p>
            @endif

            <!-- ------------------------------------------------- -->
            <!-- Child Task (belongsTo or hasMany) -->
            <!-- ------------------------------------------------- -->
            @if($task->childTasks->count() > 0)
                <p class="text-muted">Has child task(s):
                    @foreach($task->childTasks as $child)
                        <a href="{{ route('tasks.show', $child->id) }}">
                            {{ $child->title }}
                        </a>@if(!$loop->last), @endif
                    @endforeach
                </p>
            @endif


            <!-- ------------------------------------------------- -->
            <!-- Assignet? -->
            <!-- ------------------------------------------------- -->
            @if(optional($task->assignee)->name)
                <p class="col-lg-12 bi bi-person-check-fill"> {{ $task->assignee->name }}</p>
            @else
                <p class="col-lg-6 bi bi-person-slash"> Unassignet</p>
            @endif
        </div>
    </div>

</a>
