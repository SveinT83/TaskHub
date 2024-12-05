<div>
    <div class="row">

        @foreach ($servicePakker as $pakke)
            <a href="#" class="col-lg-4" wire:click="setService({{ $pakke->id }})">
                <x-card-secondary title="{{ $pakke->name }}" footer="{{ $pakke->private ? 'Privat' : 'Bedrift' }}">
                    <p>{{ $pakke->description }}</p>
                </x-card-secondary>
            </a>
        @endforeach

    </div>
</div>
