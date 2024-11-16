<div class="col-md-2" wire:poll.500ms="refresh">
    <div class="row">
    
        <div class="col-6">
            @if($pb == 'Privat')
                <p class="bi bi-person"> {{$pb}}</p>
            @elseif($pb == 'Bedrift')
                <p class="bi bi-building"> {{$pb}}</p>
            @else
                <p class="bi bi-person"> Ubestemt</p>
            @endif

            <p class="bi bi-box"> {{ $totalItems }} stk.</p>
        </div>

        <div class="col-6">
            <p class="bi bi-cart2"> {{ $totalPrice }} NOK</p>

            <i>
                @if($pb == 'Privat')
                    inkl. mva
                @elseif($pb == 'Bedrift')
                    eks. mva
                @endif
            </i>
        </div>

    </div>
</div>
