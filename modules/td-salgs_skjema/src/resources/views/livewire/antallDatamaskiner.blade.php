<div class="row justify-content-center">
    <div class="col-md-5">
        <label for="antallDatamaskiner" class="form-label fw-bold">Hvor mange datamaskiner?</label>
        <input type="number" class="form-control form-control-lg" name="amount" id="antallDatamaskiner" wire:model="amount" min="0" value="{{$pb}}">

        <button class="btn btn-primary mt-3 bi bi-forward-fill" wire:click="updateAmountDatamaskiner"> Neste</button>
    </div>
</div>