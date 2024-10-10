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
<!-- Task If task module exists -->
<!-- ------------------------------------------------- -->
@if($tasks->count() > 0)
<div class="card mt-3">
    <div class="card-header">
        <p>Tasks</p>
    </div>
    <div class="card-body">
        <div class="row">
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
        </div>
    </div>
</div>
@else
<div class="card mt-3">
    <div class="card-header">
        <p>No tasks available</p>
    </div>
</div>
@endif

<!-- ------------------------------------------------- -->
<!-- KB - If KB Module exists -->
<!-- Automatically matches the tickets to a KB article -->
<!-- ------------------------------------------------- -->
<div class="card mt-3">
    <div class="card-header">
        <p>KB Article's <i>(Demo)</i></p>
    </div>
    <div class="card-body">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Accordion Item #1
                </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Accordion Item #2
                </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Accordion Item #3
                </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
