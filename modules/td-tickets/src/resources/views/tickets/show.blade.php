@extends('layouts.app')

@section('pageHeader')

    <!-- ------------------------------------------------- -->
    <!-- Ticket Header -->
    <!-- ------------------------------------------------- -->
    <div class="row">
        <h1 class="col-md-2">#{{ $ticket->status->id }} - {{ $ticket->title }}</h1>
        <p class="col-md-2"><strong>Status:</strong> {{ $ticket->status->name }}</p>
        <p class="col-md-2"><strong>Client:</strong> {{ $ticket->client->name ?? 'N/A' }}</p>
        <p class="col-md-2"><strong>Assigned:</strong> {{ $ticket->assignedUser->name ?? 'Unassignet' }}</p>
        <p class="col-md-2"><strong>Created:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
        <p class="col-md-2"><strong>Due date:</strong> {{ $ticket->due_date ? $ticket->due_date->format('Y-m-d') : 'Ingen' }}</p>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
@endsection

@section('content')
<div class="container-fluid">



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
            <!-- Corresponding -->
            <!-- ------------------------------------------------- -->
            <div class="card mt-3">
                <div class="card-header">
                  <h2>Corresponding:</h2>
                </div>
                <div class="card-body">
            
                    <!-- Accordion Start -->
                    <div class="accordion" id="ticketReplies">
                        @foreach($replies as $index => $reply)
                            <!-- Ticket Reply Item -->
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReplyItem{{ $index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapseReplyItem{{ $index }}">
                                    <strong>{{ $reply->user->name ?? $ticket->client->name }}</strong> - {{ $reply->created_at->format('Y-m-d H:i') }}
                                </button>
                              </h2>
                              <div id="collapseReplyItem{{ $index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#ticketReplies" aria-labelledby="heading{{ $index }}">
                                <div class="accordion-body">
                                    <!-- Ticket Reply Content START-->
                                    <p>{{ $reply->message }}</p>
                                    <!-- Ticket Reply Content END-->
                                </div>
                              </div>
                            </div>
                        @endforeach
                    </div><!-- Accordion End -->
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
                                <form method="POST" action="{{ route('tickets.reply', $ticket->id) }}">
                                  @csrf
                              
                                  <div class="row">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-postadresse</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $ticket->client->main_email ?? '' }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="message" class="form-label">Melding</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                    </div>
                                  </div>

                                  <div class="row">

                                    <div class="col-md-4 mb-3">
                                      <label for="timeSpend" class="form-label">Time spent (min):</label>
                                      <input type="number" id="timeSpend" name="timeSpend" class="form-control" placeholder="Time spent in minutes"> 
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="timeRate" class="form-label">Time Rate:</label>
                                        <select class="form-select" id="timeRate" name="timeRate" aria-label="Select Time Rate">
                                            @foreach($timeRates as $rate)
                                                <option value="{{ $rate->id }}">
                                                    {{ $rate->name }} - {{ number_format($rate->price, 2) }} NOK @if($rate->taxable) (Taxable) @else (Non-Taxable) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                              
                                    <div class="col-md-4 mb-3">
                                        <button class="btn btn-primary" type="submit" name="action" value="send_response">Send Responce</button>
                                        <button class="btn btn-secondary" type="submit" name="action" value="internal_note">Internal Note</button>
                                    </div>
                                  </div>
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

                                  <!-- Ticket Time Rows START -->
                                  <form class="row">
                                      @foreach($timeSpends as $time)
                                        <div class="row">
                                            <div class="col-md-1">
                                                <p>{{ $time->time_spend }} min</p>
                                            </div>
                                        </div>
                                      @endforeach
                                        </form>
                                  <!-- Ticket Time Rows END -->

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
