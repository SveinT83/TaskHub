<div>
    <div class="row">

    </div>

    <div class="row text-center">
        <div class="col-md-6">
            <button wire:click="setPrivate" class="btn btn-primary btn-lg">
                <x-card-secondary title="Privat">
                    <img src="{{ asset('modules/TdSalgsSkjema/images/family.png')}}" class="card-img-top" alt="...">
                </x-card-secondary>
            </button>
        </div>

        <div class="col-md-6">
            <button wire:click="setBusiness" class="btn btn-secondary btn-lg">
                <x-card-secondary title="Bedrift">
                    <img src="{{ asset('modules/TdSalgsSkjema/images/bussiness.png')}}" class="card-img-top" alt="...">
                </x-card-secondary>
            </button>
        </div>

    </div>
</div>
