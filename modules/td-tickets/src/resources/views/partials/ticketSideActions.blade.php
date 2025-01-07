<!-- ------------------------------------------------- -->
<!-- Side section -->
<!-- All Content here, can be in they own file in they own module's -->
<!-- ------------------------------------------------- -->

<div class="alert alert-info mt-3" role="alert">
    To-to. Side actions to come here.
</div>

<!-- ------------------------------------------------- -->
<!-- Timer -->
<!-- ------------------------------------------------- -->
<div class="card mt-3">
    <div class="card-header">
        <p>Timer <i>(Demo)</i></p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p>00:00:00</p>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary bi bi-play">Start / Pause</button>
            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------- -->
<!-- TASK'S -->
<!-- Shows the task's related to the ticket -->
<!-- ------------------------------------------------- -->
@if($tasks !== null)
    @include('tdtickets::partials.task_widget')
@endif

<!-- ------------------------------------------------- -->
<!-- KB - If KB Module exists -->
<!-- Automatically matches the tickets to a KB article -->
<!-- ------------------------------------------------- -->
<livewire:kb-widget :ticket="$ticket" />
