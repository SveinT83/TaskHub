
@foreach($tasks as $task)
    <div class="col-md-4 mt-1">
        <a class="card h-100" href="{{ route('tasks.show', $task->id) }}">
            <div class="card-header bg-secondary-subtle">
                <h5 class="card-title">{{ $task->title }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($task->due_date)
                        <p class="col-lg-6 bi bi-hourglass-split"> {{ $task->due_date }}</p>
                    @endif

                    <!-- ------------------------------------------------- -->
                    <!-- Status? -->
                    <!-- ------------------------------------------------- -->
                    @if(optional($task->status)->status_name)
                        <p class="col-lg-6 bi bi-check-circle-fill"> {{ $task->status->status_name }}</p>
                    @else
                        <p class="col-lg-6 bi bi-circle"> No Status</p>
                    @endif

                    <!-- Parent Task (belongsTo or hasMany) -->
                    @if($task->parentTask)
                        @if($task->parentTask instanceof \Illuminate\Database\Eloquent\Collection)
                            <p class="text-muted">Child of:
                                @foreach($task->parentTask as $parent)
                                    {{ $parent->title }}@if(!$loop->last), @endif
                                @endforeach
                            </p>
                        @else
                            <p class="text-muted">Child of: {{ $task->parentTask->title }}</p>
                        @endif
                    @endif

                    <!-- Child Task (belongsTo or hasMany) -->
                    @if($task->childTask)
                        @if($task->childTask instanceof \Illuminate\Database\Eloquent\Collection)
                            <p class="text-muted">Has child task(s):
                                @foreach($task->childTask as $child)
                                    {{ $child->title }}@if(!$loop->last), @endif
                                @endforeach
                            </p>
                        @else
                            <p class="text-muted">Child of: {{ $task->childTask->title }}</p>
                        @endif
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
    </div>
@endforeach

<!--
    <div class="card-footer">
        <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary btn-sm bi bi-plus"> add</a>
    </div>
-->