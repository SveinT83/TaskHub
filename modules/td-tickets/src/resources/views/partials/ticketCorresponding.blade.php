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
                        <div class="row">
                            <div class="col-md-11">
                                <p><strong>{{ $reply->user->name ?? $ticket->client->name }}</strong> - {{ $reply->created_at->format('Y-m-d H:i') }}</p>
                            </div>

                                @foreach($reply->timeSpends as $timeSpend)
                                    <div class="col-md-1">
                                        <i class="bi bi-stopwatch"> </i>
                                    </div>
                                @endforeach
                        </div>
                    </button>
                    </h2>
                    <div id="collapseReplyItem{{ $index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#ticketReplies" aria-labelledby="heading{{ $index }}">
                        <div class="accordion-body">
                            <!-- Ticket Reply Content START-->
                                <div class="row">
                                    <div class="card">
                                        <p class="card-body">{{ $reply->message }}</p>

                                        @foreach($reply->timeSpends as $timeSpend)
                                            <div class="card-footer text-body-secondary">
                                                <i class="bi bi-stopwatch"> Time spendt: {{ $timeSpend->time_spend }} min.</i>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            <!-- Ticket Reply Content END-->
                        </div>
                    </div>
                </div>
            @endforeach
        </div><!-- Accordion End -->
    </div>
</div>