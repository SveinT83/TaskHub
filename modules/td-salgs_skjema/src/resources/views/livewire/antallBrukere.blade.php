<div class="row justify-content-center">
    <div class="col-md-5">
        <label for="antallBruekre" class="form-label fw-bold">Hvor mange brukere?</label>
        <input type="number" class="form-control form-control-lg" name="amount" id="antallBruekre" wire:model="amount" min="1" value="{{$pb}}">

        <button class="btn btn-primary mt-3 bi bi-forward-fill" wire:click="updateAmountUsers"> Neste</button>
    </div>
</div>