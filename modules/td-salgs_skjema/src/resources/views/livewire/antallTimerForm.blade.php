<div class="row justify-content-center">
    <div class="col-md-5">
        <label for="antallTimer" class="form-label fw-bold">Hvor mange timer pr. år?</label>

        <select class="form-select" id="antallTimer" wire:model="antallTimer">
            
            @if ($estimatedHours)
                <option value="{{ $estimatedHours }}" selected>Timebank: {{$estimatedHours}} /år <i>- Anbefalt</i></option>
            @endif

            @if ($estimatedHours != 5)
                <option value="5">Timebank: 5 /år</option>
            @endif

            @if ($estimatedHours != 10)
                <option value="10">Timebank: 10 /år</option>
            @endif

            @if ($estimatedHours != 20)
                <option value="20">Timebank: 20 /år</option>
            @endif

            @if ($estimatedHours != 30)
                <option value="30">Timebank: 30 /år</option>
            @endif

            @if ($estimatedHours != 40)
                <option value="40">Timebank: 40 /år</option>
            @endif

        </select>

        <button class="btn btn-primary mt-3 bi bi-forward-fill" wire:click="updateHours">Neste</button>
    </div>
</div>