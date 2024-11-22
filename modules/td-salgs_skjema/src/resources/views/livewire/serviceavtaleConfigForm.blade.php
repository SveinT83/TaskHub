<!-- -------------------------------------------------------------------------------------------------- -->
<!-- COMPOMENT -->
<!-- src/Livewire/serviceavtaleConfig.php -->
<!-- -------------------------------------------------------------------------------------------------- -->
<div>

    <!-- ------------------------------------------------- -->
    <!-- Name of the service -->
    <!-- ------------------------------------------------- -->
    <a href="serviceavtale"><h3>{{$serviceData->name}}</h3></a>

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Price table START -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Antall:</th>
            <th scope="col">Pris:</th>
            <th scope="col">Sum:</th>
          </tr>
        </thead>
        <tbody>
            <!-- ------------------------------------------------- -->
            <!-- Users -->
            <!-- ------------------------------------------------- -->
            <tr class="table-secondary">
                <th scope="row"><a href="antallBrukere"><b>Brukere</b></a></th>
                <td>{{$amountUsers}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">Normal brukere</th>
                <td>{{$normalUsers}}</td>
                <td>{{$basePrice_normal_user}},-</td>
                <td>{{$normalUsersPrice}},-</td>
            </tr>
            <tr>
                <th scope="row">Basis brukere</th>
                <td>{{$basicUsers}}</td>
                <td>{{$basePrice_extra_user}},-</td>
                <td>{{$basicUsersPrice}},-</td>
            </tr>
            <tr>
                <th scope="row"></th>
                <td></td>
                <td>SUM: </td>
                <td>{{$sumTottUsers}},-</td>
            </tr>

            <!-- ------------------------------------------------- -->
            <!-- Computers -->
            <!-- ------------------------------------------------- -->
            <tr class="table-secondary">
                <th scope="row"><a href="antallDatamaskiner"><b>Datamaskiner</b></a></th>
                <td>{{$amountDatamaskiner}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">Bruker datamaskiner</th>
                <td>{{$normalComputers}}</td>
                <td>0,-</td>
                <td>{{$normalComputersPrice}},-</td>
            </tr>
            <tr>
                <th scope="row">Ekstra datamaskiner</th>
                <td>{{$extraComputers}}</td>
                <td>149,-</td>
                <td>{{$extraComputersPrice}},-</td>
            </tr>
            <tr>
                <th scope="row"></th>
                <td></td>
                <td>SUM: </td>
                <td>{{$sumTottComputers}},-</td>
            </tr>

            <!-- ------------------------------------------------- -->
            <!-- Block hours -->
            <!-- ------------------------------------------------- -->
            <tr class="table-secondary">
                <th scope="row"><a href="antallTimer"><b>Timebank</b></a></th>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th scope="row">Timebank {{$timebank}}</th>
                <td>1</td>
                <td>SUM: </td>
                <td>{{$timebankPrice}},-</td>
            </tr>

            <!-- ------------------------------------------------- -->
            <!-- Sum Tott -->
            <!-- ------------------------------------------------- -->
            <tr class="table-secondary">
                <th scope="row"></th>
                <td></td>
                <td><b>TOTT SUM:</b></td>
                <td><b>{{$sumTott}},-</b></td>
            </tr>

        </tbody>
    </table>
    <!-- ------------------------------------------------- -->
    <!-- Price table END -->
    <!-- ------------------------------------------------- -->

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- If Nextcloud show an estimated VM resource usage -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    @if($cpu != false)
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">Estimert ressursbruk</h4>
            <p>For å kunne kjøre Nextcloud stabilt med {{$amountUsers}} brukere, anbefaler vi: VCPU: {{$cpu}}, RAM: {{$ram}} GB og diskplass: {{$storage}} GB.</p>
            <hr>
            <p class="mb-0">Dette er inkludert i prisen og kan oppgraderes mot tilleg.</p>
        </div>
    @endif

    <!-- -------------------------------------------------------------------------------------------------- -->
    <!-- Navigation and create order -->
    <!-- -------------------------------------------------------------------------------------------------- -->
    
    <div class="row justify-content-between m-3 mt-5">
        <div class="col-md-5">
            <div class="row justify-content-start">
                <div class="col-md m-1">
                    <div class="row">
                        <a class="btn btn-outline-primary bi bi-arrow-counterclockwise" href="create"> Start på nytt</a>
                    </div>
                </div>

                <div class="col-md m-1">
                    <div class="row">
                        <a class="btn btn-outline-primary bi bi-people" href="antallBrukere"> Brukere</a>
                    </div>
                </div>

                <div class="col-md m-1">
                    <div class="row">
                        <a class="btn btn-outline-primary bi bi-pc-display" href="antallDatamaskiner"> Datamaskiner</a>
                    </div>
                </div>

                @if($timebank != 12)
                    <div class="col-md m-1">
                        <div class="row">
                            <a class="btn btn-outline-primary bi bi-hourglass-split" href="antallTimer">Timebank</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-2 m-1">
            <div class="row">
                <a class="btn btn-primary bi bi-gear" wire:click="processOrder" wire:loading.attr="disabled">
                    <span wire:loading.remove>Prosesser ordre</span>
                    <span wire:loading>Prosesserer...</span>
                </a>
            </div>
        </div>
    </div>

</div>