<div class="card mt-3">
    <div class="card-header text-bg-primary">
        <h5 class="card-title">Description</h5>
    </div>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card Body -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <div class="card-body bg-body-tertiary">
        <p>{!! $task->description !!}</p>
    </div>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Card Footer -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <div class="card-footer text-muted" style="font-size: 10px;">
        <div class="row justify-content-start">
            <p class="col-lg-1 bi bi-hourglass-split"> {{ $task->due_date }}</p>


            <!-- ------------------------------------------------- -->
            <!-- Status? -->
            <!-- ------------------------------------------------- -->
            @if(optional($task->status)->name)
                <p class="col-lg-1 bi bi-check-circle-fill"> {{ $task->status->name }}</p>
            @else
                <p class="col-lg-1 bi bi-circle"> No Status</p>
            @endif

            <!-- ------------------------------------------------- -->
            <!-- Status? -->
            <!-- ------------------------------------------------- -->
            @if(optional($task->status)->name)
                <p class="col-lg-1 bi bi-check-circle-fill"> {{ $task->status->name }}</p>
            @else
                <p class="col-lg-1 bi bi-circle"> No Status</p>
            @endif

            <!-- ------------------------------------------------- -->
            <!-- Parent Task (belongsTo or hasMany) -->
            <!-- ------------------------------------------------- -->
            @if($task->parentTask)
                @if($task->parentTask instanceof \Illuminate\Database\Eloquent\Collection)
                    <p class="col-lg-1">Child of:
                        @foreach($task->parentTask as $parent)
                            {{ $parent->title }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @else
                    <p class="col-lg-1">Child of: {{ $task->parentTask->title }}</p>
                @endif
            @endif

            <!-- ------------------------------------------------- -->
            <!-- Child Task (belongsTo or hasMany) -->
            <!-- ------------------------------------------------- -->
            @if($task->childTask)
                @if($task->childTask instanceof \Illuminate\Database\Eloquent\Collection)
                    <p class="col-lg-1">Has child task(s):
                        @foreach($task->childTask as $child)
                            {{ $child->title }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @else
                    <p class="col-lg-1">Child of: {{ $task->childTask->title }}</p>
                @endif
            @endif


            <!-- ------------------------------------------------- -->
            <!-- Assignet? -->
            <!-- ------------------------------------------------- -->
            @if(optional($task->assignee)->name)
                <p class="col-lg-1 bi bi-person-check-fill"> {{ $task->assignee->name }}</p>
            @else
                <p class="col-lg-1 bi bi-person-slash"> Unassignet</p>
            @endif
        </div>
    </div>

</div>
