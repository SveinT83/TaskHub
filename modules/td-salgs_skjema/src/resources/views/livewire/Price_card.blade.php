<div class="col-md-6" wire:poll.500ms="refresh">
    <div class="row">

        <div class="col-6">

            @if($pb == 'Privat')
                <p class="bi bi-person"> {{$pb}}</p>
            @elseif($pb == 'Bedrift')
                <p class="bi bi-building"> {{$pb}}</p>
            @else
                <p class="bi bi-person"> Ubestemt</p>
            @endif
            
        </div>
    
        <div class="col-6">
            @if($service)
                <div class="row">
                    <h3>{{$service}}</h3>
                </div>
            @endif

            <div class="row mt-3">

                @if($service)
                    <div class="col-6">
                        <b>Rabbatt:</b> 
                        <p>-{{ $discount }} kr</p>
                    </div>
                
                    <div class="col-6"> @endif

                <b>Tottal:</b> 
                <p class="bi bi-cart2"> {{ $totalPrice }} NOK
                    <i>
                        @if($pb == 'Privat')
                            inkl. mva
                        @elseif($pb == 'Bedrift')
                            eks. mva
                        @endif
                    </i>
                </p>
                @if($service) </div> @endif
            </div>
        </div>

    </div>
</div>
