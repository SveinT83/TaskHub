<div>
    <h3>{{$serviceData->name}}</h3>

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
            <!-- Brukere -->
            <tr class="table-secondary">
                <th scope="row"><b>Brukere</b></th>
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
                <td>149,-</td>
                <td>{{$basicUsersPrice}},-</td>
            </tr>
            <tr>
                <th scope="row"></th>
                <td></td>
                <td>SUM: </td>
                <td>{{$sumTottUsers}},-</td>
            </tr>

            <!-- Datamaskiner -->
            <tr class="table-secondary">
                <th scope="row"><b>Datamaskiner</b></th>
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

            <!-- Tottal sum -->
            <tr class="table-secondary">
                <th scope="row"></th>
                <td></td>
                <td>TOTT SUM: </td>
                <td>{{$sumTott}},-</td>
            </tr>

        </tbody>
    </table>
</div>