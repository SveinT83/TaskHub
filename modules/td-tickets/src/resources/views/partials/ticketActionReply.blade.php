<!-- ------------------------------------------------- -->
<!-- FORM START -->
<!-- This form allows users to reply to a ticket. It includes fields for the reply message and a submit button. -->
<!-- ------------------------------------------------- -->
<form method="POST" action="{{ route('tickets.reply', $ticket->id) }}">
    @csrf

    <div class="row">

        <!-- ------------------------------------------------- -->
        <!-- Receiver -->
        <!-- Displays the client's email address associated with this ticket -->
        <!-- ------------------------------------------------- -->
        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Receiver</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $ticket->client->main_email ?? '' }}" required>
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Receiver -->
        <!-- This field allows the user to reply to the end user or add an internal note -->
        <!-- ------------------------------------------------- -->
        <div class="mb-3">
            <label for="message" class="form-label">Reply:</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>

    </div>
    <div class="row justify-content-between align-items-end">

        <!-- ------------------------------------------------- -->
        <!-- Time spent -->
        <!-- Time spent in minutes. This is for billing purposes -->
        <!-- ------------------------------------------------- -->
        <div class="col-md-4 mb-3">
            <label for="timeSpend" class="form-label fw-bold">Time spent (min):</label>
            <input type="number" id="timeSpend" name="timeSpend" class="form-control" placeholder="Time spent in minutes"> 
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Time Rate -->
        <!-- Time Rate -->
        <!-- This dropdown allows the user to select a rate for the time spent. The rates are fetched from the database and displayed here. -->
        <!-- ------------------------------------------------- -->
        <div class="col-md-4 mb-3">
            <label for="timeRate" class="form-label fw-bold">Time Rate:</label>
            <select class="form-select" id="timeRate" name="timeRate" aria-label="Select Time Rate">
                @foreach($timeRates as $rate)
                    <option value="{{ $rate->id }}">
                        {{ $rate->name }} - {{ number_format($rate->price, 2) }} NOK @if($rate->taxable) (Taxable) @else (Non-Taxable) @endif
                    </option>
                @endforeach
            </select>
        </div>

        <!-- ------------------------------------------------- -->
        <!-- Buttons -->
        <!-- Two buttons. One for sending ticket responce and one to only save the responce as an internal note -->
        <!-- ------------------------------------------------- -->
        <div class="col-md-4 mb-3">
            <button class="btn btn-primary" type="submit" name="action" value="send_response">Send Responce</button>
            <button class="btn btn-secondary" type="submit" name="action" value="internal_note">Internal Note</button>
        </div>

    </div>
</form>