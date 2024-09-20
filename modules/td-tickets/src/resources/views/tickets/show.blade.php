@extends('layouts.app')

@section('pageHeader')
    <h1>#{{ $ticket->status->id }} - {{ $ticket->title }}</h1>
@endsection

@section('content')
<div class="container-fluid mt-3">
    
    <!-- ------------------------------------------------- -->
    <!-- Ticket Header -->
    <!-- ------------------------------------------------- -->
    <div class="row pb-2 align-items-end border-bottom">
        <p class="col-md-2"><strong>Status:</strong> {{ $ticket->status->name }}</p>
        <p class="col-md-2"><strong>Kunde:</strong> {{ $ticket->client->name ?? 'N/A' }}</p>
        <p class="col-md-2"><strong>Tildelt til:</strong> {{ $ticket->assignedUser->name ?? 'Ikke tildelt' }}</p>

        <div class="col-md-2">
            <select class="form-select" aria-label="Default select example">
                <option selected>
                    {{ $ticket->assignedUser->name ?? 'Ikke tildelt' }}
                </option>
            </select>
        </div>

        <p class="col-md-2"><strong>Opprettet:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
        <p class="col-md-2"><strong>Forfallsdato:</strong> {{ $ticket->due_date ? $ticket->due_date->format('Y-m-d') : 'Ingen' }}</p>
    </div>



    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Section's -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <div class="row">



        <!-- ------------------------------------------------- -->
        <!-- Main section -->
        <!-- ------------------------------------------------- -->
        <div class="col-md-9">
            
            <!-- ------------------------------------------------- -->
            <!-- Ticket Body -->
            <!-- ------------------------------------------------- -->
            <div class="card mt-3">
                <div class="card-header">
                    <h2>Description:</h2>
                </div>
                <div class="card-body">
                    <div class="mt-2">{{ $ticket->description }}</div>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- Ticket Action -->
            <!-- ------------------------------------------------- -->
            <div class="card mt-3">
                <div class="card-header">
                    Ticket Action
                </div>
                <div class="card-body">

                    <!-- Accordion Start -->
                    <div class="accordion" id="ticketAction">

                        <!-- Ticket Reply -->
                        <div class="accordion-item">
                          <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReply" aria-expanded="true" aria-controls="collapseReply">
                              Reply
                            </button>
                          </h2>
                          <div id="collapseReply" class="accordion-collapse collapse show" data-bs-parent="#ticketAction">
                            <div class="accordion-body">
                                <!-- Ticket Reply Content START-->
                                <form class="row">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="The e-mail to the user if it exists.">
                                      </div>
                                      <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                      </div>

                                      <button class="btn btn-primary"> send </button>
                                </form>
                                <!-- Ticket Reply Content END-->
                            </div>
                          </div>
                        </div>

                        <!-- Ticket Time Track -->
                        <div class="accordion-item">
                          <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTime" aria-expanded="false" aria-controls="collapseTwo">
                              TimeTrack
                            </button>
                          </h2>
                          <div id="collapseTime" class="accordion-collapse collapse" data-bs-parent="#ticketAction">
                            <div class="accordion-body">
                                <!-- Ticket Time Content START -->
                                <form class="row align-items-end">
                                    <div class="col-md-4 mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Time spend:</label>
                                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Time spend in minutes">
                                      </div>
                                    <div class="col-md-4 mb-3">
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected>Time rate</option>
                                            <option value="1">Remote support</option>
                                            <option value="2">On Site</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <button class="btn btn-primary"> send </button>
                                    </div>
                                </form>
                                <!-- Ticket Time Content START -->
                            </div>
                          </div>
                        </div>
                    </div><!-- Accordion End -->
                </div>
            </div>


        </div>


        <!-- ------------------------------------------------- -->
        <!-- Side section -->
        <!-- All Content here, can be in they own file in they own module's -->
        <!-- ------------------------------------------------- -->
        <div class="col-md-3 border-start">

            <div class="alert alert-info mt-3" role="alert">
                To-to. Side actions to come here.
            </div>
            
            <!-- ------------------------------------------------- -->
            <!-- Timer -->
            <!-- ------------------------------------------------- -->
            <div class="card mt-3">
                <div class="card-header">
                    Timer
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
            <div class="card mt-3">
                <div class="card-header">
                    Task's
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
                                    <tr>
                                        <th scope="row"><input class="form-check-input" type="checkbox" value="" id="#"></th>
                                        <td>Task 1</td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><input class="form-check-input" type="checkbox" value="" id="#"></th>
                                        <td>Task 2</td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><input class="form-check-input" type="checkbox" value="" id="#"></th>
                                        <td>Task 3</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ------------------------------------------------- -->
            <!-- KB - If KB Module exists -->
            <!-- Automatically matches the tickets to a KB article -->
            <!-- ------------------------------------------------- -->
            <div class="card mt-3">
                <div class="card-header">
                    KB Article's
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

        </div>

    </div>
</div>
@endsection
